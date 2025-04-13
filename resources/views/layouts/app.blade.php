<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '出勤管理')</title>
    {{-- 開発用 --}}
    {{-- @vite(['resources/css/style.css', 'resources/js/app.js']) --}}
    {{-- 本番用 --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-CSIfVw45.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/style-C717Te9p.css') }}">
    <script src="{{ asset('build/assets/app-CIfNNPVJ.js') }}" defer></script>
</head>
<body>
    <header>
        <h1>Y's TEC</h1>
        <a class="hd-menu" href="{{route('dashboard')}}">出勤管理</a>
        <a class="hd-menu" href="{{route('contact.list')}}">共有事項</a>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Y's TEC</p>
    </footer>
</body>
</html>
{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}
