<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Optional per-section progress (simple state)
        Schema::create('user_section_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->constrained()->cascadeOnDelete();
            $table->enum('status',['not_started','in_progress','completed'])->default('not_started');
            $table->unsignedTinyInteger('percent_complete')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->unique(['user_id','section_id']);
            $table->timestamps();
        });

        // Link questions to a section (nullable keeps old quizzes working)
        Schema::table('questions', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('module_id')->constrained('sections')->nullOnDelete();
             if (!Schema::hasColumn('questions','choices')) $table->json('choices')->nullable();
             if (!Schema::hasColumn('questions','answer'))  $table->json('answer')->nullable();
        });
    }

    public function down(): void {
        Schema::table('questions', fn(Blueprint $t) => $t->dropConstrainedForeignId('section_id'));
        Schema::dropIfExists('user_section_progress');
        Schema::dropIfExists('sections');
    }
};