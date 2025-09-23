{{-- resources/views/admin/questions/index.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-0">{{ $module->title }} — Questions</h4>
    <div class="text-muted small">Slug: {{ $module->slug }}</div>
  </div>

  <div class="d-flex gap-2 align-items-center">
    {{-- Download CSV template --}}
    <a href="{{ route('admin.modules.questions.template', $module) }}"
       class="btn btn-outline-secondary btn-sm">
      Download CSV template
    </a>

    {{-- CSV/XLSX import --}}
    <form method="POST"
          action="{{ route('admin.modules.questions.import', $module) }}"
          enctype="multipart/form-data"
          class="d-flex gap-2">
      @csrf
      <input type="file"
             name="questions_file"
             class="form-control form-control-sm"
             accept=".csv,.xlsx,.xls"
             required>
      <button class="btn btn-outline-primary btn-sm">Import</button>
    </form>

    {{-- Manual create --}}
    <a href="{{ route('admin.modules.questions.create', $module) }}"
       class="btn btn-primary btn-sm">
      New Question
    </a>
  </div>
</div>

{{-- Validation + flash messages --}}
@if ($errors->any())
  <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@if (session('ok'))
  <div class="alert alert-success">{{ session('ok') }}</div>
@endif

@if (session('import_ok') === true)
  <div class="alert alert-success">Import complete.</div>
@endif

@if (session('import_failures'))
  <div class="alert alert-warning">
    <div class="fw-bold mb-1">Some rows failed validation:</div>
    <ul class="mb-0">
      @foreach (session('import_failures') as $failure)
        <li>Row {{ $failure->row() }}: {{ implode('; ', $failure->errors()) }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card">
  <div class="card-body p-0">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th style="width: 100px;">Type</th>
          <th style="width: 70px;">Diff</th>
          <th>Question</th>
          <th style="width: 110px;">Status</th>
          <th class="text-end" style="width: 180px;"></th>
        </tr>
      </thead>
      <tbody>
        @forelse ($questions as $q)
          <tr>
            <td class="text-uppercase">{{ $q->type }}</td>
            <td>{{ $q->difficulty }}</td>
            <td class="text-truncate" style="max-width: 520px">{{ $q->stem }}</td>
            <td>
              @if ($q->is_active)
                <span class="badge text-bg-success">Active</span>
              @else
                <span class="badge text-bg-secondary">Hidden</span>
              @endif
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-secondary"
                 href="{{ route('admin.modules.questions.edit', [$module, $q]) }}">
                Edit
              </a>

              <form class="d-inline" method="POST"
                    action="{{ route('admin.modules.questions.destroy', [$module, $q]) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Delete question?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No questions yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">
  {{ $questions->links() }}
</div>
@endsection
