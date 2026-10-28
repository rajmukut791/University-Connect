<?php

namespace App\Http\Controllers;

use App\Services\EncryptedFileService;
use App\Services\ProfileStrengthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Edit Profile
    |--------------------------------------------------------------------------
    */
    public function edit(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Load Alumni Work Experiences
        |--------------------------------------------------------------------------
        |
        | Only alumni need professional work-history records.
        |
        */
        if ($user->isAlumni()) {
            $user->load([
                'workExperiences',
            ]);
        }

        $profileScore = $this->calculateProfileScore($user);

        $profileSuggestions = $this->generateProfileSuggestions(
            $user,
            $profileScore
        );

        /*
        |--------------------------------------------------------------------------
        | Existing AI Profile Strength Service
        |--------------------------------------------------------------------------
        |
        | We preserve your existing service so nothing currently depending on
        | it is broken.
        |
        */
        $profileStrength = app(ProfileStrengthService::class)
            ->analyze($user);

        /*
        |--------------------------------------------------------------------------
        | Role Based Views
        |--------------------------------------------------------------------------
        */
        if ($user->isStudent()) {
            return view('profile.settings', [
                'user' => $user,
                'profileScore' => $profileScore,
                'profileSuggestions' => $profileSuggestions,
                'profileStrength' => $profileStrength,
            ]);
        }

        if ($user->isAlumni()) {
            return view('profile.settings', [
                'user' => $user,
                'profileScore' => $profileScore,
                'profileSuggestions' => $profileSuggestions,
                'profileStrength' => $profileStrength,
                'workExperiences' => $user->workExperiences,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Admin + Super Admin
        |--------------------------------------------------------------------------
        */
        return view('profile.settings', [
            'user' => $user,
            'profileScore' => $profileScore,
            'profileSuggestions' => $profileSuggestions,
            'profileStrength' => $profileStrength,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Common Fields
        |--------------------------------------------------------------------------
        */
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'profile_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];

        if ($user->isStudent() || $user->isAlumni()) {
            // University-managed identity cannot be changed through self-service requests.
            unset($rules['name']);
            foreach (['name', 'email', 'official_id', 'student_id', 'alumni_id'] as $field) {
                if (!$request->exists($field)) continue;
                $value = $request->input($field);
                if ((!is_scalar($value) && $value !== null) || (string) $value !== (string) $user->$field) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        $field => 'Only an administrator can change your name, email or university ID.',
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Student Specific Fields
        |--------------------------------------------------------------------------
        */
        if ($user->isStudent()) {
            $rules = array_merge($rules, [
                'department' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'batch' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'skills' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'bio' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

                'github_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],

                'linkedin_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],

                'portfolio_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Alumni Specific Fields
        |--------------------------------------------------------------------------
        */
        if ($user->isAlumni()) {
            $rules = array_merge($rules, [
                'department' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'batch' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'skills' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'bio' => [
                    'nullable',
                    'string',
                    'max:2000',
                ],

                'github_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],

                'linkedin_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],

                'portfolio_url' => [
                    'nullable',
                    'url',
                    'max:255',
                ],
            ]);
        }

        if ($user->isAlumni()) {
            $rules['requires_message_request'] = ['sometimes', 'boolean'];
            foreach (['current_company', 'current_designation', 'current_job_type', 'previous_company', 'previous_designation'] as $field) {
                $rules[$field] = ['nullable', 'string', 'max:255'];
            }
            $rules['work_experience_years'] = ['nullable', 'string', 'max:100'];
            $rules['previous_job_details'] = ['nullable', 'string', 'max:3000'];
        }

        if ($request->exists('_profile_section')) {
            $sections = app(\App\Services\ProfileSettingsService::class)->sections($user);
            $section = $request->input('_profile_section');
            abort_unless(is_string($section) && isset($sections[$section]), 422);
            $rules = array_intersect_key($rules, $sections[$section]['fields']);
        }

        $validated = $request->validate($rules);

        /*
        |--------------------------------------------------------------------------
        | Profile Image
        |--------------------------------------------------------------------------
        */
         unset($validated['profile_image']);

       if (isset($rules['profile_image']) && $request->hasFile('profile_image')) {

    $encryptedFiles = app(
        EncryptedFileService::class
    );

    /*
     * Encrypt and save new profile image
     * inside private storage.
     */
    $validated['profile_image'] =
        $encryptedFiles->store(
            $request->file('profile_image'),
            'profile-images'
        );
       }

        /*
        |--------------------------------------------------------------------------
        | Save User Profile
        |--------------------------------------------------------------------------
        */
        $oldImage = $user->profile_image;
        try {
            $user->fill($validated);
            $user->save();
        } catch (\Throwable $error) {
            if (isset($validated['profile_image'])) app(EncryptedFileService::class)->delete($validated['profile_image']);
            throw $error;
        }
        if (isset($validated['profile_image']) && $oldImage) app(EncryptedFileService::class)->delete($oldImage);

        return Redirect::route('profile.edit')
            ->with(
                'status',
                'profile-updated'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Account
    |--------------------------------------------------------------------------
    */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag(
            'userDeletion',
            [
                'password' => [
                    'required',
                    'current_password',
                ],
            ]
        );

        $user = $request->user();

        if ($user->profile_image) {
          app(
           EncryptedFileService::class
           )->delete(
           $user->profile_image
           );
       }

        auth()->guard()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Based Profile Score
    |--------------------------------------------------------------------------
    */
    private function calculateProfileScore($user): int
    {
        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */
        if ($user->isStudent()) {
            $fields = [
                'name',
                'email',
                'phone',
                'department',
                'batch',
                'skills',
                'bio',
                'address',
                'profile_image',
                'github_url',
                'linkedin_url',
                'portfolio_url',
            ];

            return $this->calculateFieldCompletion(
                $user,
                $fields
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Alumni
        |--------------------------------------------------------------------------
        */
        if ($user->isAlumni()) {
            $fields = [
                'name',
                'email',
                'phone',
                'department',
                'batch',
                'skills',
                'bio',
                'address',
                'profile_image',
                'github_url',
                'linkedin_url',
                'portfolio_url',
            ];

            $completed = 0;

            foreach ($fields as $field) {
                if (!empty($user->{$field})) {
                    $completed++;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Work Experience counts as an important profile item
            |--------------------------------------------------------------------------
            */
            $hasExperience = $user
                ->workExperiences()
                ->exists();

            if ($hasExperience) {
                $completed++;
            }

            $totalFields = count($fields) + 1;

            return (int) round(
                ($completed / $totalFields) * 100
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Admin / Super Admin
        |--------------------------------------------------------------------------
        */
        $fields = [
            'name',
            'email',
            'phone',
            'address',
            'profile_image',
        ];

        return $this->calculateFieldCompletion(
            $user,
            $fields
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generic Field Completion Calculator
    |--------------------------------------------------------------------------
    */
    private function calculateFieldCompletion(
        $user,
        array $fields
    ): int {
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($user->{$field})) {
                $completed++;
            }
        }

        if (count($fields) === 0) {
            return 0;
        }

        return (int) round(
            ($completed / count($fields)) * 100
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Role Based Suggestions
    |--------------------------------------------------------------------------
    */
    private function generateProfileSuggestions(
        $user,
        int $profileScore
    ): array {
        $suggestions = [];

        /*
        |--------------------------------------------------------------------------
        | Common
        |--------------------------------------------------------------------------
        */
        if ($profileScore < 60) {
            $suggestions[] =
                'Complete your profile information to improve visibility.';
        }

        if (empty($user->profile_image)) {
            $suggestions[] =
                'Upload a professional profile photo.';
        }

        if (empty($user->phone)) {
            $suggestions[] =
                'Add your phone number for better communication.';
        }

        /*
        |--------------------------------------------------------------------------
        | Student Suggestions
        |--------------------------------------------------------------------------
        */
        if ($user->isStudent()) {
            if (empty($user->department)) {
                $suggestions[] =
                    'Add your department information.';
            }

            if (empty($user->batch)) {
                $suggestions[] =
                    'Add your academic batch.';
            }

            if (empty($user->skills)) {
                $suggestions[] =
                    'Add your technical and professional skills.';
            }

            if (empty($user->bio)) {
                $suggestions[] =
                    'Write your career goal and academic interests.';
            }

            if (empty($user->github_url)) {
                $suggestions[] =
                    'Add your GitHub profile to showcase projects and code.';
            }

            if (empty($user->linkedin_url)) {
                $suggestions[] =
                    'Add your LinkedIn profile for professional networking.';
            }

            if (empty($user->portfolio_url)) {
                $suggestions[] =
                    'Add your portfolio or personal website.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Alumni Suggestions
        |--------------------------------------------------------------------------
        */
        if ($user->isAlumni()) {
            if (empty($user->department)) {
                $suggestions[] =
                    'Add your university department.';
            }

            if (empty($user->batch)) {
                $suggestions[] =
                    'Add your batch or passing year.';
            }

            if (empty($user->skills)) {
                $suggestions[] =
                    'Add your professional skills and expertise.';
            }

            if (empty($user->bio)) {
                $suggestions[] =
                    'Write a professional bio about your experience and expertise.';
            }

            if (empty($user->linkedin_url)) {
                $suggestions[] =
                    'Add your LinkedIn profile for professional networking.';
            }

            if (
                !$user
                    ->workExperiences()
                    ->exists()
            ) {
                $suggestions[] =
                    'Add at least one work experience to strengthen your alumni profile.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Completed Profile
        |--------------------------------------------------------------------------
        */
        if (empty($suggestions)) {
            $suggestions[] =
                'Your profile looks strong. Keep it updated regularly.';
        }

        return $suggestions;
    }
}
