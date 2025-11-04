{{-- resources/views/landing.blade.php --}}
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CyberCore — Cybersecurity E-Learning for Everyone</title>
  <meta name="description" content="CyberCore is a secure, user-friendly e-learning platform designed to raise cybersecurity awareness for students, educators, and non-technical audiences.">
  <link rel="icon" href="/assets/img/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background text-foreground antialiased">
<div class="gradient-glow pointer-events-none fixed inset-0 z-0"></div>
<div class="flex min-h-screen flex-col relative z-10">
  <header
    x-data="(() => { const storageKey = 'cybercore-theme'; const prefersDark = window.matchMedia('(prefers-color-scheme: dark)'); let stored = null; try { stored = localStorage.getItem(storageKey); } catch (error) {} const applyTheme = value => { document.documentElement.classList.toggle('dark', value === 'dark'); document.documentElement.dataset.theme = value; }; const initial = stored ?? (prefersDark.matches ? 'dark' : 'light'); applyTheme(initial); prefersDark.addEventListener?.('change', event => { try { if (!localStorage.getItem(storageKey)) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } } catch (error) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } }); return { open: false, scrolled: false, theme: initial, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; try { localStorage.setItem(storageKey, this.theme); } catch (error) {} applyTheme(this.theme); } }; })()"
    x-init="scrolled = window.scrollY > 12; window.addEventListener('scroll', () => scrolled = window.scrollY > 12);"
    :class="{ 'shadow-[0_20px_60px_-35px_rgba(14,165,233,0.45)] bg-background/80 dark:bg-slate-950/90 border-border dark:border-white/10': scrolled }"
    class="sticky top-0 z-50 border-b border-transparent bg-transparent backdrop-blur-xl transition duration-200"
  >
    <div class="container flex items-center justify-between py-5">
      <a href="#" class="flex items-center gap-3 text-sm font-semibold tracking-tight">
        <img src="{{ asset('images/logo.png') }}" alt="CyberCore" class="h-11 w-auto">
        <div class="flex flex-col">
          <span class="text-base font-semibold text-foreground dark:text-white">CyberCore</span>
          <span class="text-xs font-medium uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60"></span>
        </div>
      </a>

      <nav class="hidden items-center gap-8 text-sm font-medium text-muted-foreground dark:text-white/70 lg:flex">
        <a href="#how" class="transition hover:text-foreground dark:text-white">How it works</a>
        <a href="#tokens" class="transition hover:text-foreground dark:text-white">Token access</a>
        <a href="#topics" class="transition hover:text-foreground dark:text-white">Topics</a>
        <a href="#lecturer" class="transition hover:text-foreground dark:text-white">Lecturers</a>
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
        @auth
          @can('view-reports')
            <a href="{{ route('admin.dashboard') }}" class="sera-btn">Admin</a>
          @endcan
          <a href="{{ route('dashboard') }}" class="sera-btn">Dashboard</a>
          <form method="POST" action="{{ route('logout') }}" class="inline-flex">
            @csrf
            <button type="submit" class="sera-btn-ghost">Logout</button>
          </form>
        @endauth

        @guest
          <a href="{{ route('login') }}" class="sera-btn">Sign in</a>
          <a href="{{ route('register') }}" class="sera-btn-primary">Get started</a>
        @endguest
      </div>

      <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-border dark:border-white/10 bg-white/80 dark:bg-white/10 text-foreground dark:text-white shadow-[0_10px_30px_-20px_rgba(56,189,248,0.55)] transition hover:border-white/20 hover:bg-white/90 dark:bg-white/10 lg:hidden" @click="open = !open" aria-expanded="false" aria-controls="mobile-nav">
        <span class="sr-only">Toggle navigation</span>
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h16.5M3.75 12h16.5M3.75 18h16.5" />
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
      class="border-t border-border/60 dark:border-white/5 bg-background/95 dark:bg-slate-950/95 px-4 pb-6 pt-4 shadow-[0_25px_60px_-40px_rgba(14,165,233,0.45)] lg:hidden"
    >
      <nav class="flex flex-col gap-4 text-sm text-muted-foreground dark:text-white/70">
        <a href="#how" class="transition hover:text-foreground dark:text-white" @click="open = false">How it works</a>
        <a href="#topics" class="transition hover:text-foreground dark:text-white" @click="open = false">Topics</a>
        <a href="#lecturer" class="transition hover:text-foreground dark:text-white" @click="open = false">Lecturers</a>
      </nav>
      <div class="mt-6 flex flex-col gap-3">
        <button type="button" @click="toggleTheme()" class="sera-btn w-full justify-center">
          <span x-show="theme === 'dark'" x-cloak>Switch to light mode</span>
          <span x-show="theme === 'light'" x-cloak>Switch to dark mode</span>
        </button>
        @auth
          @can('view-reports')
            <a href="{{ route('admin.dashboard') }}" class="sera-btn" @click="open = false">Admin</a>
          @endcan
          <a href="{{ route('dashboard') }}" class="sera-btn" @click="open = false">Dashboard</a>
          <form method="POST" action="{{ route('logout') }}" class="w-full" @submit="open = false">
            @csrf
            <button type="submit" class="sera-btn-ghost w-full">Logout</button>
          </form>
        @endauth

        @guest
          <a href="{{ route('login') }}" class="sera-btn" @click="open = false">Sign in</a>
          <a href="{{ route('register') }}" class="sera-btn-primary w-full" @click="open = false">Get started</a>
        @endguest
      </div>
    </div>
  </header>

  <main class="flex-1">
    {{-- Hero --}}
    @php
      $activeLearners = \App\Models\User::whereDoesntHave('roles', function ($query) {
        $query->whereIn('name', ['admin', 'lecturer']);
      })->count();
      $activeModules = \App\Models\Module::where('is_active', true)->count();
      $assessmentCount = \App\Models\QuizAttempt::count();
      $tokenCost = (int) config('tokens.module_attempt_cost', 15);
      $signupBonus = (int) config('tokens.signup_bonus_tokens', 0);
      $tokenPacks = collect(config('tokens.packs', []))->map(fn ($size) => "{$size} tokens")->implode(', ');
    @endphp
    <section id="hero" class="py-20">
      <div class="container grid gap-12 lg:grid-cols-2 lg:items-center">
        <div class="space-y-8">
          <p class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-primary">
            Empowering cybersecurity knowledge
          </p>
          <div class="space-y-5">
            <h1 class="text-4xl font-semibold tracking-tight text-foreground sm:text-5xl lg:text-6xl">Build confident cyber habits for every learner.</h1>
            <p class="max-w-xl text-base text-muted-foreground dark:text-white/70">CyberCore turns complex security topics into short, outcomes-focused lessons so your cohort can stay safe without feeling overwhelmed.</p>
          </div>
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('register') }}" class="sera-btn-primary w-full justify-center sm:w-auto">
              Get started free
            </a>
            <a href="#topics" class="sera-btn-subtle w-full justify-center sm:w-auto">
              Browse modules
            </a>
          </div>
          <dl class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-border/70 bg-white/80 p-4 shadow-sm dark:border-white/10 dark:bg-white/10">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Active learners</dt>
              <dd class="mt-2 text-2xl font-semibold text-foreground dark:text-white">{{ number_format($activeLearners) }}</dd>
            </div>
            <div class="rounded-2xl border border-border/70 bg-white/80 p-4 shadow-sm dark:border-white/10 dark:bg-white/10">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Active modules</dt>
              <dd class="mt-2 text-2xl font-semibold text-foreground dark:text-white">{{ number_format($activeModules) }}</dd>
            </div>
            <div class="rounded-2xl border border-border/70 bg-white/80 p-4 shadow-sm dark:border-white/10 dark:bg-white/10">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Assessments taken</dt>
              <dd class="mt-2 text-2xl font-semibold text-foreground dark:text-white">{{ number_format($assessmentCount) }}</dd>
            </div>
          </dl>
        </div>
        <div class="rounded-3xl border border-border/70 bg-white/80 p-8 shadow-sm dark:border-white/10 dark:bg-white/10">
          <h2 class="text-2xl font-semibold text-foreground dark:text-white">A focused learning experience</h2>
          <p class="mt-3 text-sm text-muted-foreground dark:text-white/70">Each pathway blends concise explanations, quick activities, and reflection prompts so learners always know what to do next.</p>
          <ul class="mt-6 space-y-3 text-sm text-muted-foreground dark:text-white/70">
            <li class="flex items-start gap-2">
              <span class="mt-1 h-2 w-2 rounded-full bg-sky-400"></span>
              Short modules designed to fit into busy timetables.
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-1 h-2 w-2 rounded-full bg-emerald-400"></span>
              Practical walkthroughs that translate into real classroom examples.
            </li>
            <li class="flex items-start gap-2">
              <span class="mt-1 h-2 w-2 rounded-full bg-blue-400"></span>
              Instant feedback so learners can celebrate progress right away.
            </li>
          </ul>
          <p class="mt-6 text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60"></p>
        </div>
      </div>
    </section>

    {{-- Token system --}}
    <section id="tokens" class="py-20">
      <div class="container grid gap-12 lg:grid-cols-[minmax(0,1.05fr),minmax(0,0.95fr)] lg:items-center">
        <div class="space-y-6">
          <p class="sera-pill w-fit">Token-first access</p>
          <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">Use tokens to unlock modules</h2>
          <p class="text-base text-muted-foreground dark:text-white/70">
            CyberCore uses tokens instead of subscriptions so learners can dip in whenever they are ready. You start with a generous balance and only spend tokens when you begin or retake a knowledge check.
          </p>
          <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-border/60 bg-background/80 p-5 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
              <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Signup bonus</p>
              <p class="mt-2 text-2xl font-semibold text-foreground dark:text-white">{{ number_format($signupBonus) }} tokens</p>
              <p class="mt-1 text-xs text-muted-foreground dark:text-white/70">Kick off with enough credits to explore multiple modules immediately.</p>
            </div>
            <div class="rounded-2xl border border-border/60 bg-background/80 p-5 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
              <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Attempt cost</p>
              <p class="mt-2 text-2xl font-semibold text-foreground dark:text-white">{{ number_format($tokenCost) }} tokens</p>
              <p class="mt-1 text-xs text-muted-foreground dark:text-white/70">Same price for fresh runs or retakes—no hidden fees along the way.</p>
            </div>
            <div class="rounded-2xl border border-border/60 bg-background/80 p-5 text-center shadow-sm dark:border-white/10 dark:bg-white/5">
              <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Top-up packs</p>
              <ul class="mt-2 list-none space-y-1 text-lg font-semibold text-foreground dark:text-white">
                @foreach (explode(',', $tokenPacks) as $pack)
                  <li>{{ trim($pack) }}</li>
                @endforeach
              </ul>
              <p class="mt-1 text-xs text-muted-foreground dark:text-white/70">Flexible bundles ready for cohorts, bootcamps, or extra practice.</p>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('register') }}" class="sera-btn-primary w-full justify-center sm:w-auto">Claim free tokens</a>
            <a href="{{ route('login') }}" class="sera-btn-subtle w-full justify-center sm:w-auto">Log in to view wallet</a>
          </div>
          <p class="text-xs uppercase tracking-[0.28em] text-muted-foreground dark:text-white/50">
            Tokens are mock credits during beta and help us benchmark demand before enabling real MY payment rails.
          </p>
        </div>
        <div class="rounded-3xl border border-border/70 bg-white/80 p-8 shadow-sm dark:border-white/10 dark:bg-white/10">
          <h3 class="text-xl font-semibold text-foreground dark:text-white">How the wallet works</h3>
          <dl class="mt-6 space-y-4 text-sm text-muted-foreground dark:text-white/70">
            <div class="rounded-2xl border border-border/60 bg-background/70 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Automatic balance</dt>
              <dd class="mt-2">Your wallet balance is always visible in the app header so you know when you are ready for the next attempt.</dd>
            </div>
            <div class="rounded-2xl border border-border/60 bg-background/70 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">One tap spending</dt>
              <dd class="mt-2">Starting a module securely deducts tokens in the same step that we create your attempt—no surprise charges.</dd>
            </div>
            <div class="rounded-2xl border border-border/60 bg-background/70 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
              <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Top up &amp; track</dt>
              <dd class="mt-2">Use the wallet page to add mock tokens, review your history, and monitor upcoming pricing as we roll out Malaysian gateways.</dd>
            </div>
          </dl>
        </div>
      </div>
    </section>

    {{-- How it works --}}
    <section id="how" class="py-20">
      <div class="container space-y-12">
        <div class="mx-auto max-w-2xl text-center">
          <p class="sera-pill mx-auto">Benefits &amp; outcomes</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">What your community gains</h2>
          <p class="mt-3 text-base text-muted-foreground dark:text-white/70">CyberCore keeps focus on the momentum learners, lecturers, and programme leads feel from the very first module.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
          <article class="rounded-3xl border border-border/60 bg-white/80 p-6 shadow-sm dark:border-white/10 dark:bg-white/10">
            <h3 class="text-xl font-semibold text-foreground dark:text-white">Faster awareness gains</h3>
            <p class="mt-2 text-sm text-muted-foreground dark:text-white/70">Micro lessons and recap quizzes deliver quick wins that reinforce safer digital behaviour from day one.</p>
          </article>
          <article class="rounded-3xl border border-border/60 bg-white/80 p-6 shadow-sm dark:border-white/10 dark:bg-white/10">
            <h3 class="text-xl font-semibold text-foreground dark:text-white">Confident classrooms</h3>
            <p class="mt-2 text-sm text-muted-foreground dark:text-white/70">Facilitator notes and ready prompts make it simple for lecturers to guide discussions without extra prep.</p>
          </article>
          <article class="rounded-3xl border border-border/60 bg-white/80 p-6 shadow-sm dark:border-white/10 dark:bg-white/10">
            <h3 class="text-xl font-semibold text-foreground dark:text-white">Clear progress evidence</h3>
            <p class="mt-2 text-sm text-muted-foreground dark:text-white/70">Track knowledge checks, certificates, and learner reflections so stakeholders can see impact at a glance.</p>
          </article>
        </div>
      </div>
    </section>

    {{-- Core topics --}}
    <section id="topics" class="py-20">
      <div class="container space-y-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="sera-pill w-fit">Curated curriculum</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">Core topics</h2>
            <p class="mt-2 max-w-xl text-sm text-muted-foreground dark:text-white/70">Explore active modules focused on the cybersecurity essentials your cohort needs.</p>
          </div>
          <a class="sera-btn" href="{{ route('register') }}">Enroll free</a>
        </div>

        @php
          $modules = \App\Models\Module::where('is_active', true)
            ->orderBy('title')
            ->limit(4)
            ->get();
        @endphp
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          @forelse($modules as $m)
            <article class="rounded-3xl border border-border/70 bg-white/80 p-6 shadow-sm dark:border-white/10 dark:bg-white/10">
              <h3 class="text-lg font-semibold text-foreground dark:text-white">{{ $m->title }}</h3>
              <p class="mt-3 text-sm text-muted-foreground dark:text-white/70">{{ \Illuminate\Support\Str::limit($m->description, 120) }}</p>
              @auth
                <a href="{{ route('learn.start', $m) }}" class="sera-btn-subtle mt-6 inline-flex w-full justify-center sm:w-auto">Open module</a>
              @else
                <a href="{{ route('register') }}" class="sera-btn-subtle mt-6 inline-flex w-full justify-center sm:w-auto">Preview module</a>
              @endauth
            </article>
          @empty
            <div class="rounded-3xl border border-border/70 bg-white/80 p-10 text-center text-sm text-muted-foreground shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white/70">
              Modules will appear here once published by Admin.
            </div>
          @endforelse
        </div>
      </div>
    </section>

    {{-- Lecturer section --}}
    <section id="lecturer" class="py-20">
      <div class="container grid gap-12 lg:grid-cols-2 lg:items-start">
        <div class="space-y-6">
          <p class="sera-pill w-fit">For lecturers</p>
          <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">Partner with CyberCore in your classroom</h2>
          <p class="text-base text-muted-foreground dark:text-white/70">We're welcoming lecturers who want to embed CyberCore modules into their lessons. Register today and we'll activate dedicated lecturer tools for your institution.</p>
        </div>
        <aside class="space-y-6 rounded-3xl border border-border/70 bg-white/80 p-8 shadow-sm dark:border-white/10 dark:bg-white/10">
          <h3 class="text-xl font-semibold text-foreground dark:text-white">Lecturer registration</h3>
          <p class="text-sm text-muted-foreground dark:text-white/70">Complete the short lecturer sign-up form and you'll receive confirmation as soon as your account is approved.</p>
          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <a href="{{ route('register', ['type' => 'lecturer']) }}" class="sera-btn-primary w-full justify-center sm:flex-1">Register as lecturer</a>
            <a href="{{ route('login', ['type' => 'lecturer']) }}" class="sera-btn-subtle w-full justify-center sm:flex-1">Lecturer sign in</a>
          </div>
          <p class="text-xs text-muted-foreground dark:text-white/60">Need help? Reach us at <a href="mailto:support@cybercore.io" class="font-medium text-primary hover:text-primary/80">support@cybercore.io</a>.</p>
        </aside>
      </div>
    </section>

  </main>

  <footer class="border-t border-border/60 dark:border-white/5 bg-background/80 dark:bg-slate-950/80 py-10 backdrop-blur-xl">
    <div class="container flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
      <div class="text-sm text-muted-foreground dark:text-white/60">&copy; <span x-data="{ year: new Date().getFullYear() }" x-text="year"></span> CyberCore. All rights reserved.</div>
      <nav class="flex flex-wrap gap-4 text-sm text-muted-foreground dark:text-white/60">
        <a href="#" class="transition hover:text-foreground dark:text-white">Privacy</a>
        <a href="#" class="transition hover:text-foreground dark:text-white">Terms</a>
        <a href="#how" class="transition hover:text-foreground dark:text-white">How</a>
        <a href="#topics" class="transition hover:text-foreground dark:text-white">Topics</a>
        <a href="#lecturer" class="transition hover:text-foreground dark:text-white">Lecturers</a>
        <a href="#stories" class="transition hover:text-foreground dark:text-white">Stories</a>
      </nav>
