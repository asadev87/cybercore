{{-- resources/views/quiz/instructions.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-3xl space-y-8 py-12">
  <header class="space-y-3 text-center">
    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Please read before starting</p>
    <h1 class="text-3xl font-semibold tracking-tight text-foreground">Module Instructions</h1>
    <p class="text-sm text-muted-foreground">
      You are about to begin the <span class="font-medium text-foreground">{{ $module->title }}</span> knowledge check.
      Review the guidance below so you are prepared before answering any questions.
    </p>
  </header>

  <article class="card-surface space-y-6 p-6 sm:p-8">
    <ol class="list-decimal space-y-4 pl-5 text-sm text-muted-foreground">
      <li>
        You will have <span class="font-medium text-foreground">{{ $module->pass_score }}%</span> as the passing score.
        Answer each question carefully—there is no penalty for reviewing your choice before submission.
      </li>
      <li>
        Some questions may require multiple selections or short written responses. Read the instructions on each
        question before answering.
      </li>
      <li>
        Do not refresh the page or close the browser while the assessment is in progress. Doing so may reset your attempt.
      </li>
      <li>
        Once you complete the quiz, you will see a detailed summary and can download your certificate if you pass.
      </li>
    </ol>

    <div class="rounded-xl border border-amber-300/40 bg-amber-100/40 px-4 py-3 text-sm text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-100">
      <strong class="font-semibold uppercase tracking-wide">Reminder:</strong>
      This quiz supports your cybersecurity readiness. Take a moment to minimize distractions before continuing.
    </div>

    <form action="{{ route('quiz.instructions', $attempt) }}" method="POST" class="flex justify-center pt-2">
      @csrf
      <button class="btn btn-primary px-6">I have read the instructions</button>
    </form>
  </article>

  <div class="text-center text-xs text-muted-foreground">
    Need more time? You can return to the module catalog at any time from the navigation bar.
  </div>
</section>
@endsection
