<x-guest-layout>
    <div class="space-y-6">
        <header class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold tracking-tight">Create your CyberCore account</h1>
            <p class="text-sm text-muted-foreground">Start mastering cyber hygiene with guided modules and quizzes.</p>
        </header>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

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

            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" />
                <p class="input-hint">Use 12+ characters with a phrase you remember easily.</p>
            </div>

            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>

            <div class="space-y-4">
                <x-primary-button class="w-full justify-center">
                    {{ __('Register') }}
                </x-primary-button>
                <p class="text-center text-xs text-muted-foreground">
                    {{ __('Already registered?') }}
                    <a href="{{ route('login') }}" class="font-medium text-primary hover:text-primary/80">{{ __('Sign in') }}</a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
