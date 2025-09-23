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
    Schema::create('quiz_attempts', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->foreignId('module_id')->constrained()->cascadeOnDelete();
        $t->unsignedSmallInteger('score')->default(0); // 0-100
        $t->timestamp('started_at')->nullable();
        $t->timestamp('completed_at')->nullable();
        $t->unsignedInteger('duration_sec')->default(0);
        $t->timestamps();
    });
}
public function down(): void { Schema::dropIfExists('quiz_attempts'); }

};
