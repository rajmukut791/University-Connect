<?php

namespace App\Services;

use App\Models\User;

class ProfileSettingsService
{
    public function sections(User $user): array
    {
        $sections = [
            'personal' => ['title' => 'Personal Information', 'icon' => 'fa-user', 'fields' => [
                ...(!$user->isStudent() && !$user->isAlumni() ? ['name' => ['Full Name', 'text']] : []),
                'phone' => ['Phone', 'tel'], 'address' => ['Address', 'text'], 'profile_image' => ['Profile Image', 'file'],
            ]],
        ];

        if ($user->isStudent() || $user->isAlumni()) {
            $sections += [
                'academic' => ['title' => 'University Information', 'icon' => 'fa-graduation-cap', 'fields' => [
                    'department' => ['Department', 'text'], 'batch' => ['Batch', 'text'],
                ]],
                'career' => ['title' => 'Skills & Bio', 'icon' => 'fa-bullseye', 'fields' => [
                    'skills' => ['Skills (comma separated)', 'text'], 'bio' => ['Career Goal / Bio', 'textarea'],
                ]],
                'links' => ['title' => 'Professional Links', 'icon' => 'fa-link', 'fields' => [
                    'github_url' => ['GitHub', 'url'], 'linkedin_url' => ['LinkedIn', 'url'], 'portfolio_url' => ['Portfolio / Website', 'url'],
                ]],
            ];
        }

        if ($user->isAlumni()) {
            $sections += [
                'professional' => ['title' => 'Professional Summary', 'icon' => 'fa-briefcase', 'fields' => [
                    'current_company' => ['Current Organization', 'text'], 'current_designation' => ['Current Position', 'text'],
                    'current_job_type' => ['Employment Type', 'text'], 'work_experience_years' => ['Total Experience', 'text'],
                    'previous_company' => ['Previous Organization', 'text'], 'previous_designation' => ['Previous Position', 'text'],
                    'previous_job_details' => ['Previous Job Details', 'textarea'],
                ]],
                'messaging' => ['title' => 'Messaging Preference', 'icon' => 'fa-comments', 'fields' => [
                    'requires_message_request' => ['Require request before messaging', 'checkbox'],
                ]],
            ];
        }

        return $sections;
    }
}
