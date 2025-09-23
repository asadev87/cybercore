{{-- resources/views/certificates/view.blade.php --}}

@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h4 class="mb-3">Certificate</h4>
  <div class="ratio ratio-16x9 border rounded-3 shadow-sm">
    <iframe
      src="{{ route('certificates.view',$certificate) }}#toolbar=0&navpanes=0&scrollbar=0"
      style="border:0"
      allow="clipboard-read; clipboard-write">
    </iframe>
  </div>
  <div class="text-muted small mt-2">
    View-only certificate. Download/print are disabled.
  </div>
</div>
@endsection

@push('scripts')
<script>
// Basic deterrents: hide context menu & intercept Ctrl/Cmd+P
document.addEventListener('contextmenu', e => e.preventDefault());
document.addEventListener('keydown', e => {
  if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p') { e.preventDefault(); }
});
</script>
@endpush
