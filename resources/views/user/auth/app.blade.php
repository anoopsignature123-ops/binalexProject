<!doctype html>
<html lang="en" class="dark">
<head>
    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Account') - BINALEX</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
</head>
<body class="user-theme auth-page min-h-screen bg-bg text-text">
    <main class="min-h-screen flex items-center justify-center p-4 sm:p-6">
        @yield('content')
    </main>
    @include('admin.layouts.scripts')
</body>
</html>
