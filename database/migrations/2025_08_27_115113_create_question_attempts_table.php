<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('question_attempts', function (Blueprint $t) {
        $t->id();
        $t->foreignId('quiz_attempt_id')->constrained()->cascadeOnDelete();
        $t->foreignId('question_id')->constrained()->cascadeOnDelete();
        $t->json('user_answer')->nullable(); // store given answer
        $t->boolean('is_correct')->default(false);
        $t->unsignedSmallInteger('time_taken_sec')->default(0);
        $t->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('question_attempts'); }

};
