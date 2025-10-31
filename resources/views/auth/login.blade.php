<x-guest-layout>
    @php
        $initialType = request('type') === 'lecturer' ? 'lecturer' : 'user';
        $registerRoutes = [
            'user' => route('register', ['type' => 'user']),
            'lecturer' => route('register', ['type' => 'lecturer']),
        ];
    @endphp
    <div
        x-data="{
            accountType: '{{ $initialType }}',
            registerRoutes: {
                user: '{{ $registerRoutes['user'] }}',
                lecturer: '{{ $registerRoutes['lecturer'] }}'
            }
        }"
        class="space-y-6"
    >
        <div class="flex justify-center">
            <div class="inline-flex rounded-full border border-border/70 bg-white/80 p-1 text-sm font-semibold dark:border-white/10 dark:bg-white/10" role="tablist" aria-label="Choose account type">
                <a
                    href="{{ route('login', ['type' => 'user']) }}"
                    role="tab"
                    @click.prevent="accountType = 'user'"
                    :aria-selected="(accountType === 'user').toString()"
                    class="rounded-full px-4 py-2 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :class="accountType === 'user' ? 'bg-primary text-white shadow-sm' : 'text-muted-foreground hover:text-foreground dark:text-white/70 dark:hover:text-white'"
                >
                    Learner
                </a>
                <a
                    href="{{ route('login', ['type' => 'lecturer']) }}"
                    role="tab"
                    @click.prevent="accountType = 'lecturer'"
                    :aria-selected="(accountType === 'lecturer').toString()"
                    class="rounded-full px-4 py-2 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :class="accountType === 'lecturer' ? 'bg-primary text-white shadow-sm' : 'text-muted-foreground hover:text-foreground dark:text-white/70 dark:hover:text-white'"
                >
                    Lecturer
                </a>
            </div>
        </div>

        <header class="space-y-2 text-center">
            <h1
                class="text-2xl font-semibold tracking-tight"
                x-text="accountType === 'lecturer' ? 'Lecturer sign in' : 'Sign in to CyberCore'"
            >
                {{ $initialType === 'lecturer' ? 'Lecturer sign in' : 'Sign in to CyberCore' }}
            </h1>
            <p
                class="text-sm text-muted-foreground"
                x-show="accountType === 'lecturer'"
                x-cloak
            >
                Use the credentials approved by CyberCore. Need access? Switch to register and submit a lecturer request.
            </p>
        </header>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="account_type" x-model="accountType" value="{{ $initialType }}">

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

            <p class="text-center text-xs text-muted-foreground">
                {{ __("Don't have an account yet?") }}
                <a
                    href="{{ $registerRoutes[$initialType] }}"
                    :href="registerRoutes[accountType]"
                    class="font-medium text-primary hover:text-primary/80"
                >
                    {{ __('Register') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
