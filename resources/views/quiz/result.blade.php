{{-- resources/views/quiz/result.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="space-y-10">
  @php
    $passScore   = $attempt->module->pass_score ?? 70;
    $passed      = (int)($attempt->score ?? 0) >= $passScore;
    $certificate = \App\Models\Certificate::where('user_id', auth()->id())
                  ->where('module_id', $attempt->module_id)
                  ->latest('issued_at')
                  ->first();
    $totalQuestions = max(1, $qas->count());
    $correctCount   = $qas->where('is_correct', true)->count();
    $incorrectCount = $totalQuestions - $correctCount;
  @endphp

  <article class="card-surface p-6 sm:p-8 space-y-8">
    <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr),minmax(0,0.9fr)] lg:items-start">
      <div class="space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Module</p>
        <h1 class="text-3xl font-semibold tracking-tight">{{ $attempt->module->title }}</h1>
        <p class="text-sm text-muted-foreground">
          You completed this attempt in {{ gmdate('i\m s\s', max(1, $attempt->duration_sec ?? 0)) }}.
        </p>
        <div class="flex flex-wrap items-center gap-3">
          <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $passed ? 'border-success/30 bg-success/10 text-success' : 'border-destructive/30 bg-destructive/10 text-destructive' }}">
            {{ $passed ? 'Passed' : 'Try again' }}
          </span>
          <span class="text-sm text-muted-foreground">Passing score: {{ $passScore }}%</span>
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-border/60 bg-secondary/40 px-5 py-6 text-center space-y-2">
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Final score</p>
          <p class="text-4xl font-semibold text-foreground">{{ $attempt->score }}%</p>
          <div class="flex flex-col gap-2 text-xs text-muted-foreground">
            <span>Passing score: {{ $passScore }}%</span>
            <span>{{ $passed ? 'Great job!' : 'Keep practicing — you are close.' }}</span>
          </div>
          <div class="mt-4 h-2 w-full rounded-full bg-secondary">
            <div class="h-2 rounded-full {{ $passed ? 'bg-success' : 'bg-destructive' }}" style="width: {{ $attempt->score }}%"></div>
          </div>
        </div>
        <div class="rounded-2xl border border-border/60 bg-secondary/40 px-5 py-6 text-center space-y-2">
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Question breakdown</p>
          <p class="text-lg font-semibold text-success">{{ $correctCount }} correct</p>
          <p class="text-sm text-destructive">{{ $incorrectCount }} incorrect</p>
          <p class="text-xs text-muted-foreground">{{ $totalQuestions }} questions answered</p>
          <div class="flex justify-center gap-3 pt-1 text-xs font-semibold uppercase">
            <span class="inline-flex items-center gap-1 text-success">
              <span class="h-2 w-2 rounded-full bg-success"></span>Correct
            </span>
            <span class="inline-flex items-center gap-1 text-destructive">
              <span class="h-2 w-2 rounded-full bg-destructive"></span>Incorrect
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
      @if($certificate)
        <a href="{{ route('certificates.embed', $certificate) }}" class="btn btn-primary text-xs px-3 py-2">View certificate</a>
      @elseif($passed)
        <button class="btn btn-muted text-xs px-3 py-2" disabled title="Your certificate will appear here shortly.">View certificate</button>
      @else
        <button class="btn btn-muted text-xs px-3 py-2" disabled title="Pass this module to unlock your certificate.">View certificate</button>
      @endif
      <a href="{{ route('learn.index') }}" class="btn btn-outline text-xs px-3 py-2">Back to modules</a>
    </div>
  </article>

  <article class="card-surface p-6 sm:p-8 space-y-6">
    <header class="flex flex-wrap items-center justify-between gap-4 border-b border-border/60 pb-4">
      <div>
        <h2 class="text-lg font-semibold">Question-by-question review</h2>
        <p class="text-xs text-muted-foreground">Compare your responses with the expected answers and explanations.</p>
      </div>
      <span class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ $correctCount }} / {{ $totalQuestions }} correct</span>
    </header>

    <div class="space-y-4">
      @foreach($qas as $i => $qa)
        @php
          $ua = (array) ($qa->user_answer ?? []);
          $ca = (array) ($qa->question->answer ?? []);
          $isCorrect = (bool) $qa->is_correct;
        @endphp
        <div class="rounded-2xl border {{ $isCorrect ? 'border-success/20 bg-success/5' : 'border-destructive/20 bg-destructive/5' }} p-5 space-y-4">
          <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="flex items-center gap-3 text-sm uppercase tracking-wide">
              <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-secondary text-xs font-bold text-secondary-foreground">{{ $i + 1 }}</span>
              <span class="text-muted-foreground">{{ strtoupper($qa->question->type) }}</span>
            </div>
            <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $isCorrect ? 'bg-success/10 text-success' : 'bg-destructive/10 text-destructive' }}">
              {{ $isCorrect ? 'Correct' : 'Incorrect' }}
            </span>
          </div>

          <h3 class="text-base font-semibold text-foreground">{{ $qa->question->stem }}</h3>

          <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl border border-border/60 bg-background/50 px-4 py-3 text-sm">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Your answer</p>
              <p class="mt-1 text-foreground">{{ count($ua) ? implode(', ', $ua) : '—' }}</p>
            </div>
            <div class="rounded-xl border border-border/60 bg-background/50 px-4 py-3 text-sm">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Expected answer</p>
              <p class="mt-1 text-foreground">{{ count($ca) ? implode(', ', $ca) : '—' }}</p>
            </div>
          </div>

          @if($qa->question->explanation)
            <div class="rounded-xl border border-border/60 bg-secondary/40 px-4 py-3 text-xs text-muted-foreground">
              <span class="font-semibold uppercase tracking-[0.24em] text-muted-foreground">Why it matters:</span>
              <span class="ml-2 text-muted-foreground">{{ $qa->question->explanation }}</span>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  </article>
</section>
@endsection
