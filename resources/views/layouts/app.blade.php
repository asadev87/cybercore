{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'CyberCore') }}</title>
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
<body class="bg-background text-foreground antialiased">
<div class="flex min-h-screen flex-col">
  @php
    $primaryNav = [
      ['label' => 'Learn', 'route' => 'learn.index'],
      ['label' => 'Performance', 'route' => 'performance.index'],
      ['label' => 'Leaderboard', 'route' => 'leaderboard.index'],
      ['label' => 'Badges', 'route' => 'badges'],
      ['label' => 'Account', 'route' => 'account.index'],
    ];
  @endphp

  <header
    x-data="(() => { const storageKey = 'cybercore-theme'; const prefersDark = window.matchMedia('(prefers-color-scheme: dark)'); let stored = null; try { stored = localStorage.getItem(storageKey); } catch (error) {} const applyTheme = value => { document.documentElement.classList.toggle('dark', value === 'dark'); document.documentElement.dataset.theme = value; }; const initial = stored ?? (prefersDark.matches ? 'dark' : 'light'); applyTheme(initial); prefersDark.addEventListener?.('change', event => { try { if (!localStorage.getItem(storageKey)) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } } catch (error) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } }); return { open: false, scrolled: false, theme: initial, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; try { localStorage.setItem(storageKey, this.theme); } catch (error) {} applyTheme(this.theme); } }; })()"
    x-init="scrolled = window.scrollY > 12; window.addEventListener('scroll', () => scrolled = window.scrollY > 12);"
    :class="{ 'shadow-lg shadow-slate-900/5 backdrop-blur bg-background/90': scrolled }"
    class="sticky top-0 z-40 border-b border-border/60 bg-background/80 backdrop-blur transition duration-200"
  >
    <div class="container flex items-center justify-between py-4">
      <a href="{{ url('/') }}" class="group flex items-center gap-3">
        <span class="grid h-10 w-10 place-content-center rounded-2xl bg-gradient-to-br from-primary/90 via-primary to-accent shadow-glow">
          <span class="h-5 w-5 rounded-xl bg-white/90"></span>
        </span>
        <span class="text-lg font-semibold tracking-tight">CyberCore</span>
      </a>

      <nav class="hidden items-center gap-3 lg:flex">
        @foreach ($primaryNav as $item)
          @php($isRoute = $item['route'] === 'badges' ? request()->is('badges') : request()->routeIs($item['route']))
          <x-nav-link :href="($item['route'] === 'badges') ? url('/badges') : route($item['route'])" :active="$isRoute">
            {{ $item['label'] }}
          </x-nav-link>
        @endforeach
        @role('admin')
          <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">Admin</x-nav-link>
        @endrole
      </nav>

      <div class="hidden items-center gap-3 lg:flex">
        <button type="button" @click="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border/70 bg-white/80 text-foreground shadow-sm transition hover:border-border hover:bg-white dark:border-white/10 dark:bg-white/10 dark:text-white" aria-label="Toggle theme">
          <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364-6.364-1.06 1.06M6.697 17.303l-1.06 1.06M18 18l-1.06-1.06M7.757 7.757 6.697 6.697M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
          </svg>
          <svg x-show="theme === 'light'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7.5 7.5 0 009.79 9.79z" />
          </svg>
        </button>
        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
          @csrf
          <button type="submit" class="btn btn-destructive text-sm">Logout</button>
        </form>
      </div>

      <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-border/70 bg-white text-foreground shadow-sm transition hover:border-border/40 lg:hidden" @click="open = !open" aria-expanded="false" aria-controls="mobile-nav">
        <span class="sr-only">Toggle navigation</span>
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
        </svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div
      id="mobile-nav"
      x-show="open"
      x-cloak
      x-transition:enter="transition ease-out duration-150"
      x-transition:enter-start="opacity-0 -translate-y-2"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-100"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-2"
      class="border-t border-border/60 bg-background/95 px-4 pb-6 pt-4 shadow-xl lg:hidden"
    >
      <div class="flex flex-col gap-3">
        <button type="button" @click="toggleTheme(); open = false;" class="btn btn-outline w-full justify-center text-sm">
          <span x-show="theme === 'dark'" x-cloak>Switch to light mode</span>
          <span x-show="theme === 'light'" x-cloak>Switch to dark mode</span>
        </button>
        <nav class="flex flex-col gap-3 text-base">
          @foreach ($primaryNav as $item)
            @php($isRoute = $item['route'] === 'badges' ? request()->is('badges') : request()->routeIs($item['route']))
            <x-responsive-nav-link :href="($item['route'] === 'badges') ? url('/badges') : route($item['route'])" :active="$isRoute" @click="open = false">
              {{ $item['label'] }}
            </x-responsive-nav-link>
          @endforeach
          @role('admin')
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" @click="open = false">Admin</x-responsive-nav-link>
          @endrole
        </nav>
        <form method="POST" action="{{ route('logout') }}" class="pt-3" @submit="open = false">
          @csrf
          <button type="submit" class="btn btn-destructive w-full">Logout</button>
        </form>
      </div>
    </div>
  </header>

  <main class="flex-1 py-10">
    <div class="container space-y-6">
      @if (session('status') === 'session-expired')
        <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
          You were signed out due to inactivity.
        </div>
      @elseif (session('status'))
        <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
          {{ session('status') }}
        </div>
      @endif

      <div class="min-h-[50vh]">
        @yield('content')
      </div>
    </div>
  </main>
</div>
</body>
</html>
