@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label font-weight-semibold text-sm']) }}>
    {{ $value ?? $slot }}
</label>
