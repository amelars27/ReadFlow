<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ReadFlow') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <x-delete-modal />

    <div class="d-flex" style="min-height: 100vh;">
        <x-sidebar />

        <div class="d-flex flex-column flex-grow-1" style="min-width: 0;">
            <x-topnav />

            @hasSection('header')
                <div class="border-bottom px-4 py-3" style="background-color: #EAF9FF;">
                    <h4 class="mb-0 fw-semibold text-dark">@yield('header')</h4>
                </div>
            @endif

            <main class="flex-grow-1 p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        document.querySelectorAll('[data-delete-url]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('deleteForm').action = this.dataset.deleteUrl;
            });
        });
    </script>
</body>
</html>
