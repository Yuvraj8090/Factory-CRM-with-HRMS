<x-guest-layout>
    <div class="text-center mb-4">
        <h4 class="fw-bold text-dark mb-1">Welcome Back</h4>
        <p class="text-muted small">Sign in to continue to your Factory CRM workspace.</p>
    </div>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Input -->
        <div class="mb-3">
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                    <i class="fas fa-envelope"></i>
                </span>
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email Address" class="form-control border-start-0 focus-ring-0" style="box-shadow: none;" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 small text-danger" />
        </div>

        <!-- Password Input -->
        <div class="mb-4">
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-white text-muted border-end-0">
                    <i class="fas fa-lock"></i>
                </span>
                <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password" class="form-control border-start-0 focus-ring-0" style="box-shadow: none;" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 small text-danger" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input shadow-sm cursor-pointer" name="remember">
                <label class="form-check-label text-muted small cursor-pointer" for="remember_me">
                    Remember me
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary fw-medium">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mb-3">
            <x-primary-button class="btn btn-primary w-100 py-2 shadow-sm fw-medium">
                Log In
            </x-primary-button>
        </div>
    </form>

    <!-- Register Link -->
    @if (Route::has('register'))
        <p class="text-center mb-0 mt-4 small text-muted">
            Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Register here</a>
        </p>
    @endif
</x-guest-layout>