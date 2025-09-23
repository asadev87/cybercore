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
    Schema::create('modules', function (Blueprint $t) {
        $t->id();
        $t->string('slug')->unique();
        $t->string('title');
        $t->text('description')->nullable();
        $t->unsignedTinyInteger('pass_score')->default(70); // % to “pass”
        $t->boolean('is_active')->default(true);
        $t->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
