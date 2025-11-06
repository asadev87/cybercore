<x-guest-layout>
    @php
        $initialType = request('type') === 'lecturer' ? 'lecturer' : 'user';
        $loginRoutes = [
            'user' => route('login', ['type' => 'user']),
            'lecturer' => route('login', ['type' => 'lecturer']),
        ];
    @endphp
    <div
        x-data="{
            accountType: '{{ old('account_type', $initialType) }}',
            loginRoutes: {
                user: '{{ $loginRoutes['user'] }}',
                lecturer: '{{ $loginRoutes['lecturer'] }}'
            }
        }"
        class="space-y-6"
    >
        <div class="flex justify-center">
            <div class="inline-flex rounded-full border border-border/70 bg-white/80 p-1 text-sm font-semibold dark:border-white/10 dark:bg-white/10" role="tablist" aria-label="Choose account type">
                <a
                    href="{{ route('register', ['type' => 'user']) }}"
                    role="tab"
                    @click.prevent="accountType = 'user'"
                    :aria-selected="(accountType === 'user').toString()"
                    class="rounded-full px-4 py-2 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                    :class="accountType === 'user' ? 'bg-primary text-white shadow-sm' : 'text-muted-foreground hover:text-foreground dark:text-white/70 dark:hover:text-white'"
                >
                    Learner
                </a>
                <a
                    href="{{ route('register', ['type' => 'lecturer']) }}"
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
                x-text="accountType === 'lecturer' ? 'Request lecturer access' : 'Create your CyberCore account'"
            >
                {{ $initialType === 'lecturer' ? 'Request lecturer access' : 'Create your CyberCore account' }}
            </h1>
            <p
                class="text-sm text-muted-foreground"
                x-show="accountType === 'user'"
                x-cloak
            >
                Start mastering cyber hygiene with guided modules and quizzes.
            </p>
            <p
                class="text-sm text-muted-foreground"
                x-show="accountType === 'lecturer'"
                x-cloak
            >
                Submit your lecturer details below and our team will confirm access for your institution.
            </p>
        </header>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="account_type" x-model="accountType" value="{{ old('account_type', $initialType) }}">

            <div class="space-y-2">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" />
            </div>

            <div class="space-y-2" x-data="passwordPulse({ delay: 700 })">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    x-ref="input"
                    x-on:input="reveal()"
                    x-on:blur="conceal(true)"
                    x-on:keydown.enter="conceal(true)"
                />
                <x-input-error :messages="$errors->get('password')" />
                <p class="input-hint">Use 12+ characters with a phrase you remember easily.</p>
            </div>

            <div class="space-y-2" x-data="passwordPulse({ delay: 700 })">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    x-ref="input"
                    x-on:input="reveal()"
                    x-on:blur="conceal(true)"
                    x-on:keydown.enter="conceal(true)"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <div class="space-y-4">
                <x-primary-button class="w-full justify-center">
                    @if ($initialType === 'lecturer')
                        <span x-show="accountType === 'lecturer'">Submit request</span>
                        <span x-show="accountType !== 'lecturer'" x-cloak>{{ __('Register') }}</span>
                    @else
                        <span x-show="accountType !== 'lecturer'">{{ __('Register') }}</span>
                        <span x-show="accountType === 'lecturer'" x-cloak>Submit request</span>
                    @endif
                </x-primary-button>
                <p class="text-center text-xs text-muted-foreground">
                    {{ __('Already registered?') }}
                    <a
                        href="{{ $loginRoutes[$initialType] }}"
                        :href="loginRoutes[accountType]"
                        class="font-medium text-primary hover:text-primary/80"
                    >
                        {{ __('Sign in') }}
                    </a>
                </p>
                <div
                    class="rounded-lg border border-border/70 bg-white/80 p-3 text-left text-xs text-muted-foreground dark:border-white/10 dark:bg-white/10"
                    x-show="accountType === 'lecturer'"
                    x-cloak
                >
                    We review lecturer requests to keep course spaces secure. You can also email <a href="mailto:support@cybercore.io" class="font-medium text-primary hover:text-primary/80">support@cybercore.io</a> for faster coordination.
                </div>
            </div>
        </form>
    </div>
</x-guest-layout>
