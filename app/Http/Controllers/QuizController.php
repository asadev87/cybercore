<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuestionAttempt;
use App\Models\UserProgress;
use App\Services\AdaptiveSelector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Certificate;



class QuizController extends Controller
{
    public function start(Request $request, Module $module)
    {
        // idempotent start: resume latest incomplete attempt or create new
        $attempt = QuizAttempt::where('user_id', Auth::id())
            ->where('module_id', $module->id)
            ->whereNull('completed_at')
            ->latest()->first();

        if (!$attempt) {
            $attempt = new QuizAttempt();
            $attempt->user_id = Auth::id();
            $attempt->module_id = $module->id;
            $attempt->target_questions = (int) config('quiz.questions_per_attempt', 8);
            $attempt->started_at = now();
            $attempt->save();
        }

        $this->syncProgressDraft($attempt, 0);

        if (!$attempt->instructions_acknowledged) {
            return view('quiz.instructions', [
                'module'  => $module,
                'attempt' => $attempt,
            ]);
        }

        return redirect()->route('quiz.show', $attempt);
    }

    public function show(QuizAttempt $attempt, AdaptiveSelector $selector)
    {
        
        $this->authorizeAttempt($attempt);

        if (!$attempt->instructions_acknowledged) {
            return view('quiz.instructions', [
                'module'  => $attempt->module,
                'attempt' => $attempt,
            ]);
        }

        // if we already reached target, finish
        $asked = $attempt->questionAttempts()->count();
        if ($asked >= $attempt->target_questions) {
            return $this->finish(request(), $attempt);
        }

        // pick next question
        $question = $selector->nextQuestion($attempt);
        if (!$question) {
            // nothing left to ask -> finish
            return $this->finish(request(), $attempt);
        }
        $certificate = Certificate::where('user_id', Auth::id())
    ->where('module_id', $attempt->module_id)
    ->latest()                 // uses created_at
    ->first();

         return view('quiz.take', compact('attempt','question','asked','certificate'));
    }

    public function answer(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        // Validate payload depending on type
        $question = Question::findOrFail($request->input('question_id'));
        abort_unless($question->module_id === $attempt->module_id, 403);

        $data = $request->validate([
            'question_id' => 'required|integer',
            'type'        => 'required|in:mcq,truefalse,fib',
            'answer'      => 'nullable', // rules by type below
        ]);

        // normalize user answer -> array of strings
        $userAns = [];
        if ($data['type'] === 'mcq') {
            $val = $request->input('answer');
            $userAns = is_array($val) ? array_map('strval', $val) : [(string) $val];
        } elseif ($data['type'] === 'truefalse') {
            $userAns = [ $request->boolean('answer') ? 'true' : 'false' ];
        } else { // fib
            $userAns = [ trim((string) $request->input('answer')) ];
        }

        // compute correctness with normalized comparisons
        $correctArr = array_map(fn($s) => mb_strtolower(trim((string) $s)), (array) ($question->answer ?? []));
        $userNormalized = array_map(fn($s) => mb_strtolower(trim((string) $s)), $userAns);

        if ($question->type === 'mcq') {
            sort($correctArr);
            $sortedUser = $userNormalized;
            sort($sortedUser);
            $isCorrect = $sortedUser === $correctArr;
        } elseif ($question->type === 'truefalse') {
            $expected = $correctArr[0] ?? null;
            $isCorrect = isset($expected) && ($userNormalized[0] ?? null) === $expected;
        } else { // fib
            $isCorrect = in_array($userNormalized[0] ?? '', $correctArr, true);
        }

        // upsert QA (avoid dupes if user refreshes)
        $qa = QuestionAttempt::firstOrCreate(
  ['quiz_attempt_id' => $attempt->id, 'question_id' => $question->id],
  [
    'user_answer' => $userAns,
    'is_correct'  => $isCorrect,
  ]
);

if ($qa->wasRecentlyCreated === false) {
  $qa->update([
    'user_answer' => $userAns,
    'is_correct'  => $isCorrect,
  ]);
}

        // progress hint
        $asked = $attempt->questionAttempts()->count();
        $target = max(1, (int) ($attempt->target_questions ?? config('quiz.questions_per_attempt', 8)));
        $percent = (int) floor(($asked / $target) * 100);
        $this->syncProgressDraft($attempt, $percent);

        if ($asked >= $attempt->target_questions) {
            return $this->finish($request, $attempt);
        }
        return redirect()->route('quiz.show', $attempt);
    }

    public function finish(Request $request, QuizAttempt $attempt = null)
    {
        // allow show() to call finish($attempt) internally
        if ($attempt === null) { $attempt = $request->route('attempt'); }

        $this->authorizeAttempt($attempt);

        if ($attempt->completed_at) {
            return redirect()->route('quiz.result', $attempt);
        }

       // compute score
$total   = max(1, $attempt->questionAttempts()->count());
$correct = $attempt->questionAttempts()->where('is_correct', true)->count();
$score   = (int) round(($correct / $total) * 100);

// Save attempt + progress atomically
DB::transaction(function () use ($attempt, $score) {
    $attempt->score        = $score;
    $attempt->completed_at = now();
    $attempt->save();

    // update progress (simple: completed if pass)
    $pass    = $attempt->module->pass_score ?? 70;
    $status  = $score >= $pass ? 'completed' : 'in_progress';
    $percent = $score >= $pass ? 100 : max($score, 10);

    \App\Models\UserProgress::updateOrCreate(
        ['user_id' => $attempt->user_id, 'module_id' => $attempt->module_id],
        ['status' => $status, 'percent_complete' => $percent, 'last_activity_at' => now()]
    );
});

// Post-commit hooks (safe to run after data is persisted)

// Issue certificate if passed
if ($score >= ($attempt->module->pass_score ?? 70)) {
    app(\App\Services\CertificateService::class)->issueForAttempt($attempt);
}

// Award badges based on this attempt
app(\App\Services\BadgeService::class)->checkAndAward($attempt);

return redirect()->route('quiz.result', $attempt);
  
        return redirect()->route('quiz.result', $attempt);
    }

    public function acknowledgeInstructions(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if (!$attempt->instructions_acknowledged) {
            $attempt->instructions_acknowledged = true;
            $attempt->save();
        }

        return redirect()->route('quiz.show', $attempt);
    }

    public function result(QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

    $qas = $attempt->questionAttempts()->with('question')->get();

    $passScore = $attempt->module->pass_score ?? 70;
    $passed    = (int)($attempt->score ?? 0) >= $passScore;

    $certificate = Certificate::where('user_id', Auth::id())
    ->where('module_id', $attempt->module_id)
    ->latest()
    ->first();

    return view('quiz.result', compact('attempt','qas','passed','certificate'));
    }

    private function authorizeAttempt(QuizAttempt $attempt): void
    {
        abort_unless($attempt->user_id === Auth::id(), 403); // simple ownership check. See policies/gates docs if you prefer. :contentReference[oaicite:7]{index=7}
    }

    private function syncProgressDraft(QuizAttempt $attempt, int $percent): void
    {
        $progress = UserProgress::firstOrNew([
            'user_id'   => $attempt->user_id,
            'module_id' => $attempt->module_id,
        ]);

        if (($progress->status ?? null) === 'completed') {
            return; // never downgrade completed modules
        }

        $progress->status = 'in_progress';
        $current = (int) ($progress->percent_complete ?? 0);
        $next = max(0, min(99, $percent));
        $progress->percent_complete = max($current, $next);
        $progress->last_activity_at = now();
        $progress->save();
    }
}