</div>
</footer>
</div>
<style>
  @keyframes glowPulse {
    0%,
    100% {
      opacity: 0.4;
      transform: translate(-10%, -10%) scale(0.95);
      filter: blur(140px);
    }
    50% {
      opacity: 0.75;
      transform: translate(10%, 5%) scale(1.05);
      filter: blur(160px);
    }
  }

  @keyframes glowDrift {
    0%,
    100% {
      opacity: 0.35;
      transform: translate(15%, 0%) scale(1);
      filter: blur(120px);
    }
    40% {
      opacity: 0.6;
      transform: translate(-5%, 8%) scale(1.08);
      filter: blur(140px);
    }
    70% {
      opacity: 0.45;
      transform: translate(-20%, -12%) scale(0.92);
      filter: blur(110px);
    }
  }

  .gradient-glow::before,
  .gradient-glow::after {
    content: '';
    position: absolute;
    border-radius: 9999px;
    opacity: 0.55;
    will-change: transform, opacity, filter;
  }

  .gradient-glow::before {
    inset: -20% -35% auto -15%;
    height: 70vw;
    max-height: 620px;
    background: radial-gradient(circle at center,
      rgba(56, 189, 248, 0.35),
      rgba(14, 165, 233, 0.15) 45%,
      transparent 70%);
    animation: glowPulse 22s ease-in-out infinite;
  }

  .gradient-glow::after {
    inset: auto -25% -30% 35%;
    height: 60vw;
    max-height: 540px;
    background: radial-gradient(circle at center,
      rgba(168, 85, 247, 0.3),
      rgba(99, 102, 241, 0.18) 40%,
      transparent 72%);
    animation: glowDrift 28s ease-in-out infinite;
    animation-delay: -8s;
  }

  @media (max-width: 768px) {
    .gradient-glow::before {
      inset: -40% -60% auto -40%;
      height: 120vw;
      filter: blur(120px);
    }
    .gradient-glow::after {
      inset: auto -50% -45% 10%;
      height: 110vw;
      filter: blur(120px);
    }
  }
</style>
</body>
</html>









