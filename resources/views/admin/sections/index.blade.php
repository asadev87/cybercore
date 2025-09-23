{{-- resources/views/admin/sections/index.blade.php --}}

@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between mb-3">
  <div>
    <h4 class="mb-0">{{ $module->title }} — Sections</h4>
    <div class="text-muted small">Slug: {{ $module->slug }}</div>
  </div>
  <a href="{{ route('admin.modules.sections.create',$module) }}" class="btn btn-primary btn-sm">New Section</a>
</div>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
<div class="card">
  <div class="card-body p-0">
    <table class="table align-middle mb-0">
      <thead><tr><th>#</th><th>Title</th><th>Status</th><th></th></tr></thead>
      <tbody>
      @foreach($sections as $s)
        <tr>
          <td>{{ $s->order }}</td>
          <td>{{ $s->title }}</td>
          <td>{!! $s->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Hidden</span>' !!}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.modules.sections.edit',[$module,$s]) }}">Edit</a>
            <form class="d-inline" method="POST" action="{{ route('admin.modules.sections.destroy',[$module,$s]) }}">@csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete section?')">Delete</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<div class="mt-3">{{ $sections->links() }}</div>
@endsection
