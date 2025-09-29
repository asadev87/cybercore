@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Modules</h4>
  @can('create', App\Models\Module::class)
    <a href="{{ route('admin.modules.create') }}" class="btn btn-primary btn-sm">New Module</a>
  @endcan
</div>

@if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

<div class="card">
  <div class="card-body p-0">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          <th>Title</th>
          <th>Owner</th>
          <th>Pass Score</th>
          <th>Status</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
      @forelse($modules as $m)
        <tr>
          <td>{{ $m->title }}</td>
          <td class="text-muted">{{ $m->user->name ?? 'N/A' }}</td>
          <td>{{ $m->pass_score ?? 70 }}%</td>
          <td>{!! $m->is_active ? '<span class="badge text-bg-success">Active</span>' : '<span class="badge text-bg-secondary">Hidden</span>' !!}</td>
          <td class="text-end">
            @can('update', $m)
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.modules.edit', $m) }}">Edit</a>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.modules.sections.index', $m) }}">Sections</a>
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.modules.questions.index', $m) }}">Questions</a>
            @endcan

            @can('delete', $m)
              <form class="d-inline" method="POST" action="{{ route('admin.modules.destroy', $m) }}">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete module?')">Delete</button>
              </form>
            @endcan
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center text-muted py-4">No modules yet.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $modules->links() }}</div>
@endsection