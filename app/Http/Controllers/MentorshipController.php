<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MentorshipController extends Controller
{
    /**
     * Show available alumni mentors.
     */
    public function index(Request $request)
    {
        $student = auth()->user();

        abort_unless(
            $student !== null && in_array($student->role, ['student', 'alumni'], true) && $student->is_active && !$student->is_blocked,
            403,
            'Only students and alumni can view the mentor network.'
        );

        $query = User::query()
            ->where('role', 'alumni')
            ->where('id', '!=', $student->id)
            ->where('is_active', true)
            ->where('is_blocked', false);

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('skills', 'like', '%' . $search . '%')
                    ->orWhere('current_company', 'like', '%' . $search . '%')
                    ->orWhere('current_designation', 'like', '%' . $search . '%')
                    ->orWhere('bio', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('department')) {
            $department = trim(
                (string) $request->input('department')
            );

            $query->where(
                'department',
                $department
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Mentor Pagination
        |--------------------------------------------------------------------------
        |
        | Show a maximum of 20 mentors per page.
        | withQueryString() keeps search and department filters when the
        | student moves between pagination pages.
        |
        */

        $mentors = $query
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Current Student's Requests
        |--------------------------------------------------------------------------
        |
        | We only need mentorship statuses for mentors visible on the
        | current page. This keeps the page lighter as the database grows.
        |
        */

        $myRequests = Mentorship::query()
            ->where('student_id', $student->id)
            ->whereIn('mentor_id', $mentors->pluck('id'))
            ->get()
            ->keyBy('mentor_id');

        $departments = User::query()
            ->whereIn('role', ['student', 'alumni'])
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view(
            'mentors.index',
            compact(
                'mentors',
                'myRequests',
                'departments'
            )
        );
    }


    /**
     * Student sends mentorship request.
     */
    public function requestMentor(
        Request $request,
        User $mentor
    ) {
        $student = auth()->user();

        abort_unless(
            $student !== null && in_array($student->role, ['student', 'alumni'], true) && $student->is_active && !$student->is_blocked,
            403,
            'Only students and alumni can request mentorship.'
        );

        abort_unless(
            $mentor->isAlumni(),
            404,
            'Mentor not found.'
        );

        if (
            !$mentor->is_active ||
            $mentor->is_blocked
        ) {
            return back()->with(
                'error',
                'This mentor is currently unavailable.'
            );
        }

        if (
            (int) $student->id ===
            (int) $mentor->id
        ) {
            return back()->with(
                'error',
                'You cannot send a mentorship request to yourself.'
            );
        }

        $validated = $request->validate([
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $description = trim(
            (string) ($validated['description'] ?? '')
        );

        if ($description === '') {
            $description =
                'I would like to request mentorship for career guidance.';
        }

        $existing = Mentorship::query()
            ->where('student_id', $student->id)
            ->where('mentor_id', $mentor->id)
            ->first();

        if (
            $existing !== null &&
            $existing->status === 'pending'
        ) {
            return back()->with(
                'error',
                'You already have a pending mentorship request with this mentor.'
            );
        }

        if (
            $existing !== null &&
            $existing->status === 'accepted'
        ) {
            return back()->with(
                'error',
                'This mentor has already accepted your mentorship request.'
            );
        }

        if (
            $existing !== null &&
            $existing->status === 'completed'
        ) {
            return back()->with(
                'error',
                'Your mentorship with this mentor has already been completed.'
            );
        }

        $mentorship = DB::transaction(
            function () use (
                $existing,
                $student,
                $mentor,
                $description
            ) {
                if ($existing !== null) {
                    $existing->update([
                        'title' => 'Career Mentorship Request',
                        'description' => $description,
                        'status' => 'pending',
                        'rejection_reason' => null,
                        'started_at' => null,
                        'ended_at' => null,
                    ]);

                    return $existing->fresh();
                }

                return Mentorship::create([
                    'mentor_id' => $mentor->id,
                    'student_id' => $student->id,
                    'title' => 'Career Mentorship Request',
                    'description' => $description,
                    'status' => 'pending',
                    'rejection_reason' => null,
                    'started_at' => null,
                    'ended_at' => null,
                ]);
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Notification -> Mentor
        |--------------------------------------------------------------------------
        */

        NotificationService::send(
            $mentor,
            'mentorship_request',
            'New Mentorship Request',
            $student->name . ' sent you a mentorship request.',
            route('mentors.requests'),
            'high',
            $mentorship,
            $student
        );

        return back()->with(
            'success',
            'Mentorship request sent successfully.'
        );
    }


    /**
     * Student cancels pending request.
     */
    public function cancelRequest(User $mentor)
    {
        $student = auth()->user();

        abort_unless(
            $student !== null && in_array($student->role, ['student', 'alumni'], true) && $student->is_active && !$student->is_blocked,
            403,
            'Only students and alumni can cancel mentorship requests.'
        );

        $mentorship = Mentorship::query()
            ->where('student_id', $student->id)
            ->where('mentor_id', $mentor->id)
            ->where('status', 'pending')
            ->first();

        if ($mentorship === null) {
            return back()->with(
                'error',
                'No pending mentorship request was found.'
            );
        }

        $mentorship->delete();

        return back()->with(
            'success',
            'Mentorship request cancelled successfully.'
        );
    }


    /**
     * Alumni views received mentorship requests.
     */
    public function myRequests()
    {
        $mentor = auth()->user();

        abort_unless(
            $mentor !== null && $mentor->isAlumni(),
            403,
            'Only alumni mentors can view mentorship requests.'
        );

        $requests = Mentorship::query()
            ->with([
                'student',
                'mentor',
            ])
            ->where(
                'mentor_id',
                $mentor->id
            )
            ->orderByRaw(
                "
                CASE
                    WHEN status = 'pending' THEN 1
                    WHEN status = 'accepted' THEN 2
                    WHEN status = 'completed' THEN 3
                    WHEN status = 'rejected' THEN 4
                    ELSE 5
                END
                "
            )
            ->orderByDesc('updated_at')
            ->get();

        return view(
            'mentors.requests',
            compact('requests')
        );
    }


    /**
     * Alumni accepts request.
     */
    public function accept(Mentorship $mentorship)
    {
        $mentor = auth()->user();

        abort_unless(
            $mentor !== null && $mentor->isAlumni(),
            403,
            'Only alumni mentors can accept mentorship requests.'
        );

        abort_unless(
            (int) $mentorship->mentor_id ===
            (int) $mentor->id,
            403,
            'You are not authorized to manage this mentorship request.'
        );

        if ($mentorship->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending mentorship requests can be accepted.'
            );
        }

        $mentorship->update([
            'status' => 'accepted',
            'started_at' => now(),
            'ended_at' => null,
            'rejection_reason' => null,
        ]);

        $mentorship->load('student');

        /*
        |--------------------------------------------------------------------------
        | Notification -> Student
        |--------------------------------------------------------------------------
        */

        NotificationService::send(
            $mentorship->student,
            'mentorship_accepted',
            'Mentorship Request Accepted',
            $mentor->name . ' accepted your mentorship request.',
            route('mentors.index'),
            'high',
            $mentorship,
            $mentor
        );

        return back()->with(
            'success',
            'Mentorship request accepted successfully.'
        );
    }


    /**
     * Alumni rejects request.
     */
    public function reject(
        Request $request,
        Mentorship $mentorship
    ) {
        $mentor = auth()->user();

        abort_unless(
            $mentor !== null && $mentor->isAlumni(),
            403,
            'Only alumni mentors can reject mentorship requests.'
        );

        abort_unless(
            (int) $mentorship->mentor_id ===
            (int) $mentor->id,
            403,
            'You are not authorized to manage this mentorship request.'
        );

        if ($mentorship->status !== 'pending') {
            return back()->with(
                'error',
                'Only pending mentorship requests can be rejected.'
            );
        }

        $validated = $request->validate([
            'rejection_reason' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $reason = trim(
            (string) ($validated['rejection_reason'] ?? '')
        );

        if ($reason === '') {
            $reason = 'Rejected by mentor.';
        }

        $mentorship->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'started_at' => null,
            'ended_at' => null,
        ]);

        $mentorship->load('student');

        /*
        |--------------------------------------------------------------------------
        | Notification -> Student
        |--------------------------------------------------------------------------
        */

        NotificationService::send(
            $mentorship->student,
            'mentorship_rejected',
            'Mentorship Request Rejected',
            $mentor->name . ' rejected your mentorship request. Reason: ' . $reason,
            route('mentors.index'),
            'medium',
            $mentorship,
            $mentor
        );

        return back()->with(
            'success',
            'Mentorship request rejected successfully.'
        );
    }


    /**
     * Alumni completes accepted mentorship.
     */
    public function complete(Mentorship $mentorship)
    {
        $mentor = auth()->user();

        abort_unless(
            $mentor !== null && $mentor->isAlumni(),
            403,
            'Only alumni mentors can complete mentorships.'
        );

        abort_unless(
            (int) $mentorship->mentor_id ===
            (int) $mentor->id,
            403,
            'You are not authorized to manage this mentorship.'
        );

        if ($mentorship->status !== 'accepted') {
            return back()->with(
                'error',
                'Only accepted mentorships can be marked as completed.'
            );
        }

        $mentorship->update([
            'status' => 'completed',
            'started_at' => $mentorship->started_at ?? now(),
            'ended_at' => now(),
        ]);

        $mentorship->load('student');

        /*
        |--------------------------------------------------------------------------
        | Notification -> Student
        |--------------------------------------------------------------------------
        */

        NotificationService::send(
            $mentorship->student,
            'mentorship_completed',
            'Mentorship Completed',
            'Your mentorship with ' . $mentor->name . ' has been completed.',
            route('mentors.index'),
            'medium',
            $mentorship,
            $mentor
        );

        return back()->with(
            'success',
            'Mentorship completed successfully.'
        );
    }
}