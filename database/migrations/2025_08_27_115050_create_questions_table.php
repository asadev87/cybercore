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
    Schema::create('questions', function (Blueprint $t) {
        $t->id();
        $t->foreignId('module_id')->constrained()->cascadeOnDelete();
        $t->enum('type', ['mcq','truefalse','fib']);
        $t->unsignedTinyInteger('difficulty')->default(1); // 1-5
        $t->text('stem');                     // the question text
        $t->json('options')->nullable();      // for MCQ (array of strings)
        $t->json('answer');                   // correct answer(s)
        $t->text('explanation')->nullable();  // feedback
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('questions'); }

};
