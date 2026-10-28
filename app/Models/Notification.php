<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'actor_id',
        'type',
        'title',
        'message',
        'action_url',
        'notifiable_type',
        'notifiable_id',
        'is_read',
        'read_at',
        'priority',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function actor()
    {
        return $this->belongsTo(
            User::class,
            'actor_id'
        );
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isUnread(): bool
    {
        return !$this->is_read;
    }

    public function isRead(): bool
    {
        return $this->is_read;
    }

    public function isHighPriority(): bool
    {
        return $this->priority === 'high';
    }

    /*
    |--------------------------------------------------------------------------
    | Student Notification Types
    |--------------------------------------------------------------------------
    |
    | Student শুধু নিজের relevant/personal notification দেখবে।
    | Management/admin-only notification এখানে রাখা হবে না।
    |
    */

    public static function studentTypes(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            'message',
            'call',

            /*
            |--------------------------------------------------------------------------
            | Mentorship
            |--------------------------------------------------------------------------
            */

            'mentorship',
            'mentorship_request',
            'mentorship_accepted',
            'mentorship_rejected',
            'mentorship_completed',

            /*
            |--------------------------------------------------------------------------
            | Jobs
            |--------------------------------------------------------------------------
            */

            'job',
            'job_application',
            'job_application_submitted',
            'job_application_approved',
            'job_application_rejected',

            /*
            |--------------------------------------------------------------------------
            | Events
            |--------------------------------------------------------------------------
            */

            'event',
            'event_registration',
            'event_registration_submitted',
            'event_registration_approved',
            'event_registration_rejected',
            'event_approved',
            'event_rejected',
            'event_reminder',

            /*
            |--------------------------------------------------------------------------
            | Alumni Conversion
            |--------------------------------------------------------------------------
            */

            'alumni_conversion_submitted',
            'alumni_conversion_rejected',

            /*
            |--------------------------------------------------------------------------
            | Account
            |--------------------------------------------------------------------------
            */

            'account',
            'account_updated',
            'account_blocked',
            'account_unblocked',

            /*
            |--------------------------------------------------------------------------
            | General System Notification
            |--------------------------------------------------------------------------
            */

            'system',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Alumni Notification Types
    |--------------------------------------------------------------------------
    |
    | Alumni নিজের message, mentorship, job posting, event এবং account-related
    | notification দেখতে পারবে।
    |
    */

    public static function alumniTypes(): array
    {
        return [
            /*
            |--------------------------------------------------------------------------
            | Message
            |--------------------------------------------------------------------------
            */

            'message',
            'call',

            /*
            |--------------------------------------------------------------------------
            | Mentorship
            |--------------------------------------------------------------------------
            */

            'mentorship',
            'mentorship_request',
            'mentorship_accepted',
            'mentorship_rejected',
            'mentorship_completed',

            /*
            |--------------------------------------------------------------------------
            | Jobs
            |--------------------------------------------------------------------------
            */

            'job',
            'job_application',
            'job_application_submitted',
            'job_application_approved',
            'job_application_rejected',
            'job_posting_approved',
            'job_posting_rejected',
            'job_application_received',

            /*
            |--------------------------------------------------------------------------
            | Events
            |--------------------------------------------------------------------------
            */

            'event',
            'event_registration',
            'event_registration_submitted',
            'event_registration_approved',
            'event_registration_rejected',
            'event_approved',
            'event_rejected',
            'event_reminder',

            /*
            |--------------------------------------------------------------------------
            | Alumni Conversion
            |--------------------------------------------------------------------------
            */

            'alumni',
            'alumni_conversion_approved',

            /*
            |--------------------------------------------------------------------------
            | Account
            |--------------------------------------------------------------------------
            */

            'account',
            'account_updated',
            'account_blocked',
            'account_unblocked',

            /*
            |--------------------------------------------------------------------------
            | General System Notification
            |--------------------------------------------------------------------------
            */

            'system',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role-Based Notification Visibility
    |--------------------------------------------------------------------------
    |
    | Important:
    |
    | Admin / Super Admin:
    |   - তাদের user_id-তে যেসব notification পাঠানো হয়েছে, সব দেখতে পারবে।
    |
    | Student / Alumni:
    |   - শুধু allowed notification types দেখতে পারবে।
    |
    | Note:
    |   - এই scope অন্য user's notification দেখায় না।
    |   - Controller-এ অবশ্যই user_id = logged-in user filter থাকবে।
    |
    */

    public function scopeVisibleTo(
        Builder $query,
        User $user
    ): Builder {
        /*
        |--------------------------------------------------------------------------
        | Super Admin / Admin
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $user->role,
                [
                    'super_admin',
                    'admin',
                ],
                true
            )
        ) {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'student') {
            return $query->whereIn(
                'type',
                self::studentTypes()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Alumni
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'alumni') {
            return $query->whereIn(
                'type',
                self::alumniTypes()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Unknown Role
        |--------------------------------------------------------------------------
        |
        | কোনো unexpected role হলে কোনো notification দেখাবে না।
        |
        */

        return $query->whereRaw('1 = 0');
    }
}