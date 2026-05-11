<x-guest-layout>
    <p class="login-box-msg">Forgot your password? Enter your email and we’ll send a reset link.</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button class="btn-block">
            Email Password Reset Link
        </x-primary-button>
    </form>
</x-guest-layout>
