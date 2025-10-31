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

  <article class="card-surface p-6 sm:p-8" x-data="skeletonLoader({ delay: 260 })">
    <template x-if="loading">
      <div class="space-y-6 animate-pulse">
        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr),minmax(0,0.9fr)] lg:items-start">
          <div class="space-y-3">
            <div class="h-3 w-24 rounded bg-secondary/70"></div>
            <div class="h-8 w-3/4 rounded bg-secondary/60"></div>
            <div class="h-3 w-1/2 rounded bg-secondary/50"></div>
            <div class="flex gap-3">
              <div class="h-6 w-24 rounded-full bg-secondary/60"></div>
              <div class="h-6 w-32 rounded-full bg-secondary/60"></div>
            </div>
          </div>
          <div class="grid gap-4 sm:grid-cols-2">
            <div class="space-y-3 rounded-2xl border border-border/60 bg-secondary/40 px-5 py-6">
              <div class="h-3 w-24 rounded bg-secondary/60"></div>
              <div class="h-10 w-20 rounded bg-secondary/70"></div>
              <div class="h-3 w-11/12 rounded bg-secondary/50"></div>
              <div class="h-3 w-2/3 rounded bg-secondary/40"></div>
              <div class="h-2 w-full rounded-full bg-secondary/50"></div>
            </div>
            <div class="space-y-3 rounded-2xl border border-border/60 bg-secondary/40 px-5 py-6">
              <div class="h-3 w-36 rounded bg-secondary/60"></div>
              <div class="h-4 w-20 rounded bg-secondary/70"></div>
              <div class="h-4 w-24 rounded bg-secondary/50"></div>
              <div class="h-3 w-32 rounded bg-secondary/50"></div>
              <div class="h-2 w-full rounded-full bg-secondary/50"></div>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap gap-3">
          <div class="h-9 w-32 rounded-lg bg-secondary/60"></div>
          <div class="h-9 w-40 rounded-lg bg-secondary/60"></div>
          <div class="h-9 w-36 rounded-lg bg-secondary/60"></div>
        </div>
      </div>
    </template>
    <div class="space-y-8" x-show="!loading" x-cloak>
      <div class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr),minmax(0,0.9fr)] lg:items-start">
        <div class="space-y-3">
          <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Module</p>
          <h1 class="text-3xl font-semibold tracking-tight">{{ $attempt->module->title }}</h1>
          <p class="text-sm text-muted-foreground">
            Completed {{ optional($attempt->completed_at)->diffForHumans() ?? 'just now' }}.
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
        <a href="{{ route('certificates.download', $certificate) }}" class="btn btn-outline text-xs px-3 py-2">Download PDF</a>
      @elseif($passed)
        <button class="btn btn-muted text-xs px-3 py-2" disabled title="Your certificate will appear here shortly.">View certificate</button>
      @else
        <button class="btn btn-muted text-xs px-3 py-2" disabled title="Pass this module to unlock your certificate.">View certificate</button>
      @endif
      @unless($passed)
        <form action="{{ route('quiz.start', $attempt->module) }}" method="POST" class="inline-flex">
          @csrf
          <button class="btn btn-primary text-xs px-3 py-2">Retake module</button>
        </form>
      @endunless
      <a href="{{ route('learn.index') }}" class="btn btn-outline text-xs px-3 py-2">Back to modules</a>
    </div>
    </div>
  </article>

  <article class="card-surface p-6 sm:p-8" x-data="skeletonLoader({ delay: 320 })">
    <template x-if="loading">
      <div class="space-y-6 animate-pulse">
        <div class="flex items-center justify-between border-b border-border/60 pb-4">
          <div class="space-y-2">
            <div class="h-4 w-48 rounded bg-secondary/60"></div>
            <div class="h-3 w-64 rounded bg-secondary/50"></div>
          </div>
          <div class="h-5 w-28 rounded bg-secondary/60"></div>
        </div>
        <div class="space-y-4">
          <div class="rounded-3xl border border-border/60 bg-secondary/30 p-6">
            <div class="h-4 w-32 rounded bg-secondary/50"></div>
            <div class="mt-4 h-4 w-3/4 rounded bg-secondary/40"></div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <div class="h-20 rounded-2xl bg-secondary/40"></div>
              <div class="h-20 rounded-2xl bg-secondary/40"></div>
            </div>
          </div>
          <div class="rounded-3xl border border-border/60 bg-secondary/30 p-6">
            <div class="h-4 w-32 rounded bg-secondary/50"></div>
            <div class="mt-4 h-4 w-3/4 rounded bg-secondary/40"></div>
            <div class="mt-6 grid gap-4 sm:grid-cols-2">
              <div class="h-20 rounded-2xl bg-secondary/40"></div>
              <div class="h-20 rounded-2xl bg-secondary/40"></div>
            </div>
          </div>
        </div>
      </div>
    </template>
    <div class="space-y-6" x-show="!loading" x-cloak>
      <header class="flex flex-wrap items-center justify-between gap-4 border-b border-border/60 pb-4">
        <div>
          <h2 class="text-lg font-semibold">Question-by-question review</h2>
          <p class="text-xs text-muted-foreground">Compare your responses with the expected answers and explanations.</p>
        </div>
        <span class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ $correctCount }} / {{ $totalQuestions }} correct</span>
      </header>

      <div class="space-y-6 mx-auto max-w-4xl">
        @foreach($qas as $i => $qa)
          @php
            $ua = (array) ($qa->user_answer ?? []);
            $ca = (array) ($qa->question->answer ?? []);
            $isCorrect = (bool) $qa->is_correct;
            $rawNotes = (array) ($qa->question->notes ?? []);
            $notes = [];
            $map = [
                'core_concept' => $rawNotes['core_concept_explanation'] ?? $rawNotes['core_concept'] ?? null,
                'context' => $rawNotes['contextual_narrative'] ?? $rawNotes['context'] ?? null,
            ];
            foreach ($map as $key => $value) {
                $trimmed = is_string($value) ? trim($value) : $value;
                if ($trimmed !== null && $trimmed !== '') {
                    $notes[$key] = $trimmed;
                }
            }
            $examplesList = $rawNotes['real_world_examples'] ?? $rawNotes['examples'] ?? [];
            if (is_string($examplesList)) {
                $lines = preg_split('/\r\n|\n|\r/', $examplesList);
                $examplesList = $lines ?: [];
            }
            $examplesList = array_values(array_filter(array_map(function ($line) {
                if (is_string($line)) {
                    $stripped = preg_replace('/^\-\s*/', '', trim($line));
                    return $stripped === '' ? null : $stripped;
                }
                return $line;
            }, is_array($examplesList) ? $examplesList : [])));
          @endphp
          <div class="rounded-3xl border {{ $isCorrect ? 'border-success/20 bg-success/5' : 'border-destructive/20 bg-destructive/5' }} p-6 sm:p-8 space-y-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="flex items-center gap-4 text-sm uppercase tracking-wide sm:text-base">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-secondary text-sm font-bold text-secondary-foreground sm:text-base">{{ $i + 1 }}</span>
                <span class="text-muted-foreground">{{ strtoupper($qa->question->type) }}</span>
              </div>
              <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-semibold uppercase tracking-wide {{ $isCorrect ? 'bg-success/10 text-success' : 'bg-destructive/10 text-destructive' }} sm:text-sm">
                {{ $isCorrect ? 'Correct' : 'Incorrect' }}
              </span>
            </div>

            <h3 class="text-xl font-semibold leading-snug text-foreground sm:text-2xl">{{ $qa->question->stem }}</h3>

            <div class="grid gap-4 sm:grid-cols-2">
              <div class="rounded-2xl border border-border/60 bg-background/60 px-5 py-4 text-base">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Your answer</p>
                <p class="mt-2 leading-relaxed text-foreground">{{ count($ua) ? implode(', ', $ua) : '—' }}</p>
              </div>
              <div class="rounded-2xl border border-border/60 bg-background/60 px-5 py-4 text-base">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Expected answer</p>
                <p class="mt-2 leading-relaxed text-foreground">{{ count($ca) ? implode(', ', $ca) : '—' }}</p>
              </div>
            </div>

            @if($qa->question->explanation)
              <div class="rounded-2xl border border-border/60 bg-secondary/40 px-5 py-4 text-sm text-muted-foreground">
                <span class="font-semibold uppercase tracking-[0.24em] text-muted-foreground">Why it matters:</span>
                <span class="ml-2 leading-relaxed text-muted-foreground">{{ $qa->question->explanation }}</span>
              </div>
            @endif

            @if(!empty($notes) || !empty($examplesList))
              <div class="rounded-2xl border border-primary/25 bg-primary/5 px-5 py-4 text-sm text-primary-900 dark:border-primary/30 dark:bg-primary/10 dark:text-primary-50">
                <p class="font-semibold uppercase tracking-[0.24em] text-primary-700 dark:text-primary-200">Study notes</p>
                <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                  @foreach($notes as $label => $value)
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
                      <p class="leading-relaxed text-primary-900/90 dark:text-primary-50/90">{{ $value }}</p>
                    </div>
                  @endforeach

                  @if(!empty($examplesList))
                    <div class="space-y-2">
                      <p class="text-[11px] font-semibold uppercase tracking-[0.32em] text-primary-600 dark:text-primary-200">Examples & tips</p>
                      <ul class="list-disc space-y-1 pl-4 leading-relaxed text-primary-900/90 dark:text-primary-50/90">
                        @foreach($examplesList as $example)
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
                      <input type="hidden" name="question_id" value="{{ $qa->question_id }}">
                      <input type="hidden" name="source" value="quiz-result">
                      <button class="btn btn-outline text-[11px] px-3 py-1" name="helpful" value="1">Yes</button>
                      <button class="btn btn-muted text-[11px] px-3 py-1" name="helpful" value="0">Not really</button>
                    </form>
                  </div>
                @endauth
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </article>
</section>
@endsection
