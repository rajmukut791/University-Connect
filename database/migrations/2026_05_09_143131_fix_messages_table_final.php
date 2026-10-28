<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'body')) {
                $table->text('body')->nullable();
            }

            if (!Schema::hasColumn('messages', 'message')) {
                $table->text('message')->nullable();
            }

            if (!Schema::hasColumn('messages', 'attachment')) {
                $table->string('attachment')->nullable();
            }

            if (!Schema::hasColumn('messages', 'attachment_name')) {
                $table->string('attachment_name')->nullable();
            }

            if (!Schema::hasColumn('messages', 'attachment_type')) {
                $table->string('attachment_type')->nullable();
            }

            if (!Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable();
            }

            if (!Schema::hasColumn('messages', 'is_edited')) {
                $table->boolean('is_edited')->default(false);
            }

            if (!Schema::hasColumn('messages', 'deleted_by_sender')) {
                $table->boolean('deleted_by_sender')->default(false);
            }

            if (!Schema::hasColumn('messages', 'deleted_by_receiver')) {
                $table->boolean('deleted_by_receiver')->default(false);
            }
        });

        if (Schema::hasColumn('messages', 'body')) {
            if (DB::getDriverName() === 'mysql') DB::statement('ALTER TABLE messages MODIFY body TEXT NULL');
        }

        if (Schema::hasColumn('messages', 'message')) {
            if (DB::getDriverName() === 'mysql') DB::statement('ALTER TABLE messages MODIFY message TEXT NULL');
        }
    }

    public function down(): void
    {
        //
    }
};