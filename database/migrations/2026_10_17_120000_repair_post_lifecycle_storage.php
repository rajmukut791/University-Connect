<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        // Repair databases where the earlier migration was recorded but storage is missing.
        // The original migration checks each column/table and preserves existing records.
        $migration = require __DIR__.'/2026_10_13_140000_add_post_archives_and_extension_requests.php';
        $migration->up();
    }

    public function down(): void
    {
        // Repairs may restore pre-existing storage; rollback must not delete its records.
    }
};
