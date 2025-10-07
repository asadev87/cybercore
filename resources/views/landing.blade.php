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
<div class="flex min-h-screen flex-col">
  <header
    x-data="(() => { const storageKey = 'cybercore-theme'; const prefersDark = window.matchMedia('(prefers-color-scheme: dark)'); let stored = null; try { stored = localStorage.getItem(storageKey); } catch (error) {} const applyTheme = value => { document.documentElement.classList.toggle('dark', value === 'dark'); document.documentElement.dataset.theme = value; }; const initial = stored ?? (prefersDark.matches ? 'dark' : 'light'); applyTheme(initial); prefersDark.addEventListener?.('change', event => { try { if (!localStorage.getItem(storageKey)) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } } catch (error) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } }); return { open: false, scrolled: false, theme: initial, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; try { localStorage.setItem(storageKey, this.theme); } catch (error) {} applyTheme(this.theme); } }; })()"
    x-init="scrolled = window.scrollY > 12; window.addEventListener('scroll', () => scrolled = window.scrollY > 12);"
    :class="{ 'shadow-[0_20px_60px_-35px_rgba(14,165,233,0.45)] bg-background/80 dark:bg-slate-950/90 border-border dark:border-white/10': scrolled }"
    class="sticky top-0 z-50 border-b border-transparent bg-transparent backdrop-blur-xl transition duration-200"
  >
    <div class="container flex items-center justify-between py-5">
      <a href="#" class="flex items-center gap-3 text-sm font-semibold tracking-tight">
        <span class="relative grid h-11 w-11 place-content-center overflow-hidden rounded-2xl border border-border dark:border-white/10 bg-secondary/80 dark:bg-slate-900/80 shadow-[0_15px_40px_-25px_rgba(56,189,248,0.65)]">
          <span class="h-6 w-6 rounded-lg bg-gradient-to-br from-sky-400 via-cyan-300 to-blue-500"></span>
        </span>
        <div class="flex flex-col">
          <span class="text-base font-semibold text-foreground dark:text-white">CyberCore</span>
          <span class="text-xs font-medium uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Stay Vigilant</span>
        </div>
      </a>

      <nav class="hidden items-center gap-8 text-sm font-medium text-muted-foreground dark:text-white/70 lg:flex">
        <a href="#how" class="transition hover:text-foreground dark:text-white">How it works</a>
        <a href="#topics" class="transition hover:text-foreground dark:text-white">Topics</a>
        <a href="#security" class="transition hover:text-foreground dark:text-white">Security</a>
        <a href="#stories" class="transition hover:text-foreground dark:text-white">Stories</a>
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
        <a href="#security" class="transition hover:text-foreground dark:text-white" @click="open = false">Security</a>
        <a href="#stories" class="transition hover:text-foreground dark:text-white" @click="open = false">Stories</a>
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
    <section id="hero" class="relative overflow-hidden">
      <div class="absolute inset-0 -z-10 sera-hero-bg animate-hero-gradient" aria-hidden="true"></div>
      <div class="absolute -left-40 top-1/4 h-72 w-72 rounded-full bg-primary/20 blur-3xl"></div>
      <div class="absolute -right-24 top-10 h-64 w-64 rounded-full bg-accent/25 blur-3xl"></div>
      <div class="absolute bottom-0 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-primary/10 blur-[180px]"></div>

      <div class="container grid gap-16 py-20 lg:grid-cols-[1fr,minmax(0,0.85fr)] lg:py-28">
        <div class="relative z-10 space-y-10">
          <div class="inline-flex items-center gap-3 rounded-large border border-default-200/70 bg-content1/80 px-4 py-1 text-xs font-semibold uppercase tracking-[0.32em] text-default-500 shadow-small backdrop-blur-sm dark:border-default-100/40 dark:text-default-400">
            <span class="h-2 w-2 rounded-full bg-primary shadow-[0_0_0_3px_rgba(37,99,235,0.2)]"></span>
            Empowering Cybersecurity Knowledge
          </div>

          <div class="relative overflow-hidden rounded-large border border-default-200/80 bg-content1/90 p-8 shadow-large backdrop-blur-xl dark:border-default-100/40 dark:bg-content1/70">
            <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-secondary/20"></div>
            <div class="relative flex flex-col gap-8">
              <div class="space-y-5">
                <h1 class="text-4xl font-semibold tracking-tight text-foreground sm:text-5xl lg:text-6xl">
                  Elevate your cyber instincts in weeks, not semesters.
                </h1>
                <p class="max-w-xl text-base text-default-600 dark:text-default-400">
                  Short, visual lessons and hands-on labs help students build real-world cyber habits—fast, focused, and distraction-free.
                </p>
              </div>

              <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-large border border-primary/60 bg-gradient-to-r from-primary/90 via-accent to-primary px-6 py-3 text-sm font-semibold text-white shadow-large transition hover:shadow-glow">
                  Get started free
                  <span aria-hidden="true" class="text-base">→</span>
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-large border border-default-200/80 bg-content2 px-6 py-3 text-sm font-semibold text-default-700 transition hover:border-default-300 hover:bg-content3 dark:border-default-100/40 dark:bg-content1/60 dark:text-default-200">
                  I already have an account
                </a>
              </div>

              <dl class="grid gap-4 sm:grid-cols-3">
                <div class="rounded-large border border-default-200/70 bg-content2/80 p-4 text-default-600 shadow-medium dark:border-default-100/30 dark:bg-content2/40 dark:text-default-300">
                  <dt class="text-xs font-semibold uppercase tracking-[0.32em]">Active learners</dt>
                  <dd class="mt-3 text-2xl font-semibold text-foreground">8,200+</dd>
                </div>
                <div class="rounded-large border border-default-200/70 bg-content2/80 p-4 text-default-600 shadow-medium dark:border-default-100/30 dark:bg-content2/40 dark:text-default-300">
                  <dt class="text-xs font-semibold uppercase tracking-[0.32em]">Modules shipped</dt>
                  <dd class="mt-3 text-2xl font-semibold text-foreground">45 curated</dd>
                </div>
                <div class="rounded-large border border-default-200/70 bg-content2/80 p-4 text-default-600 shadow-medium dark:border-default-100/30 dark:bg-content2/40 dark:text-default-300">
                  <dt class="text-xs font-semibold uppercase tracking-[0.32em]">Avg. completion</dt>
                  <dd class="mt-3 text-2xl font-semibold text-foreground">92%</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <div class="relative isolate flex h-full items-center justify-center">
          <div class="absolute inset-6 rounded-[3rem] bg-gradient-to-br from-primary/18 via-accent/14 to-primary/10 blur-3xl" aria-hidden="true"></div>
          <div class="relative w-full max-w-lg overflow-hidden rounded-[2.5rem] border border-default-200/70 bg-content1/95 p-8 shadow-large backdrop-blur-3xl dark:border-default-100/30 dark:bg-content1/60">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-default-500 dark:text-default-400">Live module</p>
                <p class="mt-3 text-lg font-semibold text-foreground">Threat Detection 101</p>
              </div>
              <span class="inline-flex items-center gap-1 rounded-full border border-success/50 bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">
                <span class="h-2 w-2 rounded-full bg-success"></span>
                In progress
              </span>
            </div>

            <div class="mt-6 space-y-4">
              <div>
                <p class="text-sm font-medium text-default-600 dark:text-default-400">Progress</p>
                <div class="mt-2 h-2 rounded-full bg-content2 dark:bg-content2/40">
                  <div class="h-2 w-3/4 rounded-full bg-gradient-to-r from-primary via-accent to-primary"></div>
                </div>
              </div>

              <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-large border border-default-200/70 bg-content2/90 p-4 text-default-600 shadow-medium dark:border-default-100/30 dark:bg-content2/40 dark:text-default-300">
                  <p class="text-xs font-semibold uppercase tracking-[0.35em]">Next lesson</p>
                  <p class="mt-2 text-sm font-medium text-foreground">Phishing simulation drill</p>
                  <p class="mt-1 text-xs text-default-500 dark:text-default-400">Build safe-inbox instincts with live decoys and instant coaching.</p>
                </div>
                <div class="rounded-large border border-default-200/70 bg-content2/90 p-4 text-default-600 shadow-medium dark:border-default-100/30 dark:bg-content2/40 dark:text-default-300">
                  <p class="text-xs font-semibold uppercase tracking-[0.35em]">Team readiness score</p>
                  <p class="mt-2 text-sm font-medium text-foreground">SOC 2–ready reports</p>
                  <p class="mt-1 text-xs text-default-500 dark:text-default-400">Exportable evidence for audits and leadership updates.</p>
                </div>
              </div>
            </div>

            <div class="mt-6 rounded-large border border-default-200/60 bg-content2/80 p-4 text-xs text-default-500 shadow-medium dark:border-default-100/30 dark:bg-content2/30 dark:text-default-400">
              Works on any device • Keyboard-friendly navigation • Privacy-first analytics
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- How it works --}}
    <section id="how" class="relative overflow-hidden py-20">
      <div class="absolute inset-0 -z-10 bg-[linear-gradient(140deg,_rgba(12,74,110,0.35),_rgba(8,47,73,0.6))]"></div>
      <div class="container">
        <div class="mx-auto max-w-2xl text-center">
          <p class="sera-pill mx-auto">Guided Journey</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">How CyberCore works</h2>
          <p class="mt-3 text-base text-muted-foreground dark:text-white/70">CyberCore connects your human risk goals to everyday practice—onboard fast, immerse every learner, and prove the progress on demand.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-3">
          <article class="sera-card">
            <div class="flex h-full flex-col gap-4">
              <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400/40 to-blue-500/60 text-lg font-bold">1</div>
              <h3 class="text-xl font-semibold">Baseline the risk</h3>
              <p class="text-sm text-muted-foreground dark:text-white/70">Role-based onboarding and quick pulse checks reveal the behaviours that need attention before training even begins.</p>
            </div>
          </article>
          <article class="sera-card">
            <div class="flex h-full flex-col gap-4">
              <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400/40 to-blue-500/60 text-lg font-bold">2</div>
              <h3 class="text-xl font-semibold">Guide every learner</h3>
              <p class="text-sm text-muted-foreground dark:text-white/70">Micro-lessons, live simulations, and automated nudges keep teams engaged without adding admin overhead.</p>
            </div>
          </article>
          <article class="sera-card">
            <div class="flex h-full flex-col gap-4">
              <div class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-400/40 to-blue-500/60 text-lg font-bold">3</div>
              <h3 class="text-xl font-semibold">Measure the impact</h3>
              <p class="text-sm text-muted-foreground dark:text-white/70">Compliance-ready dashboards, executive summaries, and auto-issued certificates show momentum the moment it happens.</p>
            </div>
          </article>
        </div>
      </div>
    </section>

    {{-- Core topics --}}
    <section id="topics" class="relative overflow-hidden py-20">
      <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950"></div>
      <div class="container space-y-10">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <p class="sera-pill w-fit">Curated curriculum</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">Core topics</h2>
            <p class="mt-2 max-w-xl text-sm text-muted-foreground dark:text-white/70">Start with the essentials or let your security office assign learning paths tailored to your role.</p>
          </div>
          <a class="sera-btn" href="{{ route('register') }}">Enroll free</a>
        </div>

        @php($modules = \App\Models\Module::where('is_active', true)->orderBy('title')->limit(4)->get())
        <div class="sera-grid">
          @forelse($modules as $m)
            <article class="sera-card flex h-full flex-col gap-6 p-6">
              <div class="space-y-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-border dark:border-white/10 bg-white/80 dark:bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Module</span>
                <h3 class="text-lg font-semibold">{{ $m->title }}</h3>
                <p class="text-sm text-muted-foreground dark:text-white/70">{{ \Illuminate\Support\Str::limit($m->description, 110) }}</p>
              </div>
              <div class="mt-auto">
                @auth
                  <a href="{{ route('learn.start', $m) }}" class="sera-btn-primary w-full justify-center">Begin</a>
                @else
                  <a href="{{ route('register') }}" class="sera-btn-primary w-full justify-center">Begin</a>
                @endauth
              </div>
            </article>
          @empty
            <div class="sera-card col-span-full flex items-center justify-center p-10 text-sm text-muted-foreground dark:text-white/70">
              Modules will appear here once published by Admin.
            </div>
          @endforelse
        </div>
      </div>
    </section>

    {{-- Security section --}}
    <section id="security" class="relative overflow-hidden py-20">
      <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.25),transparent_65%),radial-gradient(circle_at_bottom,_rgba(14,165,233,0.2),transparent_70%)]"></div>
      <div class="container grid gap-12 lg:grid-cols-[1.1fr_minmax(0,0.95fr)]">
        <div class="space-y-6">
          <p class="sera-pill w-fit">Security first</p>
          <h2 class="text-3xl font-semibold tracking-tight sm:text-4xl">Enterprise-grade protection with human-friendly design</h2>
          <p class="text-base text-muted-foreground dark:text-white/70">CyberCore mirrors the defense-in-depth posture we teach. Infrastructure, data handling, and accessibility all follow strict guardrails.</p>
          <ul class="space-y-4 text-sm text-muted-foreground dark:text-white/70">
            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-sky-300/40 bg-sky-500/10 text-xs font-semibold text-sky-200">01</span> SOC 2-aligned controls, with continuous monitoring and automated alerts.</li>
            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-sky-300/40 bg-sky-500/10 text-xs font-semibold text-sky-200">02</span> WCAG 2.2 AA tested: keyboard-first flows, focus-visible states, and transcripts for every media asset.</li>
            <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-sky-300/40 bg-sky-500/10 text-xs font-semibold text-sky-200">03</span> Privacy by design with zero third-party trackers and region-aware data residency.</li>
          </ul>
        </div>
        <aside class="sera-section space-y-6">
          <h3 class="text-xl font-semibold">Learners first, always.</h3>
          <p class="text-sm text-muted-foreground dark:text-white/70">Personalized nudges, bite-sized briefings, and shareable wins keep momentum high without blowing up calendars.</p>
          <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-border dark:border-white/10 bg-white/80 dark:bg-white/10 p-4">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Pulse checks</p>
              <p class="mt-2 text-sm">Gauge confidence before and after each lesson to chart real behavior change.</p>
            </div>
            <div class="rounded-2xl border border-border dark:border-white/10 bg-white/80 dark:bg-white/10 p-4">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground dark:text-white/60">Leaderboard</p>
              <p class="mt-2 text-sm">Friendly competition keeps teams engaged—without penalizing late starters.</p>
            </div>
          </div>
          <a href="{{ route('register') }}" class="sera-btn-primary w-full justify-center sm:w-auto">Create your free account</a>
        </aside>
      </div>
    </section>

    {{-- Stories / Testimonials --}}
    <section id="stories" class="relative overflow-hidden py-20">
      <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-900/95 to-slate-950"></div>
      <div class="container space-y-12">
        <div class="mx-auto max-w-2xl text-center">
          <p class="sera-pill mx-auto">Trusted by teams</p>
          <h2 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">Stories from the CyberCore community</h2>
          <p class="mt-3 text-base text-muted-foreground dark:text-white/70">Program leads and educators share how they roll out awareness training with style and measurable outcomes.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
          <article class="sera-card space-y-4">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-gradient-to-br from-sky-400 to-blue-500"></div>
              <div>
                <p class="text-sm font-semibold">Amelia Rhodes</p>
                <p class="text-xs text-muted-foreground dark:text-white/60">Director of IT Training, Horizon College</p>
              </div>
            </div>
            <p class="text-sm text-muted-foreground dark:text-white/70">“Within two weeks, completion jumped 60%. The cinematic backgrounds and animated cards from Sera UI made our people forget this was ‘mandatory’ training.”</p>
          </article>
          <article class="sera-card space-y-4">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-400 to-emerald-400"></div>
              <div>
                <p class="text-sm font-semibold">Raj Patel</p>
                <p class="text-xs text-muted-foreground dark:text-white/60">Security Enablement, Northwind Bank</p>
              </div>
            </div>
            <p class="text-sm text-muted-foreground dark:text-white/70">“Adaptive quizzes with progress chips give us real-time insight. The admin dashboards remain simple, yet pair perfectly with our compliance reporting.”</p>
          </article>
          <article class="sera-card space-y-4">
            <div class="flex items-center gap-3">
              <div class="h-10 w-10 rounded-full bg-gradient-to-br from-fuchsia-400 to-sky-500"></div>
              <div>
                <p class="text-sm font-semibold">Noah Bennett</p>
                <p class="text-xs text-muted-foreground dark:text-white/60">STEM Educator, Brightside Academy</p>
              </div>
            </div>
            <p class="text-sm text-muted-foreground dark:text-white/70">“Students finally see cyber literacy as creative. The motion cards, glitch accents, and micro-animations keep them exploring far beyond the lesson plan.”</p>
          </article>
        </div>
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
        <a href="#security" class="transition hover:text-foreground dark:text-white">Security</a>
        <a href="#stories" class="transition hover:text-foreground dark:text-white">Stories</a>
      </nav>
    </div>
  </footer>
</div>
</body>
</html>











