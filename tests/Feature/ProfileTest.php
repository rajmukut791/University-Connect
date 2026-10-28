<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('students update permitted profile information without changing managed identity', function () {
    $user = User::factory()->create();
    $this->actingAs($user)->patch('/profile', ['phone'=>'01712345678','address'=>'Campus Road'])->assertSessionHasNoErrors()->assertRedirect('/profile');
    expect($user->fresh()->phone)->toBe('01712345678')->and($user->fresh()->email)->toBe($user->email)->and($user->fresh()->name)->toBe($user->name);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('profile settings renders separate editors for each role', function (string $role) {
    $user = User::factory()->create(['role' => $role]);
    $response = $this->actingAs($user)->get('/profile');
    $response->assertOk()->assertSee('Profile Settings')->assertSee('settings-personal', false);
    if ($role === 'alumni') {
        $response->assertSee('settings-history', false)->assertSee('settings-messaging', false);
    }
    if (in_array($role, ['admin', 'super_admin'])) {
        $response->assertDontSee('settings-academic', false);
    }
})->with(['student', 'alumni', 'admin', 'super_admin']);

test('editing one profile section preserves other sections and ignores cross section fields', function () {
    $user = User::factory()->create(['skills' => 'PHP', 'phone' => '01711111111', 'department' => 'CSE']);
    $this->actingAs($user)->patch('/profile', [
        '_profile_section' => 'academic', 'department' => 'English', 'batch' => '56',
        'phone' => '01899999999', 'skills' => 'Unexpected replacement',
    ])->assertSessionHasNoErrors()->assertRedirect('/profile');
    expect($user->fresh()->department)->toBe('English')
        ->and($user->fresh()->phone)->toBe('01711111111')
        ->and($user->fresh()->skills)->toBe('PHP');
});

test('admins can save contact details separately and cannot edit alumni sections', function () {
    $user = User::factory()->create(['role' => 'admin']);
    $this->actingAs($user)->patch('/profile', [
        '_profile_section' => 'personal', 'name' => $user->name, 'phone' => '01712345678',
    ])->assertSessionHasNoErrors();
    expect($user->fresh()->phone)->toBe('01712345678');
    $this->patch('/profile', ['_profile_section' => 'messaging', 'requires_message_request' => 0])->assertStatus(422);
});

test('alumni messaging editor preserves professional summary', function () {
    $user = User::factory()->create(['role' => 'alumni', 'current_company' => 'Campus Ltd', 'requires_message_request' => true]);
    $this->actingAs($user)->patch('/profile', ['_profile_section' => 'messaging', 'requires_message_request' => 0])->assertSessionHasNoErrors();
    expect($user->fresh()->requires_message_request)->toBeFalse()->and($user->fresh()->current_company)->toBe('Campus Ltd');
});
