<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '出勤管理')</title>

    @if (app()->environment('local'))
        {{-- 開発用 --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        @vite(['resources/js/app.js'])
    @else
        {{-- 本番用 --}}
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('build/assets/app-CXVLft0U.css') }}">
        <link rel="stylesheet" href="{{ asset('build/assets/style-sIrnz_Ww.css') }}">
        <script src="{{ asset('build/assets/app-D-SzzJQe.js') }}" defer></script>
    @endif
</head>
<body>
    <header>
        <h1>Y's TEC</h1>
        <a class="hd-menu" href="{{ route('dashboard') }}">出勤管理</a>
        <a class="hd-menu" href="{{ route('contact.list') }}">共有事項</a>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Y's TEC</p>
    </footer>
</body>
</html>

