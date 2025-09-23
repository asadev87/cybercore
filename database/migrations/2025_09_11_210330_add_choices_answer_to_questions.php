<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions','choices')) {
                $table->json('choices')->nullable();
            }
            if (!Schema::hasColumn('questions','answer')) {
                $table->json('answer')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('questions', function (Blueprint $table) {
            if (Schema::hasColumn('questions','choices')) {
                $table->dropColumn('choices');
            }
            if (Schema::hasColumn('questions','answer')) {
                $table->dropColumn('answer');
            }
        });
    }
};