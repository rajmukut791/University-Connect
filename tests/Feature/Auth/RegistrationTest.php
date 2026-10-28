<?php
test('public self registration is unavailable', function () {
    $this->get('/register')->assertOk()->assertSee('University Connect');
    $this->post('/register', ['name'=>'Unapproved User','email'=>'unknown@example.com','password'=>'password','password_confirmation'=>'password'])->assertStatus(405);
    $this->assertGuest();
    $this->assertDatabaseMissing('users',['email'=>'unknown@example.com']);
});
