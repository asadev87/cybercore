<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('question_attempts', function (Blueprint $table) {
            if (! Schema::hasColumn('question_attempts', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('quiz_attempt_id')
                    ->constrained()
                    ->cascadeOnDelete();
            }
        });

        $attempts = DB::table('question_attempts')
            ->select('question_attempts.id', 'quiz_attempts.user_id')
            ->join('quiz_attempts', 'quiz_attempts.id', '=', 'question_attempts.quiz_attempt_id')
            ->whereNull('question_attempts.user_id')
            ->get();

        foreach ($attempts as $attempt) {
            if (! is_null($attempt->user_id)) {
                $userExists = DB::table('users')->where('id', $attempt->user_id)->exists();

                if ($userExists) {
                    DB::table('question_attempts')
                        ->where('id', $attempt->id)
                        ->update(['user_id' => $attempt->user_id]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('question_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('question_attempts', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

