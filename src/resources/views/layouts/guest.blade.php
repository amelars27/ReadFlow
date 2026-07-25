<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReadFlow') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex align-items-center justify-content-center min-vh-100 px-3">
        <div class="w-100" style="max-width: 420px;">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <i class="bi bi-book-half text-primary fs-1"></i>
                    <h1 class="display-6 fw-bold text-dark mt-2">ReadFlow</h1>
                    <p class="text-muted">Your Personal Reading Tracker</p>
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
