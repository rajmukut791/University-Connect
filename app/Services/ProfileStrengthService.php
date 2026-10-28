<?php

namespace App\Services;

use App\Models\User;

class ProfileStrengthService
{
    public function analyze(User $user): array
    {
        $checks = [
            'name' => [
                'label' => 'Full Name',
                'score' => 10,
                'completed' => !empty($user->name),
            ],
            'email' => [
                'label' => 'Email Address',
                'score' => 10,
                'completed' => !empty($user->email),
            ],
            'phone' => [
                'label' => 'Phone Number',
                'score' => 10,
                'completed' => !empty($user->phone),
            ],
            'department' => [
                'label' => 'Department',
                'score' => 10,
                'completed' => !empty($user->department),
            ],
            'batch' => [
                'label' => 'Batch / Passing Year',
                'score' => 10,
                'completed' => !empty($user->batch),
            ],
            'skills' => [
                'label' => 'Skills',
                'score' => 15,
                'completed' => !empty($user->skills),
            ],
            'bio' => [
                'label' => 'Professional Bio',
                'score' => 15,
                'completed' => !empty($user->bio) && strlen($user->bio) >= 30,
            ],
            'linkedin' => [
                'label' => 'LinkedIn Profile',
                'score' => 10,
                'completed' => !empty($user->linkedin_url),
            ],
            'github' => [
                'label' => 'GitHub / Portfolio',
                'score' => 10,
                'completed' => !empty($user->github_url) || !empty($user->portfolio_url),
            ],
        ];

        $score = 0;
        $missing = [];
        $completed = [];

        foreach ($checks as $check) {
            if ($check['completed']) {
                $score += $check['score'];
                $completed[] = $check['label'];
            } else {
                $missing[] = $check['label'];
            }
        }

        $level = match (true) {
            $score >= 85 => 'Excellent',
            $score >= 70 => 'Strong',
            $score >= 50 => 'Good',
            $score >= 30 => 'Improve',
            default => 'Weak',
        };

        $message = match (true) {
            $score >= 85 => 'Your profile is highly optimized for career and mentorship opportunities.',
            $score >= 70 => 'Your profile is strong, but a few details can make it more professional.',
            $score >= 50 => 'Your profile is good. Add missing career details to improve networking.',
            $score >= 30 => 'Your profile needs improvement. Complete important information.',
            default => 'Your profile is incomplete. Add basic details to get real profile completeness.',
        };

        return [
            'score' => min($score, 100),
            'level' => $level,
            'message' => $message,
            'missing' => $missing,
            'completed' => $completed,
        ];
    }
}