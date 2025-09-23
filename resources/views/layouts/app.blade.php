{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CyberCore</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-light bg-light border-bottom">
  <div class="container">
    <a class="navbar-brand" href="/">CyberCore</a>

    <div class="ms-auto d-flex align-items-center gap-2">
      @auth
        @if (!request()->routeIs('dashboard'))
          <a class="btn btn-outline-primary btn-sm" href="{{ route('learn.index') }}">Learn</a>
          <a class="btn btn-outline-primary btn-sm" href="{{ route('performance.index') }}">Performance</a>
          <a class="btn btn-outline-primary btn-sm" href="{{ route('leaderboard.index') }}">Leaderboard</a>
          <a class="btn btn-outline-secondary btn-sm" href="{{ url('/badges') }}">Badges</a>
          <a class="btn btn-outline-secondary btn-sm" href="{{ route('account.index') }}">Account</a>
          @role('admin')
            <a class="btn btn-outline-danger btn-sm" href="{{ route('admin.dashboard') }}">Admin</a>
          @endrole
        @endif

        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
      @endauth
    </div>
  </div>
</nav>

  <main class="py-4">
    <div class="container">
      {{-- Nice message (flash) --}}
      @if (session('status') === 'session-expired')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          You were signed out due to inactivity.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @elseif (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>

    {{-- Page content --}}
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
