{{-- resources/views/quiz/result.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <h4 class="mb-0">Your score: {{ $attempt->score }}%</h4>
        @if($passed)
          <span class="badge text-bg-success">Passed</span>
        @else
          <span class="badge text-bg-secondary">Try Again</span>
        @endif

        <div class="ms-auto d-flex gap-2">
          @if($certificate)
            <a href="{{ route('certificates.embed', $certificate) }}"
               class="btn btn-accent btn-sm">
              View certificate
            </a>
          @elseif($passed)
            <button class="btn btn-outline-secondary btn-sm" disabled
                    title="Your certificate will appear here shortly.">
              View certificate
            </button>
          @else
            <button class="btn btn-outline-secondary btn-sm" disabled
                    title="Pass this module to unlock your certificate.">
              View certificate
            </button>
          @endif

          <a href="{{ route('learn.index') }}" class="btn btn-primary btn-sm">
            Back to modules
          </a>
        </div>
      </div>

      <div class="text-muted mt-2">
        Module: {{ $attempt->module->title }} · Duration: {{ $attempt->duration_sec }}s
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">Your answers & feedback</div>
    <div class="card-body p-0">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Question</th>
            <th>Your answer</th>
            <th>Correct</th>
            <th>Feedback</th>
          </tr>
        </thead>
        <tbody>
          @foreach($qas as $i => $qa)
            <tr>
              <td>{{ $i+1 }}</td>
              <td class="w-50">{{ $qa->question->stem }}</td>
              <td>
                @php $ua = (array) ($qa->user_answer ?? []); @endphp
                {{ implode(', ', $ua) }}
              </td>
              <td>
                @php $ca = (array) ($qa->question->answer ?? []); @endphp
                <span class="badge {{ $qa->is_correct ? 'text-bg-success' : 'text-bg-danger' }}">
                  {{ $qa->is_correct ? 'Correct' : implode(', ', $ca) }}
                </span>
              </td>
              <td class="text-muted small">{{ $qa->question->explanation }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection
