<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('certificates')) {   // <-- guard
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('module_id')->constrained()->cascadeOnDelete();
                $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
                $table->string('code')->unique();
                $table->timestamps();
            });
        } else {
            // Optional: ensure required columns/keys exist
            if (! Schema::hasColumn('certificates','quiz_attempt_id')) {
                Schema::table('certificates', function (Blueprint $table) {
                    $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
                });
            }
            if (! Schema::hasColumn('certificates','code')) {
                Schema::table('certificates', function (Blueprint $table) {
                    $table->string('code')->unique();
                });
            }
        }
    }
  public function down(): void { Schema::dropIfExists('certificates'); }
};
