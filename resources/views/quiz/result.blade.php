{{-- resources/views/quiz/result.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="space-y-8">
  @php
    $passScore   = $attempt->module->pass_score ?? 70;
    $passed      = (int)($attempt->score ?? 0) >= $passScore;
    $certificate = \App\Models\Certificate::where('user_id', auth()->id())
                  ->where('module_id', $attempt->module_id)
                  ->latest('issued_at')
                  ->first();
  @endphp

  <article class="card-surface space-y-4 p-6">
    <div class="flex flex-wrap items-center gap-3">
      <h1 class="text-2xl font-semibold">Your score: {{ $attempt->score }}%</h1>
      <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $passed ? 'bg-success/10 text-success' : 'bg-secondary text-secondary-foreground' }}">
        {{ $passed ? 'Passed' : 'Try again' }}
      </span>
      <div class="ms-auto flex flex-wrap items-center gap-3 text-sm">
        @if($certificate)
          <a href="{{ route('certificates.embed', $certificate) }}" class="btn btn-primary text-xs px-3 py-2">View certificate</a>
        @elseif($passed)
          <button class="btn btn-muted text-xs px-3 py-2" disabled title="Your certificate will appear here shortly.">View certificate</button>
        @else
          <button class="btn btn-muted text-xs px-3 py-2" disabled title="Pass this module to unlock your certificate.">View certificate</button>
        @endif
        <a href="{{ route('learn.index') }}" class="btn btn-outline text-xs px-3 py-2">Back to modules</a>
      </div>
    </div>
    <p class="text-sm text-muted-foreground">Module: {{ $attempt->module->title }} · Duration: {{ $attempt->duration_sec }}s</p>
  </article>

  <article class="card-surface overflow-hidden">
    <header class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Your answers & feedback</h2>
    </header>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-6 py-3 text-left font-semibold">#</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Question</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Your answer</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Correct</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Feedback</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @foreach($qas as $i => $qa)
            @php
              $ua = (array) ($qa->user_answer ?? []);
              $ca = (array) ($qa->question->answer ?? []);
            @endphp
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm font-semibold text-foreground">{{ $i + 1 }}</td>
              <td class="px-6 py-4 text-sm text-foreground">{{ $qa->question->stem }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ implode(', ', $ua) }}</td>
              <td class="px-6 py-4">
                <span class="rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $qa->is_correct ? 'bg-success/10 text-success' : 'bg-destructive/10 text-destructive' }}">
                  {{ $qa->is_correct ? 'Correct' : implode(', ', $ca) }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $qa->question->explanation }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </article>
</section>
@endsection

