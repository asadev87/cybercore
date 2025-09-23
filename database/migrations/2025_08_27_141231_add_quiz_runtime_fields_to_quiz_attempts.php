<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
    Schema::table('quiz_attempts', function (Blueprint $t) {
        $t->unsignedSmallInteger('target_questions')->default(8)->after('score');
        $t->unsignedInteger('time_limit_sec')->nullable()->after('target_questions');
    });
}
public function down(): void {
    Schema::table('quiz_attempts', function (Blueprint $t) {
        $t->dropColumn(['target_questions','time_limit_sec']);
    });
}

};
