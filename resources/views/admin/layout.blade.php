{{-- resources/views/admin/layout.blade.php --}}
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CyberCore Admin — @yield('title','Dashboard')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script>
    (() => {
      const storageKey = 'cybercore-theme';
      const root = document.documentElement;
      try {
        const stored = localStorage.getItem(storageKey);
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const theme = stored ?? (prefersDark ? 'dark' : 'light');
        root.classList.toggle('dark', theme === 'dark');
        root.dataset.theme = theme;
      } catch (error) {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          root.classList.add('dark');
          root.dataset.theme = 'dark';
        }
      }
    })();
  </script>
</head>
<body class="bg-background text-foreground antialiased"
      x-data="(() => { const storageKey = 'cybercore-theme'; const prefersDark = window.matchMedia('(prefers-color-scheme: dark)'); let stored = null; try { stored = localStorage.getItem(storageKey); } catch (error) {} const applyTheme = value => { document.documentElement.classList.toggle('dark', value === 'dark'); document.documentElement.dataset.theme = value; }; const initial = stored ?? (prefersDark.matches ? 'dark' : 'light'); applyTheme(initial); prefersDark.addEventListener?.('change', event => { try { if (!localStorage.getItem(storageKey)) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } } catch (error) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } }); return { theme: initial, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; try { localStorage.setItem(storageKey, this.theme); } catch (error) {} applyTheme(this.theme); } }; })()">
<div class="flex min-h-screen flex-col">
  <header class="border-b border-border/60 bg-background/90 backdrop-blur">
    <div class="container flex items-center justify-between py-4">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
        <span class="relative grid h-10 w-10 place-content-center overflow-hidden rounded-2xl border border-border/70 bg-secondary/70 text-primary shadow-[0_12px_30px_-18px_rgba(37,99,235,0.4)] dark:border-white/10 dark:bg-white/10">
          <span class="h-5 w-5 rounded-xl bg-gradient-to-br from-primary via-accent to-blue-500"></span>
        </span>
        <div class="flex flex-col">
          <span class="text-lg font-semibold tracking-tight">CyberCore Admin</span>
          <span class="text-xs font-medium uppercase tracking-[0.22em] text-muted-foreground">Control Center</span>
        </div>
      </a>
      <div class="flex items-center gap-3">
        <button type="button" @click="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border/70 bg-white/80 text-foreground shadow-sm transition hover:border-border hover:bg-white dark:border-white/10 dark:bg-white/10 dark:text-white" aria-label="Toggle theme">
          <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364-6.364-1.06 1.06M6.697 17.303l-1.06 1.06M18 18l-1.06-1.06M7.757 7.757 6.697 6.697M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
          </svg>
          <svg x-show="theme === 'light'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7.5 7.5 0 009.79 9.79z" />
          </svg>
        </button>
        @if (auth()->user()?->hasRole('lecturer'))
          <a class="btn btn-outline text-sm" href="{{ route('dashboard') }}">Dashboard</a>
        @else
          <a class="btn btn-outline text-sm" href="{{ route('admin.dashboard') }}">Dashboard</a>
        @endif
        @can('view-reports')
          <a class="btn btn-outline text-sm" href="{{ route('admin.reports.index') }}">Reports</a>
        @endcan
        @if (auth()->user()?->hasRole('admin'))
          <a class="btn btn-outline text-sm" href="{{ route('admin.performance.monitor') }}">Performance</a>
        @endif
        <a class="btn btn-outline text-sm" href="{{ url('/') }}">Landing</a>
        @unless (auth()->user()?->hasRole('lecturer'))
          <a class="btn btn-muted text-sm" href="{{ route('dashboard') }}">Learner view</a>
        @endunless
      </div>
    </div>
  </header>

  <main class="flex-1 py-10">
    <div class="container space-y-8">
      @hasSection('page_header')
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          @yield('page_header')
        </div>
      @endif

      <div class="space-y-6">
        @yield('content')
      </div>
    </div>
  </main>
</div>
@stack('scripts')
</body>
</html>
