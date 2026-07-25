<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReadFlow') }} — @yield('title', 'Dashboard')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        @include('layouts.navigation')

        @isset($header)
            <div class="bg-white border-bottom px-4 py-3">
                <div class="container">
                    <h4 class="mb-0 fw-semibold text-dark">{{ $header }}</h4>
                </div>
            </div>
        @endisset

        <main class="flex-grow-1 py-4">
            <div class="container">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
