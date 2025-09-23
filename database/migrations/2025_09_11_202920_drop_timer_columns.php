<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts','time_limit_sec')) {
                $table->dropColumn('time_limit_sec');
            }
        });
        Schema::table('question_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('question_attempts','time_taken_sec')) {
                $table->dropColumn('time_taken_sec');
            }
        });
    }
    public function down() {
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->integer('time_limit_sec')->nullable();
        });
        Schema::table('question_attempts', function (Blueprint $table) {
            $table->integer('time_taken_sec')->default(0);
        });
    }
};