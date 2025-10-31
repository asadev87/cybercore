{{-- resources/views/certificates/view.blade.php --}}

@extends('layouts.app')

@section('content')
<section class="bg-white py-12 text-slate-900 dark:bg-white dark:text-slate-900">
  <div class="container space-y-6">
    <header class="flex flex-wrap items-center justify-between gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-600">Certificate</p>
        <h1 class="text-2xl font-semibold tracking-tight">{{ $certificate->module->title }}</h1>
      </div>
      <div class="flex items-center gap-3 text-sm text-slate-600">
        <span>Issued {{ $certificate->created_at->format('F j, Y') }}</span>
        <span class="hidden h-4 w-[1px] bg-border sm:block"></span>
        <span>{{ $certificate->user->name }}</span>
      </div>
    </header>

    <div class="rounded-3xl border border-blue-100 bg-white shadow-lg overflow-hidden">
      <div class="aspect-[3/4] w-full">
        <iframe
          src="{{ route('certificates.embed', $certificate) }}#toolbar=0&navpanes=0&scrollbar=0"
          title="Certificate preview"
          class="h-full w-full border-0"
          allow="clipboard-read; clipboard-write"
        ></iframe>
      </div>
    </div>

    <p class="text-sm text-slate-600">Certificate is view-only. Printing and downloading are disabled to help prevent spoofing.</p>
  </div>
</section>
@endsection

@push('scripts')
<script>
  document.addEventListener('contextmenu', event => event.preventDefault());
  document.addEventListener('keydown', event => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'p') {
      event.preventDefault();
    }
  });
</script>
@endpush



