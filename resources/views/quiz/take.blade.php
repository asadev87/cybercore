{{-- resources/views/quiz/take.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
  @php
    $asked = $attempt->questionAttempts()->count();
    $total = max(1, $attempt->target_questions);
    $pct   = intval(($asked / $total) * 100);
  @endphp

  @php
    /** @var \App\Models\QuizAttempt $attempt */
    $certificate = \App\Models\Certificate::where('user_id', auth()->id())
        ->where('module_id', $attempt->module_id)
        ->latest('issued_at')
        ->first();
@endphp

  <div class="mb-3">
    <div class="d-flex justify-content-between small text-muted">
      <div>Question {{ $asked + 1 }} of {{ $total }}</div>

      @if($certificate)
  <a href="{{ route('certificates.embed', $certificate) }}"
     class="btn btn-outline-primary btn-sm">View certificate</a>
@else
  <button type="button" class="btn btn-outline-secondary btn-sm" disabled
          title="Complete and pass this module to unlock your certificate">
    View certificate
  </button>
@endif

    </div>

    <div class="progress mt-2" style="height:10px">
      <div class="progress-bar" style="width: {{ $pct }}%"></div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-soft">
        <div class="card-body">
          <h5 class="quiz-stem mb-3">{{ $question->stem }}</h5>

          <form method="POST" action="{{ route('quiz.answer', $attempt) }}" aria-labelledby="qlegend">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="type" value="{{ $question->type }}">

            {{-- MCQ (button-style radios) --}}
            @if($question->type === 'mcq')
              @php $choices = is_array($question->choices) ? $question->choices : []; @endphp
              <fieldset role="radiogroup" aria-describedby="help-mcq">
                <legend id="qlegend" class="visually-hidden">Choose one answer</legend>
                <div class="btn-group-vertical w-100" role="group" aria-label="Answer choices">
                  @forelse($choices as $idx => $choice)
                    @php $id = 'opt'.($idx+1); @endphp
                    <input type="radio" class="btn-check" name="answer" id="{{ $id }}" value="{{ $choice }}" required>
                    <label class="btn btn-outline-primary text-start mb-2" for="{{ $id }}">{{ $choice }}</label>
                  @empty
                    <div class="alert alert-warning mb-0">This question has no choices configured.</div>
                  @endforelse
                </div>
                <div id="help-mcq" class="form-text">Select the best option.</div>
              </fieldset>
            @endif

            {{-- True / False --}}
            @if($question->type === 'truefalse')
              <fieldset role="radiogroup" aria-describedby="help-tf">
                <legend id="qlegend" class="visually-hidden">True or False</legend>
                <div class="btn-group w-100" role="group" aria-label="True or False">
                  <input type="radio" class="btn-check" name="answer" id="optTrue" value="true" required>
                  <label class="btn btn-outline-primary" for="optTrue">True</label>

                  <input type="radio" class="btn-check" name="answer" id="optFalse" value="false" required>
                  <label class="btn btn-outline-primary" for="optFalse">False</label>
                </div>
                <div id="help-tf" class="form-text">Choose one.</div>
              </fieldset>
            @endif

            {{-- Fill in the blank --}}
            @if($question->type === 'fib')
              <div class="mb-3">
                <label for="fibAnswer" class="form-label visually-hidden">Your answer</label>
                <input type="text" class="form-control form-control-lg" id="fibAnswer" name="answer" autocomplete="off" placeholder="Type your answer…" required>
                <div class="form-text">Spelling matters unless stated otherwise.</div>
              </div>
            @endif

            <div class="d-flex justify-content-between mt-3">
              <div class="text-muted small">Difficulty: {{ ucfirst($question->difficulty) }}</div>
              <button class="btn btn-accent">Submit answer</button>
            </div>
          </form>
        </div>
      </div>
      <div class="text-center mt-3 small text-muted">Stay focused — you can review your score after submission.</div>
    </div>
  </div>
</div>
@endsection
