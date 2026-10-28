<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') { \Illuminate\Support\Facades\Schema::table('event_participants', fn (\Illuminate\Database\Schema\Blueprint $table) => $table->string('status')->default('pending')->change()); return; }

        DB::statement("ALTER TABLE event_participants MODIFY status ENUM('pending','approved','rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') return;

        DB::statement("ALTER TABLE event_participants MODIFY status ENUM('registered','cancelled') DEFAULT 'registered'");
    }
};