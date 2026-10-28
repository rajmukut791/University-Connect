<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') { \Illuminate\Support\Facades\Schema::table('users', fn (\Illuminate\Database\Schema\Blueprint $table) => $table->string('alumni_id')->nullable()->change()); return; }

        /*
        |--------------------------------------------------------------------------
        | Remove foreign key from users.alumni_id if an old FK exists
        |--------------------------------------------------------------------------
        */

        $database = DB::getDatabaseName();

        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = 'users'
              AND COLUMN_NAME = 'alumni_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$database]);

        foreach ($foreignKeys as $foreignKey) {
            DB::statement(
                "ALTER TABLE `users`
                 DROP FOREIGN KEY `{$foreignKey->CONSTRAINT_NAME}`"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Change alumni_id from integer/bigint to string
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE `users`
            MODIFY `alumni_id` VARCHAR(255) NULL
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') return;

        /*
        |--------------------------------------------------------------------------
        | Do not automatically convert back to integer
        |--------------------------------------------------------------------------
        |
        | Values such as ALU2026-0001 cannot safely be converted to BIGINT.
        |
        */
    }
};