{{-- resources/views/certificates/embed.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0">
        <div class="card-body text-center">

          {{-- Logo --}}
          <div class="mb-4">
            <img src="{{ asset('assets/img/logo.png') }}" alt="CyberCore Logo" style="max-width:150px;">
          </div>

          {{-- Title --}}
          <h2 class="fw-bold mb-3">Certificate of Achievement</h2>
          <p class="mb-4 text-muted">This certifies that</p>

          {{-- Username --}}
          <h3 class="mb-4">{{ $certificate->user->name }}</h3>

          {{-- Module --}}
          <p class="mb-2">
            has successfully completed the module
            <strong>{{ $certificate->module->title }}</strong>
          </p>

          {{-- Score (if available) --}}
          @if($certificate->quiz_attempt && isset($certificate->quiz_attempt->score))
            <p class="mb-4">
              with a score of <strong>{{ $certificate->quiz_attempt->score }}%</strong>
            </p>
          @else
            <p class="mb-4 text-secondary">
              Score: — (not recorded)
            </p>
          @endif
          

          {{-- Date --}}
          <p class="mb-4 text-muted">Issued on: {{ $certificate->created_at->format('F j, Y') }}</p>

          {{-- Signature placeholders --}}
          <div class="d-flex justify-content-center align-items-center mt-5">
            <div class="me-4 text-center">
              <div style="border-top:1px solid #000; width:200px; margin:0 auto;"></div>
              <small class="d-block">Instructor Signature</small>
            </div>
            <div class="text-center">
              <div style="border-top:1px solid #000; width:200px; margin:0 auto;"></div>
              <small class="d-block">CyberCore</small>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
