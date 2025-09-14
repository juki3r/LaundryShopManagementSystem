<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laundry Shop') }}</title>
    <link rel="shortcut icon" href="{{ asset('logo1.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body {
            height: 100%;
            width: 100%;
            margin: 0;
            font-family: 'Figtree', system-ui, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
        }
        .content {
            min-height: 100vh;
            overflow-x: hidden;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid h-100 p-0">
        <div class="row g-0 h-100">
            <!-- Sidebar -->
            <div class="col-12 col-md-2 col-lg-1 bg-dark text-light sidebar">
                @include('layouts.navigation')
            </div>

            <!-- Main Content -->
            <div class="col content">
                <!-- Page Heading -->
                @isset($header)
                <header class="bg-white shadow-sm">
                    <div class="container-fluid py-3 px-3 px-sm-4">
                        {{ $header }}
                    </div>
                </header>
                @endisset

                <!-- Page Content -->
                <main class="p-3 p-sm-4">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>
