<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('question_id')->nullable()->constrained()->nullOnDelete();
            $table->string('context', 32);
            $table->boolean('helpful');
            $table->string('source', 64)->nullable();
            $table->timestamps();

            $table->index(['context', 'module_id', 'question_id']);
            $table->unique(
                ['user_id', 'context', 'module_id', 'question_id', 'source'],
                'note_feedback_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_feedback');
    }
};
