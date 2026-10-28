<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasColumn('post_extension_requests', 'original_dates')) {
            Schema::table('post_extension_requests', fn (Blueprint $table) => $table->json('original_dates')->nullable());
        }
    }

    public function down(): void
    {
        Schema::table('post_extension_requests', fn (Blueprint $table) => $table->dropColumn('original_dates'));
    }
};
