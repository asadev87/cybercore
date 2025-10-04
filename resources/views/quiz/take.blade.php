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

  <div class="mx-auto max-w-3xl space-y-4">
    <article class="card-surface p-6">
      <h1 class="text-lg font-semibold leading-snug text-foreground">{{ $question->stem }}</h1>

      <form method="POST" action="{{ route('quiz.answer', $attempt) }}" aria-labelledby="qlegend" class="mt-6 space-y-5">
        @csrf
        <input type="hidden" name="question_id" value="{{ $question->id }}">
        <input type="hidden" name="type" value="{{ $question->type }}">

        @if($question->type === 'mcq')
          @php
            $choices = is_array($question->choices) ? $question->choices : [];
          @endphp
          <fieldset class="space-y-3" role="radiogroup" aria-describedby="help-mcq">
            <legend id="qlegend" class="text-sm font-medium text-muted-foreground">Select the best option</legend>
            @forelse($choices as $idx => $choice)
              @php
                $id = 'opt'.($idx + 1);
                $letter = chr(65 + $idx);
              @endphp
              <label for="{{ $id }}" class="answer-option cursor-pointer peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-[0_24px_50px_-32px_rgba(37,99,235,0.6)]">
                <input type="radio" class="peer sr-only" name="answer" id="{{ $id }}" value="{{ $choice }}" required>
                <span class="answer-bullet peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground">{{ $letter }}</span>
                <span class="answer-helper peer-checked:text-foreground">{{ $choice }}</span>
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
            <p id="help-mcq" class="input-hint">Use arrow keys or tap to choose.</p>
          </fieldset>
        @endif

        @if($question->type === 'truefalse')
          <fieldset class="space-y-3" role="radiogroup" aria-describedby="help-tf">
            <legend id="qlegend" class="text-sm font-medium text-muted-foreground">True or False</legend>
            <div class="grid gap-3 sm:grid-cols-2">
              @foreach(['true' => 'True', 'false' => 'False'] as $value => $label)
                @php
                  $id = 'opt'.ucfirst($value);
                  $initial = strtoupper(substr($label, 0, 1));
                @endphp
                <label for="{{ $id }}" class="answer-option cursor-pointer justify-center gap-5 text-center peer-checked:border-primary peer-checked:bg-primary/10 peer-checked:shadow-[0_24px_50px_-32px_rgba(37,99,235,0.6)]">
                  <input type="radio" class="peer sr-only" name="answer" id="{{ $id }}" value="{{ $value }}" required>
                  <span class="answer-bullet peer-checked:border-primary peer-checked:bg-primary peer-checked:text-primary-foreground">{{ $initial }}</span>
                  <span class="answer-helper flex-1 text-center text-base font-semibold peer-checked:text-foreground">{{ $label }}</span>
                </label>
              @endforeach
            </div>
            <p id="help-tf" class="input-hint">Select one answer.</p>
          </fieldset>
        @endif

        @if($question->type === 'fib')
          <div class="space-y-2">
            <label for="fibAnswer" class="input-label">Your answer</label>
            <input type="text" class="input-field" id="fibAnswer" name="answer" autocomplete="off" placeholder="Type your answer…" required>
            <p class="input-hint">Spelling matters unless stated otherwise.</p>
          </div>
        @endif

        <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-muted-foreground">
          <span>Difficulty: {{ ucfirst($question->difficulty) }}</span>
          <button class="btn btn-primary">Submit answer</button>
        </div>
      </form>
    </article>
    <p class="text-center text-xs text-muted-foreground">Stay focused — you can review your score after submission.</p>
  </div>
</section>
@endsection
