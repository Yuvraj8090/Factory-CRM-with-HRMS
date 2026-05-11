<x-guest-layout>
    <p class="login-box-msg">Sign in to continue to your Factory CRM workspace.</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email" />
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>
        <x-input-error :messages="$errors->get('email')" class="mb-3" />

        <div class="input-group mb-3">
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" />
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mb-3" />

        <div class="row">
            <div class="col-7">
                <div class="icheck-primary">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Remember me</label>
                </div>
            </div>
            <div class="col-5">
                <x-primary-button class="btn-block">
                    Log In
                </x-primary-button>
            </div>
        </div>
    </form>

    @if (Route::has('password.request'))
        <p class="mb-1 mt-3">
            <a href="{{ route('password.request') }}">I forgot my password</a>
        </p>
    @endif

    @if (Route::has('register'))
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
        </p>
    @endif
</x-guest-layout>
