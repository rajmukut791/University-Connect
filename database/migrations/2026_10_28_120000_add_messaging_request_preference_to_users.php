<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        if (!Schema::hasColumn('users', 'requires_message_request')) Schema::table('users', fn (Blueprint $table) => $table->boolean('requires_message_request')->default(true));
    }
    public function down(): void {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('requires_message_request'));
    }
};
