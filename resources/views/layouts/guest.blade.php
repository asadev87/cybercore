<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CyberCore') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            (() => {
                const storageKey = 'cybercore-theme';
                const root = document.documentElement;

                const applyTheme = (theme) => {
                    root.classList.toggle('dark', theme === 'dark');
                    root.dataset.theme = theme;
                    const darkIcon = document.getElementById('guest-theme-dark');
                    const lightIcon = document.getElementById('guest-theme-light');
                    if (darkIcon && lightIcon) {
                        darkIcon.classList.toggle('hidden', theme !== 'dark');
                        lightIcon.classList.toggle('hidden', theme === 'dark');
                    }
                };

                const resolveTheme = () => {
                    try {
                        const stored = localStorage.getItem(storageKey);
                        if (stored === 'dark' || stored === 'light') {
                            return stored;
                        }
                    } catch (error) {
                        // ignore storage read issues
                    }
                    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                };

                const initialTheme = resolveTheme();
                applyTheme(initialTheme);

                window.addEventListener('DOMContentLoaded', () => applyTheme(root.dataset.theme || initialTheme));

                window.toggleGuestTheme = () => {
                    const current = root.dataset.theme === 'dark' ? 'dark' : 'light';
                    const next = current === 'dark' ? 'light' : 'dark';
                    applyTheme(next);
                    try {
                        localStorage.setItem(storageKey, next);
                    } catch (error) {
                        // ignore storage errors (e.g., private mode)
                    }
                };
            })();
        </script>
    </head>
    <body class="bg-background text-foreground antialiased">
        <div class="relative min-h-screen overflow-hidden bg-gradient-to-b from-primary/5 via-background to-background/60">
            <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden" aria-hidden="true">
                <div class="guest-wave guest-wave--one"></div>
                <div class="guest-wave guest-wave--two"></div>
                <div class="guest-wave guest-wave--three"></div>
            </div>
            <div class="pointer-events-none absolute -top-24 left-1/2 h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-primary/10 blur-3xl animate-[pulse_12s_ease-in-out_infinite] -z-20" aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 left-0 h-72 w-72 -translate-x-1/3 translate-y-1/3 rounded-full bg-sky-300/30 blur-3xl mix-blend-screen animate-[float_16s_linear_infinite] -z-20" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -bottom-10 right-0 h-96 w-96 translate-x-1/4 rounded-full bg-emerald-300/20 blur-3xl mix-blend-screen animate-[floatReverse_18s_linear_infinite] -z-20" aria-hidden="true"></div>

            <div class="container relative flex min-h-screen flex-col items-center justify-center py-12">
                <button
                    type="button"
                    onclick="window.toggleGuestTheme?.()"
                    class="absolute right-6 top-6 inline-flex h-11 w-11 items-center justify-center rounded-full border border-border/70 bg-white/80 text-foreground shadow-sm transition hover:border-border hover:bg-white dark:border-white/10 dark:bg-white/10 dark:text-white"
                    aria-label="Toggle theme"
                >
                    <svg id="guest-theme-dark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5m0 15V21m9-9h-1.5M4.5 12H3m15.364-6.364-1.06 1.06M6.697 17.303l-1.06 1.06M18 18l-1.06-1.06M7.757 7.757 6.697 6.697M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                    <svg id="guest-theme-light" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3a7.5 7.5 0 009.79 9.79z" />
                    </svg>
                </button>
                <a href="/" class="mb-8 inline-flex items-center gap-3 rounded-2xl border border-border/50 bg-card/90 px-4 py-3 shadow-card">
                    <img src="{{ asset('images/logo.png') }}" alt="CyberCore" class="h-10 w-auto">
                </a>

                <div class="w-full max-w-md space-y-6">
                    <div class="card-surface p-6 shadow-card sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>


