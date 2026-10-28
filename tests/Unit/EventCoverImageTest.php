<?php

use App\Models\Event;
use App\Models\User;
use App\Services\EncryptedFileService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->boolean('is_blocked')->default(false);
        $table->dateTime('blocked_until')->nullable();
        $table->text('blocked_reason')->nullable();
        $table->timestamps();
    });
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('cover_image')->nullable();
        $table->timestamps();
    });
    Storage::fake('public');
    Storage::fake('local');
    $this->imageBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jRZkAAAAASUVORK5CYII=');
});

test('public event covers load without a public storage link', function () {
    Storage::disk('public')->put('events/covers/test.png', $this->imageBytes);
    $event = Event::create(['cover_image' => 'events/covers/test.png']);
    $this->actingAs(new User(['is_active'=>true, 'is_blocked'=>false, 'role' => 'super_admin']))
        ->get(route('secure.events.cover', $event))
        ->assertOk()->assertHeader('Content-Type', 'image/png')
        ->assertContent($this->imageBytes);
});

test('encrypted event covers are decrypted for authenticated users', function () {
    $file = UploadedFile::fake()->createWithContent('cover.png', $this->imageBytes);
    $path = app(EncryptedFileService::class)->store($file, 'events/covers');
    $event = Event::create(['cover_image' => $path]);
    expect(Storage::disk('local')->get($path))->not->toBe($this->imageBytes);
    $this->actingAs(new User(['is_active'=>true, 'is_blocked'=>false, 'role' => 'student']))
        ->get(route('secure.events.cover', $event))
        ->assertOk()->assertHeader('Content-Type', 'image/png')
        ->assertContent($this->imageBytes);
});

test('guests cannot access event covers', function () {
    $event = Event::create(['cover_image' => 'events/covers/test.png']);
    $this->get(route('secure.events.cover', $event))->assertRedirect(route('login'));
});

test('missing event covers return not found', function (string $path) {
    $event = Event::create(['cover_image' => $path]);
    $this->actingAs(new User(['is_active'=>true, 'is_blocked'=>false, 'role' => 'admin']))
        ->get(route('secure.events.cover', $event))->assertNotFound();
})->with(['events/covers/missing.png', 'private/encrypted/events/covers/missing.enc']);
