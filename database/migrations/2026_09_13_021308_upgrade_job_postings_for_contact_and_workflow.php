<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_postings')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Contact Information
        |--------------------------------------------------------------------------
        */

        Schema::table('job_postings', function (Blueprint $table) {

            if (!Schema::hasColumn('job_postings', 'contact_email')) {
                $table->string('contact_email')
                    ->nullable()
                    ->after('positions_available');
            }

            if (!Schema::hasColumn('job_postings', 'contact_phone')) {
                $table->string('contact_phone', 30)
                    ->nullable()
                    ->after('contact_email');
            }

            if (!Schema::hasColumn('job_postings', 'application_url')) {
                $table->string('application_url')
                    ->nullable()
                    ->after('contact_phone');
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Job Type Enum
        |--------------------------------------------------------------------------
        |
        | Temporarily support both old and new values.
        |
        */

        if (DB::getDriverName() === 'mysql') DB::statement("
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

        if (DB::getDriverName() === 'mysql') DB::statement("
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
        | Job Status Workflow
        |--------------------------------------------------------------------------
        */

        if (DB::getDriverName() === 'mysql') DB::statement("
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

        DB::table('job_postings')
            ->where('status', 'open')
            ->update([
                'status' => 'approved',
            ]);

        if (DB::getDriverName() === 'mysql') DB::statement("
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
        if (!Schema::hasTable('job_postings')) {
            return;
        }

        Schema::table('job_postings', function (Blueprint $table) {

            if (Schema::hasColumn('job_postings', 'application_url')) {
                $table->dropColumn('application_url');
            }

            if (Schema::hasColumn('job_postings', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }

            if (Schema::hasColumn('job_postings', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
        });
    }
};