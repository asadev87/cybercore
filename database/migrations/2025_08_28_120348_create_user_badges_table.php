<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
    Schema::create('user_badges', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->foreignId('badge_id')->constrained()->cascadeOnDelete();
        $t->timestamp('awarded_at');
        $t->timestamps();

        $t->unique(['user_id','badge_id']); // prevent duplicates
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};
