{{-- resources/views/quiz/instructions.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $descriptionCopy = (array) (config('module_notes.descriptions') ?? []);
  $defaultNotes    = (array) (config('module_notes.defaults') ?? []);
  $noteText = $module->note ?: ($defaultNotes[$module->slug] ?? null);

  $noteSummary = null;
  $noteStory   = null;
  $noteCore    = null;
  $noteOutcomes = [];

  if ($noteText) {
      $lines = preg_split("/\r\n|\r|\n/", trim($noteText));
      $current = null;
      foreach ($lines as $line) {
          $trimmed = trim($line);
          if ($trimmed === '') {
              continue;
          }

          if (stripos($trimmed, 'TL;DR:') === 0) {
              $noteSummary = trim(substr($trimmed, 6));
              $current = null;
              continue;
          }

          if (stripos($trimmed, 'Core Concept Explanation:') === 0) {
              $noteCore = '';
              $current = 'core';
              continue;
          }

          if (stripos($trimmed, 'Contextual Narrative:') === 0) {
              $noteStory = '';
              $current = 'story';
              continue;
          }

          if (stripos($trimmed, 'Real-World Checklist:') === 0) {
              $noteOutcomes = [];
              $current = 'outcomes';
              continue;
          }

          if ($current === 'core') {
              $noteCore = trim(($noteCore ? $noteCore . ' ' : '') . $trimmed);
          } elseif ($current === 'story') {
              $noteStory = trim(($noteStory ? $noteStory . ' ' : '') . $trimmed);
          } elseif ($current === 'outcomes' && str_starts_with($trimmed, '-')) {
              $noteOutcomes[] = trim(ltrim($trimmed, '- '));
          }
      }
  }

  $moduleSummary = $noteSummary ?: ($descriptionCopy[$module->slug] ?? $module->description);
  $noteStory = $noteStory ?: ($noteCore ?: 'Imagine how these concepts apply in your daily workflow—identify a recent situation you can revisit with this guidance in mind.');
  if (empty($noteOutcomes)) {
      $noteOutcomes = [
          'Demonstrate practical knowledge of the core security behaviors covered in this module.',
          'Spot and respond to real-world signals faster in your day-to-day role.',
          'Unlock your completion certificate once you meet or exceed the passing score.',
      ];
  }
@endphp

<section class="mx-auto max-w-5xl space-y-8 py-12">
  <header class="space-y-3 text-center">
    <p class="text-sm font-semibold uppercase tracking-[0.32em] text-muted-foreground">Please read before starting</p>
    <h1 class="text-4xl font-semibold tracking-tight text-foreground">Before You Begin</h1>
    <p class="text-base text-muted-foreground">
      Preview what you will cover in <span class="font-medium text-foreground">{{ $module->title }}</span> and confirm you are ready to begin the knowledge check.
    </p>
  </header>

  <article class="card-surface space-y-6 p-8 sm:p-10">
    <div class="space-y-6">
      <section class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-muted-foreground">Module summary</p>
        <p class="text-lg leading-relaxed text-foreground">{{ $moduleSummary }}</p>
      </section>

      <section class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-muted-foreground">Example</p>
        <p class="text-base leading-relaxed text-muted-foreground">{{ $noteStory }}</p>
      </section>

      <section class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-muted-foreground">When you complete this module</p>
        <ul class="list-disc space-y-2 pl-5 text-base leading-relaxed text-muted-foreground">
          @foreach($noteOutcomes as $outcome)
            <li>{{ $outcome }}</li>
          @endforeach
        </ul>
      </section>
    </div>
  </article>

  @php
    $guidelines = [
        "The passing score is {$module->pass_score}%—take your time and review each answer before submitting.",
        'Questions may include multiple choice, true/false, or short responses. Read the prompt carefully and follow the instructions shown on each screen.',
        'Avoid refreshing your browser or navigating away. Doing so can interrupt your attempt and may require you to restart.',
        'After finishing, you will see a detailed result summary and can download your completion certificate if you pass.',
    ];
  @endphp

  <article class="card-surface space-y-6 p-8 sm:p-10">
    <h2 class="text-2xl font-semibold tracking-tight text-foreground">Knowledge Check Guidelines</h2>
    <ul class="space-y-4">
      @foreach($guidelines as $index => $guideline)
        <li class="flex gap-5">
          <span class="flex h-9 w-9 flex-none items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">{{ $index + 1 }}</span>
          <p class="flex-1 text-base leading-relaxed text-muted-foreground">{{ $guideline }}</p>
        </li>
      @endforeach
    </ul>

    <div class="rounded-xl border border-amber-300/40 bg-amber-100/40 px-6 py-4 text-base text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-100">
      <strong class="font-semibold uppercase tracking-wide">Quick reminder:</strong>
      Take a breath, close distracting tabs, and focus on demonstrating what you have learned.
    </div>

    <form action="{{ route('quiz.instructions', $attempt) }}" method="POST" class="flex justify-center pt-4">
      @csrf
      <button class="btn btn-primary px-8 py-3 text-base">I’m ready to start</button>
    </form>
  </article>

  <div class="text-center text-sm text-muted-foreground">
    Need more time? You can return to the module catalog at any time from the navigation menu.
  </div>
</section>
@endsection
