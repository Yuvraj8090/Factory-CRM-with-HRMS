<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Factory CRM') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=source-sans-3:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="hold-transition login-page">
        <div class="login-box" style="width: min(440px, 92vw);">
            <div class="card card-outline card-primary shadow app-hero-card">
                <div class="card-header text-center border-0 app-hero-panel py-4">
                    <a href="{{ route('home') }}" class="h3 text-white d-inline-block mb-0">
                        <b>Factory</b> CRM
                    </a>
                    <p class="mb-0 mt-2" style="color: rgba(255, 255, 255, 0.78);">AdminLTE access portal for sales, finance, and HR teams.</p>
                </div>
                <div class="card-body login-card-body p-4 p-md-5">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
