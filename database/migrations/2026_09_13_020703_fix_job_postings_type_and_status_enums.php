<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') { \Illuminate\Support\Facades\Schema::table('job_postings', function (\Illuminate\Database\Schema\Blueprint $table) { $table->string('type')->default('full_time')->change(); $table->string('status')->default('pending')->change(); }); return; }

        if (!Schema::hasTable('job_postings')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Step 1: Temporarily allow old + new type values
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE job_postings
            MODIFY type ENUM(
                'full-time',
                'part-time',
                'full_time',
                'part_time',
                'internship',
                'contract',
                'temporary',
                'remote',
                'hybrid'
            ) NOT NULL DEFAULT 'full_time'
        ");

        /*
        |--------------------------------------------------------------------------
        | Convert old type values
        |--------------------------------------------------------------------------
        */

        DB::table('job_postings')
            ->where('type', 'full-time')
            ->update([
                'type' => 'full_time',
            ]);

        DB::table('job_postings')
            ->where('type', 'part-time')
            ->update([
                'type' => 'part_time',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Final Type Enum
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE job_postings
            MODIFY type ENUM(
                'full_time',
                'part_time',
                'internship',
                'contract',
                'temporary',
                'remote',
                'hybrid'
            ) NOT NULL DEFAULT 'full_time'
        ");


        /*
        |--------------------------------------------------------------------------
        | Step 2: Temporarily allow old + new status values
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE job_postings
            MODIFY status ENUM(
                'open',
                'closed',
                'filled',
                'pending',
                'approved',
                'rejected'
            ) NOT NULL DEFAULT 'pending'
        ");

        /*
        |--------------------------------------------------------------------------
        | Existing old open jobs should become approved
        |--------------------------------------------------------------------------
        */

        DB::table('job_postings')
            ->where('status', 'open')
            ->update([
                'status' => 'approved',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Final Status Enum
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE job_postings
            MODIFY status ENUM(
                'pending',
                'approved',
                'rejected',
                'closed',
                'filled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') return;

        if (!Schema::hasTable('job_postings')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Allow both sets temporarily
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE job_postings
            MODIFY type ENUM(
                'full_time',
                'part_time',
                'full-time',
                'part-time',
                'internship',
                'contract',
                'temporary',
                'remote',
                'hybrid'
            ) NOT NULL DEFAULT 'full_time'
        ");

        DB::table('job_postings')
            ->where('type', 'full_time')
            ->update([
                'type' => 'full-time',
            ]);

        DB::table('job_postings')
            ->where('type', 'part_time')
            ->update([
                'type' => 'part-time',
            ]);

        DB::table('job_postings')
            ->whereIn('type', [
                'remote',
                'hybrid',
            ])
            ->update([
                'type' => 'contract',
            ]);

        DB::statement("
            ALTER TABLE job_postings
            MODIFY type ENUM(
                'full-time',
                'part-time',
                'internship',
                'contract',
                'temporary'
            ) NOT NULL DEFAULT 'full-time'
        ");


        DB::statement("
            ALTER TABLE job_postings
            MODIFY status ENUM(
                'pending',
                'approved',
                'rejected',
                'closed',
                'filled',
                'open'
            ) NOT NULL DEFAULT 'approved'
        ");

        DB::table('job_postings')
            ->whereIn('status', [
                'pending',
                'approved',
                'rejected',
            ])
            ->update([
                'status' => 'open',
            ]);

        DB::statement("
            ALTER TABLE job_postings
            MODIFY status ENUM(
                'open',
                'closed',
                'filled'
            ) NOT NULL DEFAULT 'open'
        ");
    }
};