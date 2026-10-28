<?php
namespace App\Services;

use App\Models\{User, Message, Mentorship, Event};

class DashboardActivityService
{
    public static function events(User $user)
    {
        return Event::whereHas('participants', fn ($query) => $query->where('user_id', $user->id)->where('status', 'approved'));
    }

    public static function connections(User $user, bool $pending = false)
    {
        $statuses = $pending ? ['pending'] : ['accepted', 'completed'];
        $ids = Mentorship::where('student_id', $user->id)->whereIn('status', $statuses)->pluck('mentor_id');
        if ($user->role === 'alumni') {
            $incoming = Mentorship::where('mentor_id', $user->id)->whereIn('status', $statuses)->pluck('student_id');
            $ids = $pending ? $incoming : $ids->merge($incoming);
        }
        return User::whereIn('id', $ids);
    }

    public static function unread(User $user)
    {
        return Message::where('recipient_id', $user->id)->where('is_read', false)->whereNull('read_at')
            ->where(fn ($query) => $query->where('deleted_by_receiver', false)->orWhereNull('deleted_by_receiver'));
    }
}
