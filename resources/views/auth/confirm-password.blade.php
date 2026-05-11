<x-guest-layout>
    <p class="login-box-msg">Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button class="btn-block">
            Confirm
        </x-primary-button>
    </form>
</x-guest-layout>
