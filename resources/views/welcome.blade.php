{{-- resources/views/welcome.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
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
<body class="bg-background text-foreground antialiased" x-data="(() => { const storageKey = 'cybercore-theme'; const prefersDark = window.matchMedia('(prefers-color-scheme: dark)'); let stored = null; try { stored = localStorage.getItem(storageKey); } catch (error) {} const applyTheme = value => { document.documentElement.classList.toggle('dark', value === 'dark'); document.documentElement.dataset.theme = value; }; const initial = stored ?? (prefersDark.matches ? 'dark' : 'light'); applyTheme(initial); prefersDark.addEventListener?.('change', event => { try { if (!localStorage.getItem(storageKey)) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } } catch (error) { const nextTheme = event.matches ? 'dark' : 'light'; applyTheme(nextTheme); } }); return { theme: initial, toggleTheme() { this.theme = this.theme === 'dark' ? 'light' : 'dark'; try { localStorage.setItem(storageKey, this.theme); } catch (error) {} applyTheme(this.theme); } }; })()">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-border/60 bg-background/90 backdrop-blur">
            <div class="container flex items-center justify-between py-4">
                <a href="/" class="flex items-center gap-3">
                    <span class="relative grid h-10 w-10 place-content-center overflow-hidden rounded-2xl border border-border/70 bg-secondary/70 text-primary shadow-[0_12px_30px_-18px_rgba(37,99,235,0.4)] dark:border-white/10 dark:bg-white/10">
                        <span class="h-5 w-5 rounded-xl bg-gradient-to-br from-primary via-accent to-blue-500"></span>
                    </span>
                    <span class="text-lg font-semibold tracking-tight">CyberCore</span>
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
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary text-sm">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-muted text-sm">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary text-sm">Sign up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 -z-10 sera-hero-bg" aria-hidden="true"></div>
                <div class="absolute -left-32 top-1/3 h-64 w-64 rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute -right-20 top-10 h-60 w-60 rounded-full bg-accent/20 blur-3xl"></div>
                <div class="container grid gap-12 py-20 lg:grid-cols-[1fr_minmax(0,0.8fr)] lg:py-28">
                    <div class="space-y-8">
                        <span class="sera-pill w-fit">Cybersecurity for everyone</span>
                        <h1 class="text-balance text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                            CyberCore helps teams practice safe habits in minutes a day.
                        </h1>
                        <p class="max-w-xl text-base text-muted-foreground sm:text-lg">
                            Dynamic micro-lessons, adaptive quizzes, and shareable certificates—all inside a motion-rich experience that mirrors the Sera UI aesthetic.
                        </p>
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('register') }}" class="sera-btn-primary">Get started</a>
                            <a href="{{ route('login') }}" class="sera-btn">Sign in</a>
                        </div>
                    </div>
                    <aside class="card-surface space-y-6 p-8">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">What you get</p>
                            <ul class="mt-4 space-y-3 text-sm text-muted-foreground">
                                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-primary/40 bg-primary/10 text-xs font-semibold text-primary">01</span> Modular learning paths with interactive Sera-style cards and motion.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-primary/40 bg-primary/10 text-xs font-semibold text-primary">02</span> Instant feedback quizzes, CSV imports, and bulk authoring tools.</li>
                                <li class="flex items-start gap-3"><span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-primary/40 bg-primary/10 text-xs font-semibold text-primary">03</span> Admin dashboards with exports, dark/light theming, and role-based controls.</li>
                            </ul>
                        </div>
                        <div class="rounded-2xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground dark:border-white/10 dark:bg-white/10 dark:text-white/80">
                            “It feels like a design system demo and an LMS in one. Learners actually enjoy the animations!” — beta customer
                        </div>
                    </aside>
                </div>
            </section>
        </main>

        <footer class="border-t border-border/60 bg-background/80 py-8">
            <div class="container flex flex-col gap-4 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
                <span>&copy; <span x-data="{ year: new Date().getFullYear() }" x-text="year"></span> CyberCore.</span>
                <nav class="flex flex-wrap gap-4">
                    <a href="{{ route('login') }}" class="transition hover:text-foreground">Sign in</a>
                    <a href="{{ route('register') }}" class="transition hover:text-foreground">Create account</a>
                    <a href="#" class="transition hover:text-foreground">Docs</a>
                </nav>
            </div>
        </footer>
    </div>
</body>
</html>
