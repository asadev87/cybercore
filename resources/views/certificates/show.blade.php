{{--resources/views/certificates/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-3">Certificate — {{ $attempt->module->title }}</h4>
  <div class="ratio ratio-16x9 border rounded-3 shadow-sm">
    <iframe
      src="{{ route('certificates.stream', $certificate) }}"
      title="Certificate"
      style="border:0"
      allow="clipboard-read; clipboard-write">
    </iframe>
  </div>
  <p class="text-muted mt-2 small mb-0">
    This certificate is view-only inside CyberCore.
  </p>
</div>
@endsection
