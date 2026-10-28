<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('donations', function(Blueprint $table) { $table->string('bkash_number',30)->nullable(); $table->string('nagad_number',30)->nullable(); }); }
 public function down(): void { Schema::table('donations', fn(Blueprint $table) => $table->dropColumn(['bkash_number','nagad_number'])); }
};
