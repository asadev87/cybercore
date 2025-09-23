<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
    Schema::create('badges', function (Blueprint $t) {
        $t->id();
        $t->string('slug')->unique();
        $t->string('name');
        $t->string('icon')->nullable();        // e.g. bi bi-shield-check
        $t->string('description')->nullable();
        $t->json('criteria')->nullable();      // optional (for future dynamic rules)
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
