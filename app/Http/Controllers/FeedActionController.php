<?php

namespace App\Http\Controllers;

use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedShare;
use Illuminate\Http\Request;

class FeedActionController extends Controller
{
    public function like(Request $request)
    {
        $validated = $this->target($request);

        $query = FeedLike::where('user_id', auth()->id())
            ->where('feedable_type', $validated['feedable_type'])
            ->where('feedable_id', $validated['feedable_id']);

        $like = $query->first();
        $liked = false;

        if ($like) {
            $like->delete();
        } else {
            FeedLike::create([
                'user_id' => auth()->id(),
                'feedable_type' => $validated['feedable_type'],
                'feedable_id' => $validated['feedable_id'],
            ]);

            $liked = true;
        }

        $likesCount = FeedLike::where('feedable_type', $validated['feedable_type'])
            ->where('feedable_id', $validated['feedable_id'])
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        }

        return back()->with(
            'success',
            $liked ? 'Post liked.' : 'Like removed.'
        );
    }

    public function comment(Request $request)
    {
        $validated = $this->target($request) + $request->validate(['comment'=>['required','string','max:1000']]);

        FeedComment::create([
            'user_id' => auth()->id(),
            'feedable_type' => $validated['feedable_type'],
            'feedable_id' => $validated['feedable_id'],
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function share(Request $request)
    {
        $validated = $this->target($request);

        $share = FeedShare::firstOrCreate([
            'user_id' => auth()->id(),
            'feedable_type' => $validated['feedable_type'],
            'feedable_id' => $validated['feedable_id'],
        ]);

        $sharesCount = FeedShare::where('feedable_type', $validated['feedable_type'])
            ->where('feedable_id', $validated['feedable_id'])
            ->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'created' => $share->wasRecentlyCreated,
                'shares_count' => $sharesCount,
                'message' => $share->wasRecentlyCreated
                    ? 'Share recorded successfully.'
                    : 'Share already recorded.',
            ]);
        }

        return back()->with(
            'success',
            $share->wasRecentlyCreated
                ? 'Post shared.'
                : 'You have already shared this post.'
        );
    }
    private function target(Request $request): array
    {
        $types = ['job'=>\App\Models\JobPosting::class, 'event'=>\App\Models\Event::class, 'donation'=>\App\Models\Donation::class];
        $allowed = array_merge(array_keys($types), array_values($types));
        $data = $request->validate(['feedable_type'=>['required', \Illuminate\Validation\Rule::in($allowed)], 'feedable_id'=>['required','integer','min:1']]);
        $class = $types[$data['feedable_type']] ?? $data['feedable_type'];
        $post = $class::findOrFail($data['feedable_id']);
        abort_unless(in_array($post->status, ['approved','active','published'], true) && \App\Services\PostLifecycleService::accepting($post), 403, 'This post is not available for interaction.');
        $data['feedable_type'] = $class;
        return $data;
    }

}
