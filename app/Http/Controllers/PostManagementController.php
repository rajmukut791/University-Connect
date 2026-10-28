<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\JobPosting;
use App\Services\EncryptedFileService;
use App\Support\PostAccess;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostManagementController extends Controller
{
    private function post(string $type, int $id)
    {
        $class = match ($type) {
            'job' => JobPosting::class, 'event' => Event::class, 'donation' => Donation::class,
            default => abort(404),
        };
        $post = $class::findOrFail($id);
        abort_unless(PostAccess::canManage(auth()->user(), $type, $post), 403);

        return $post;
    }

    public function edit(string $type, int $id)
    {
        $post = $this->post($type, $id);

        return view('posts.edit', compact('type', 'post'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $post = $this->post($type, $id);
        $rules = ['title' => ['required', 'string', 'max:255'], 'description' => ['required', 'string']];
        $rules += match ($type) {
            'job' => [
                'company_name' => ['required', 'string', 'max:255'], 'location' => ['required', 'string', 'max:255'],
                'type' => ['required', 'in:full_time,part_time,internship,contract,temporary,remote,hybrid'],
                'experience_level' => ['required', 'in:entry,mid,senior'],
                'salary_range' => ['nullable', 'string', 'max:255'], 'requirements' => ['required', 'string'],
                'benefits' => ['nullable', 'string'], 'positions_available' => ['required', 'integer', 'min:1'],
                'contact_email' => ['nullable', 'email', 'max:255', 'required_without:contact_phone'],
                'contact_phone' => ['nullable', 'string', 'max:30', 'required_without:contact_email'],
                'application_url' => ['nullable', 'url', 'max:2048'], 'deadline' => ['nullable', 'date'],
                'job_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            'event' => [
                'type' => ['required', 'string', 'max:100'], 'location' => ['required', 'string', 'max:255'],
                'start_date' => ['required', 'date'], 'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'start_time' => ['required', 'date_format:H:i'], 'end_time' => ['required', 'date_format:H:i'],
                'capacity' => ['nullable', 'integer', 'min:1'],
                'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
            'donation' => [
                'category' => ['nullable', 'string', 'max:100'],
                'bkash_number' => ['nullable','string','regex:/^(?:\+?88)?01[3-9][0-9]{8}$/'],
                'nagad_number' => ['nullable','string','regex:/^(?:\+?88)?01[3-9][0-9]{8}$/'], 'target_amount' => ['required', 'numeric', 'min:1'],
                'deadline' => ['nullable', 'date'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            ],
        };
        $data = $request->validate($rules);
        if ($type === 'event') {
            $start = $data['start_date'] . ' ' . $data['start_time'];
            $end = $data['end_date'] . ' ' . $data['end_time'];
            if (strtotime($end) <= strtotime($start)) {
                throw ValidationException::withMessages(['end_time' => 'The event must end after it starts.']);
            }
            $data['event_date'] = $start;
        }
        if ($type === 'donation' && (float) $data['target_amount'] < (float) $post->collected_amount) {
            throw ValidationException::withMessages(['target_amount' => 'The target cannot be less than the amount already collected.']);
        }
        $dateFields = $type === 'event' ? ['start_date', 'start_time', 'end_date', 'end_time'] : ['deadline'];
        foreach ($dateFields as $field) {
            if (!array_key_exists($field, $data)) continue;
            $old = $post->$field;
            if ($old instanceof \DateTimeInterface) $old = $old->format('Y-m-d');
            if (str_ends_with($field, 'time')) $old = $old ? substr($old, 0, 5) : null;
            if ((string) $old !== (string) $data[$field]) {
                throw ValidationException::withMessages([$field => 'Use Apply Date Extension to request date changes for admin review.']);
            }
        }
        // Authors cannot publish their own edits or change ownership/fund totals.
        $isManagement = in_array(auth()->user()->role, ['admin', 'super_admin'], true);
        if (!$isManagement) {
            $data['status'] = 'pending';
        }
        $imageField = match ($type) { 'job' => 'job_image', 'event' => 'cover_image', 'donation' => 'image' };
        $oldImage = $post->{$imageField};
        unset($data[$imageField]);
        $files = app(EncryptedFileService::class);
        if ($request->hasFile($imageField)) {
            $data[$imageField] = $type === 'job'
                ? $request->file($imageField)->store('jobs', 'public')
                : $files->store($request->file($imageField), $type . '-images');
        }
        try {
            $post->update($data);
        } catch (\Throwable $error) {
            if (isset($data[$imageField])) $files->delete($data[$imageField]);
            throw $error;
        }
        if (isset($data[$imageField])) $files->delete($oldImage);

        return redirect()->route(match ($type) {
            'job' => 'jobs.show', 'event' => 'events.show', 'donation' => 'donations.show',
        }, $post)->with('success', $isManagement ? 'Post updated successfully.' : 'Post updated and submitted for approval.');
    }

    public function destroy(string $type, int $id)
    {
        $post = $this->post($type, $id);
        $image = $post->{match ($type) { 'job' => 'job_image', 'event' => 'cover_image', 'donation' => 'image' }};
        \Illuminate\Support\Facades\DB::transaction(function () use ($type, $post) {
            if (\Illuminate\Support\Facades\Schema::hasTable('post_extension_requests')) {
                \App\Models\PostExtensionRequest::where('post_type', $type)->where('post_id', $post->id)->delete();
            }
            $post->delete();
        });
        app(EncryptedFileService::class)->delete($image);

        \App\Services\NotificationService::management($type.'_deleted', ucfirst($type).' Deleted', auth()->user()->name.' deleted the post: '.$post->title, null, 'medium', null, auth()->user());
        return redirect()->route('newsfeed.index')->with('success', 'Post deleted successfully.');
    }
}
