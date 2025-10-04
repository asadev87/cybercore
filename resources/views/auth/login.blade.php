<x-guest-layout>
    <div class="space-y-6">
        <header class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold tracking-tight">Sign in to CyberCore</h1>
            <p class="text-sm text-muted-foreground">Rejoin your modules and keep your cybersecurity skills sharp.</p>
        </header>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <x-input-label for="password" :value="__('Password')" />
                    @if (Route::has('password.request'))
                        <a class="text-xs font-medium text-primary hover:text-primary/80" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <label for="remember_me" class="flex items-center gap-2 text-sm text-muted-foreground">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded-md border-border/80 text-primary focus:ring-ring" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            <x-primary-button class="w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
