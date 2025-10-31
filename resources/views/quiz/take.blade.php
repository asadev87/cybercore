{{-- resources/views/quiz/take.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="space-y-8">
  @php
    $asked = $attempt->questionAttempts()->count();
    $total = max(1, $attempt->target_questions);
    $pct   = intval(($asked / $total) * 100);

    /** @var \App\Models\QuizAttempt $attempt */
    $certificate = \App\Models\Certificate::where('user_id', auth()->id())
        ->where('module_id', $attempt->module_id)
        ->latest('issued_at')
        ->first();
  @endphp

  <div class="space-y-3">
    <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-muted-foreground">
      <span>Question {{ $asked + 1 }} of {{ $total }}</span>
      <div class="flex items-center gap-2">
        @if($certificate)
          <a href="{{ route('certificates.embed', $certificate) }}" class="btn btn-outline text-xs">View certificate</a>
        @else
          <button type="button" class="btn btn-muted text-xs" disabled title="Complete and pass this module to unlock your certificate">
            View certificate
          </button>
        @endif
      </div>
    </div>
    <div class="h-2 rounded-full bg-secondary">
      <div class="h-2 rounded-full bg-primary" style="width: {{ $pct }}%" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="Quiz progress"></div>
    </div>
  </div>

  <div class="mx-auto max-w-6xl space-y-8 px-4 sm:px-6 lg:px-8">
    <article class="card-surface space-y-6 p-8 sm:p-10 lg:p-12">
      @php
        $defaultNotes   = (array) (config('module_notes.defaults') ?? []);
        $moduleNote     = optional($attempt->module)->note;
        $note           = $moduleNote ?: ($defaultNotes[$attempt->module->slug] ?? null);

        $rawNotes = (array) ($question->notes ?? []);
        $questionNotes = [];
        $map = [
          'core_concept' => $rawNotes['core_concept_explanation'] ?? $rawNotes['core_concept'] ?? null,
          'context' => $rawNotes['contextual_narrative'] ?? $rawNotes['context'] ?? null,
        ];
        foreach ($map as $key => $value) {
          $trimmed = is_string($value) ? trim($value) : $value;
          if ($trimmed !== null && $trimmed !== '') {
            $questionNotes[$key] = $trimmed;
          }
        }

        $questionExamples = $rawNotes['real_world_examples'] ?? $rawNotes['examples'] ?? [];
        if (is_string($questionExamples)) {
          $lines = preg_split('/\r\n|\n|\r/', $questionExamples);
          $questionExamples = $lines ?: [];
        }
        $questionExamples = array_values(array_filter(array_map(function ($line) {
          if (is_string($line)) {
            $stripped = preg_replace('/^\-\s*/', '', trim($line));
            return $stripped === '' ? null : $stripped;
          }
          return $line;
        }, is_array($questionExamples) ? $questionExamples : [])));
      @endphp

      @if($note)
        <div class="rounded-2xl border border-amber-300/40 bg-amber-100/30 px-5 py-4 text-sm text-amber-800 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
          <div class="flex flex-col gap-2 sm:flex-row sm:items-start">
            <span class="font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">Module prep note:</span>
            <span class="sm:ml-2 whitespace-pre-line leading-relaxed">{{ $note }}</span>
          </div>
        </div>
      @endif

      @if(!empty($questionNotes) || !empty($questionExamples))
        <div class="rounded-2xl border border-primary/25 bg-primary/5 px-6 py-5 text-sm text-primary-900 dark:border-primary/30 dark:bg-primary/10 dark:text-primary-50">
          <p class="text-xs font-semibold uppercase tracking-[0.32em] text-primary-700 dark:text-primary-200">Question notes</p>
          <div class="mt-4 grid gap-5 lg:grid-cols-3">
            @foreach($questionNotes as $label => $value)
              <div class="space-y-2">
                <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-primary-600 dark:text-primary-200">
                  @switch($label)
                    @case('core_concept')
                      Core concept
                      @break
                    @case('context')
                      Context
                      @break
                    @default
                      {{ \Illuminate\Support\Str::headline($label) }}
                  @endswitch
                </p>
                <p class="text-lg leading-relaxed text-primary-900/90 dark:text-primary-50/90">{{ $value }}</p>
              </div>
            @endforeach

            @if(!empty($questionExamples))
              <div class="space-y-2">
                <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-primary-600 dark:text-primary-200">Examples & tips</p>
                <ul class="list-disc space-y-1 pl-4 text-lg leading-relaxed text-primary-900/90 dark:text-primary-50/90">
                  @foreach($questionExamples as $example)
                    <li>{{ $example }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>

          @auth
            <div class="mt-4 flex flex-wrap items-center gap-3 text-[11px] font-semibold uppercase tracking-[0.28em] text-primary-600 dark:text-primary-200">
              <span>Helpful?</span>
              <form action="{{ route('notes.feedback.store') }}" method="POST" class="inline-flex gap-2">
                @csrf
                <input type="hidden" name="context" value="question">
                <input type="hidden" name="module_id" value="{{ $attempt->module_id }}">
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <input type="hidden" name="source" value="quiz-take">
                <button class="btn btn-outline text-[11px] px-3 py-1" name="helpful" value="1">Yes</button>
                <button class="btn btn-muted text-[11px] px-3 py-1" name="helpful" value="0">Not really</button>
              </form>
            </div>
          @endauth
        </div>
      @endif

      <h1 class="text-2xl font-semibold leading-tight text-foreground sm:text-3xl">{{ $question->stem }}</h1>

      <form method="POST" action="{{ route('quiz.answer', $attempt) }}" aria-labelledby="qlegend" class="mt-6 space-y-6 sm:space-y-7">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <input type="hidden" name="type" value="{{ $question->type }}">

        @if($question->type === 'mcq')
          @php
            $choices = is_array($question->choices) ? $question->choices : [];
          @endphp
          <fieldset class="space-y-4" role="radiogroup" aria-describedby="help-mcq">
            <legend id="qlegend" class="text-base font-semibold text-muted-foreground sm:text-2xl">Select the best option</legend>
            @forelse($choices as $idx => $choice)
              @php
                $id = 'opt'.($idx + 1);
                $letter = chr(65 + $idx);
              @endphp
              <label for="{{ $id }}" class="answer-option cursor-pointer gap-4 px-4 py-4 text-lg leading-relaxed peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-[0_24px_50px_-32px_rgba(37,99,235,0.6)] sm:px-6 sm:py-5 sm:text-2xl">
                <input type="radio" class="peer sr-only" name="answer" id="{{ $id }}" value="{{ $choice }}" required>
                <span class="answer-bullet peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground">{{ $letter }}</span>
                <span class="answer-helper flex-1 text-left text-lg leading-relaxed peer-checked:text-foreground">{{ $choice }}</span>
                <span class="hidden h-8 w-8 items-center justify-center rounded-full border border-primary/30 bg-primary/10 text-primary peer-checked:flex" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                  </svg>
                </span>
              </label>
            @empty
              <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                This question has no choices configured.
              </div>
            @endforelse
            <p id="help-mcq" class="input-hint text-xs sm:text-sm">Use arrow keys or tap to choose.</p>
          </fieldset>
        @endif

        @if($question->type === 'truefalse')
          <fieldset class="space-y-4" role="radiogroup" aria-describedby="help-tf">
            <legend id="qlegend" class="text-base font-semibold text-muted-foreground sm:text-2xl">True or False</legend>
            <div class="grid gap-3 sm:grid-cols-2">
              @foreach(['true' => 'True', 'false' => 'False'] as $value => $label)
                @php
                  $id = 'opt'.ucfirst($value);
                  $initial = strtoupper(substr($label, 0, 1));
                @endphp
                <label for="{{ $id }}" class="answer-option cursor-pointer justify-center gap-6 px-6 py-4 text-center text-2xl peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-[0_24px_50px_-32px_rgba(37,99,235,0.6)] sm:text-2xl">
                  <input type="radio" class="peer sr-only" name="answer" id="{{ $id }}" value="{{ $value }}" required>
                  <span class="answer-bullet peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground">{{ $initial }}</span>
                  <span class="answer-helper flex-1 text-center text-2xl font-semibold peer-checked:text-foreground">{{ $label }}</span>
                </label>
              @endforeach
            </div>
            <p id="help-tf" class="input-hint text-xs sm:text-sm">Select one answer.</p>
          </fieldset>
        @endif

        @if($question->type === 'fib')
          <div class="space-y-3">
            <label for="fibAnswer" class="input-label">Your answer</label>
            <input type="text" class="input-field text-lg" id="fibAnswer" name="answer" autocomplete="off" placeholder="Type your answer…" required>
            <p class="input-hint text-xs sm:text-sm">Spelling matters unless stated otherwise.</p>
          </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-4 text-sm text-muted-foreground sm:text-base">
          <span class="font-medium">Difficulty: {{ ucfirst($question->difficulty) }}</span>
          <button class="btn btn-primary px-6 py-2 text-sm font-semibold sm:text-base">Submit answer</button>
        </div>
      </form>
    </article>
    <p class="text-center text-xs text-muted-foreground">Stay focused — you can review your score after submission.</p>
  </div>
</section>
@endsection
