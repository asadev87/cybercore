{{-- resources/views/admin/users/index.blade.php --}}

@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Manage accounts</h1>
      <p class="text-sm text-muted-foreground">Review user access, adjust roles, and verify addresses.</p>
    </div>
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex w-full max-w-xs items-center gap-2">
      <label for="search" class="sr-only">{{ __('Search users') }}</label>
      <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" type="search" placeholder="Search name or email" class="flex-1 rounded-xl border border-border/60 bg-background px-3 py-2 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
      @if(! empty($filters['search']))
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline text-sm">{{ __('Reset') }}</a>
      @endif
    </form>
  </header>

  @if (session('status'))
    <div class="rounded-xl border border-primary/30 bg-primary/10 px-4 py-2 text-sm text-primary">
      {{ session('status') }}
    </div>
  @endif

  @error('action')
    <div class="rounded-xl border border-destructive/20 bg-destructive/10 px-4 py-2 text-sm text-destructive">{{ $message }}</div>
  @enderror

  <div class="overflow-hidden rounded-3xl border border-border/60 bg-background shadow-sm">
    <table class="min-w-full divide-y divide-border/60 text-sm">
      <thead class="bg-secondary/50 text-secondary-foreground">
        <tr>
          <th scope="col" class="px-4 py-3 text-left font-semibold">{{ __('User') }}</th>
          <th scope="col" class="px-4 py-3 text-left font-semibold">{{ __('Role') }}</th>
          <th scope="col" class="px-4 py-3 text-left font-semibold">{{ __('Status') }}</th>
          <th scope="col" class="px-4 py-3 text-right font-semibold">{{ __('Actions') }}</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border/60">
        @forelse ($users as $user)
          <tr class="hover:bg-secondary/40">
            <td class="px-4 py-4">
              <div class="font-medium text-foreground">{{ $user->name ?? __('Unnamed') }}</div>
              <div class="text-sm text-muted-foreground">{{ $user->email }}</div>
            </td>
            <td class="px-4 py-4">
              <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="role">
                <label for="role-{{ $user->id }}" class="sr-only">{{ __('Role') }}</label>
                <select id="role-{{ $user->id }}" name="role" class="w-40 rounded-lg border border-border/60 bg-background px-2 py-1 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                  @foreach ($roles as $role)
                    <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>{{ ucfirst($role->name) }}</option>
                  @endforeach
                </select>
                <button type="submit" class="btn btn-outline text-xs">{{ __('Update') }}</button>
              </form>
              @error('role')
                <p class="mt-1 text-xs text-destructive">{{ $message }}</p>
              @enderror
            </td>
            <td class="px-4 py-4 text-sm text-muted-foreground">
              <div class="flex flex-col gap-1">
                <span>{{ $user->email_verified_at ? __('Verified') : __('Pending verification') }}</span>
                <span class="text-xs">{{ __('Created') }} {{ $user->created_at->format('M j, Y') }}</span>
              </div>
            </td>
            <td class="px-4 py-4 text-right text-sm">
              <div class="inline-flex items-center gap-2">
                @if (! $user->hasVerifiedEmail())
                  <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="verify">
                    <button type="submit" class="btn btn-outline text-xs">{{ __('Mark verified') }}</button>
                  </form>
                @else
                  <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="action" value="unverify">
                    <button type="submit" class="btn btn-muted text-xs">{{ __('Reset verification') }}</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-4 py-10 text-center text-sm text-muted-foreground">{{ __('No users found.') }}</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>
    {{ $users->links() }}
  </div>
</section>
@endsection
