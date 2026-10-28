<?php

namespace App\Http\Controllers;

use App\Models\PostExtensionRequest;
use App\Services\{NotificationService, PostLifecycleService};
use App\Support\PostAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PostLifecycleController extends Controller
{
    private function post(string $type, int $id)
    {
        $class = PostLifecycleService::TYPES[$type] ?? abort(404);
        return $class::findOrFail($id);
    }

    public function index(Request $request)
    {
        $filters = $request->validate(['type'=>['nullable','in:job,event,donation'], 'status'=>['nullable','in:all,pending,approved,rejected']]);
        $type = $filters['type'] ?? 'job';
        $status = $filters['status'] ?? 'all';
        $management = in_array($request->user()->role, ['admin', 'super_admin'], true);
        $owner = match ($type) { 'job'=>'posted_by', 'event'=>'created_by', 'donation'=>'user_id' };
        $posts = (PostLifecycleService::TYPES[$type])::whereNotNull('archived_at')->where($owner,$request->user()->id)->latest('archived_at')->paginate(20)->withQueryString();
        $query = PostExtensionRequest::where('post_type',$type)->when(!$management, fn ($q) => $q->where('user_id',$request->user()->id));
        $counts = (clone $query)->selectRaw('status, COUNT(*) AS aggregate')->groupBy('status')->pluck('aggregate','status');
        $requests = $query->with(['user','reviewer'])->when($status !== 'all', fn ($q) => $q->where('status',$status))->latest()->orderByDesc('id')->paginate(20,['*'],'requests_page')->withQueryString();
        $requestPosts = (PostLifecycleService::TYPES[$type])::whereIn('id',$requests->pluck('post_id'))->get()->keyBy('id');
        return view('posts.archive', compact('type','status','posts','requests','management','counts','requestPosts'));
    }

    public function archive(string $type, int $id)
    {
        $post = $this->post($type, $id);
        abort_unless(PostAccess::canManage(auth()->user(), $type, $post), 403);
        $post->update(['archived_at' => now()]);
        return redirect()->route(match ($type) { 'job' => 'jobs.show', 'event' => 'events.show', 'donation' => 'donations.show' }, $post)->with('success', 'Post archived successfully. It is no longer visible in the newsfeed or active listings.');
    }

    public function form(string $type, int $id)
    {
        $post = $this->post($type, $id);
        abort_unless(PostAccess::canManage(auth()->user(), $type, $post) && PostAccess::ownerId($type, $post) === (int) auth()->id(), 403);
        abort_if(PostExtensionRequest::where('post_type', $type)->where('post_id', $id)->where('status', 'pending')->exists(), 409, 'An extension request is already awaiting review.');
        return view('posts.extension', compact('type', 'post'));
    }

    public function apply(Request $request, string $type, int $id)
    {
        $post = $this->post($type, $id);
        abort_unless(PostAccess::canManage($request->user(), $type, $post) && PostAccess::ownerId($type, $post) === (int) $request->user()->id, 403);
        $rules = ['reason' => ['required', 'string', 'min:10', 'max:3000']];
        $rules += $type === 'event' ? [
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'start_time' => ['required', 'date_format:H:i'], 'end_time' => ['required', 'date_format:H:i'],
        ] : ['deadline' => ['required', 'date', 'after:today']];
        $data = $request->validate($rules);
        $reason = $data['reason']; unset($data['reason']);
        $this->validateDates($type, $post, $data);
        DB::transaction(function () use ($post, $type, $data, $reason, $request) {
            $lockedPost = $post->newQuery()->whereKey($post->id)->lockForUpdate()->firstOrFail();
            $this->validateDates($type, $lockedPost, $data);
            $originalDates = collect(array_keys($data))->mapWithKeys(function ($field) use ($lockedPost) {
                $value = $lockedPost->getAttribute($field);
                return [$field => $value instanceof \DateTimeInterface ? $value->format('Y-m-d') : $value];
            })->all();
            if (PostExtensionRequest::where('post_type', $type)->where('post_id', $post->id)->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['reason' => 'An extension request is already awaiting review.']);
            }
            PostExtensionRequest::create(['post_type' => $type, 'post_id' => $post->id, 'user_id' => $request->user()->id, 'dates' => $data, 'original_dates' => $originalDates, 'reason' => $reason, 'status' => 'pending']);
        });
        NotificationService::management('post_extension_requested', ucfirst($type).' Date Extension', $post->title.' — '.$reason,
            route('posts.archive', ['type' => $type]), 'medium', $post, $request->user());
        return redirect()->route('posts.archive', ['type' => $type])->with('success', 'Extension request sent to admin. Dates will change only after approval.');
    }

    private function validateDates(string $type, $post, array $dates): void
    {
        $end = $type === 'event'
            ? \Carbon\Carbon::parse($dates['end_date'].' '.$dates['end_time'])
            : \Carbon\Carbon::parse($dates['deadline'])->endOfDay();
        if ($end->lte(now()) || ($type === 'event' && $end->lte(\Carbon\Carbon::parse($dates['start_date'].' '.$dates['start_time'])))
            || (PostLifecycleService::expiry($post) && $end->lte(PostLifecycleService::expiry($post)))) {
            throw ValidationException::withMessages([$type === 'event' ? 'end_date' : 'deadline' => 'Choose a future date later than the current expiry.']);
        }
    }

    public function review(Request $request, PostExtensionRequest $extension)
    {
        abort_unless(in_array($request->user()->role, ['admin', 'super_admin'], true), 403);
        $data = $request->validate(['decision' => ['required', 'in:approved,rejected'], 'review_note' => ['nullable', 'string', 'max:3000', 'required_if:decision,rejected']]);
        DB::transaction(function () use ($request, $extension, $data) {
            $extension = PostExtensionRequest::whereKey($extension->id)->lockForUpdate()->firstOrFail();
            abort_unless($extension->status === 'pending', 409, 'This request has already been reviewed.');
            $class = PostLifecycleService::TYPES[$extension->post_type];
            $post = $class::whereKey($extension->post_id)->lockForUpdate()->firstOrFail();
            abort_if((int)$extension->user_id === (int)$request->user()->id || PostAccess::ownerId($extension->post_type,$post) === (int)$request->user()->id, 403, 'Another administrator must review your own request.');
            if ($data['decision'] === 'approved') {
                $this->validateDates($extension->post_type, $post, $extension->dates);
                $dates = $extension->dates;
                if ($extension->post_type === 'event') $dates['event_date'] = $dates['start_date'].' '.$dates['start_time'];
                $post->update($dates + ['archived_at' => null, 'status' => 'approved']);
            }
            $extension->update(['status' => $data['decision'], 'review_note' => $data['review_note'] ?? null, 'reviewed_by' => $request->user()->id, 'reviewed_at' => now()]);
        });
        NotificationService::personal($extension->user_id, 'post_extension_reviewed', 'Date Extension '.ucfirst($data['decision']),
            'Your '.$extension->post_type.' date extension was '.$data['decision'].'. '.($data['review_note'] ?? ''),
            route('posts.archive', ['type' => $extension->post_type]), 'medium', $extension, $request->user());
        return back()->with('success', 'Extension '.$data['decision'].'.');
    }
}
