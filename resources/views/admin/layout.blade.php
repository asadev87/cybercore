{{-- resources/views/admin/layout.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CyberCore Admin — @yield('title','Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
      <a class="navbar-brand" href="{{ route('admin.dashboard') }}">CyberCore Admin</a>

      <div class="ms-auto d-flex gap-2">
        @can('view-reports')
          <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reports.index') }}">Reports</a>
        @endcan
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('dashboard') }}">Learner View</a>
      </div>
    </div>
  </nav>

  <main class="container py-4">
    @hasSection('page_header')
      <div class="d-flex justify-content-between align-items-center mb-3">
        @yield('page_header')
      </div>
    @endif

    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>