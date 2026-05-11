<x-guest-layout>
    <p class="login-box-msg">Verify your email to activate your Factory CRM account.</p>

    <div class="alert alert-info">
        Thanks for signing up. Please confirm your email by using the verification link we just sent you.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <x-primary-button class="btn-block mb-3">
            Resend Verification Email
        </x-primary-button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-link btn-block">Log Out</button>
    </form>
</x-guest-layout>
