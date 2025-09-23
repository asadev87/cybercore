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
    Schema::create('user_progress', function (Blueprint $t) {
        $t->id();
        $t->foreignId('user_id')->constrained()->cascadeOnDelete();
        $t->foreignId('module_id')->constrained()->cascadeOnDelete();
        $t->enum('status', ['not_started','in_progress','completed'])->default('not_started');
        $t->unsignedTinyInteger('percent_complete')->default(0);
        $t->timestamp('last_activity_at')->nullable();
        $t->timestamps();
        $t->unique(['user_id','module_id']);
    });
}
public function down(): void { Schema::dropIfExists('user_progress'); }

};
