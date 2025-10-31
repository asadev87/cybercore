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
    </head>
    <body class="bg-background text-foreground antialiased">
        <div class="min-h-screen bg-gradient-to-b from-primary/5 via-background to-background/60">
            <div class="container flex min-h-screen flex-col items-center justify-center py-12">
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






