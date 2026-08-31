<!doctype html>
<html lang="en" class="dark">

<head>
    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.remove('dark');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'admin Panel') - BINALEX</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/panel.css') }}" rel="stylesheet">
</head>

<body id="dashboard" class="admin-theme">
    <div id="loader">
        <div class="spinner"></div>
        <div class="loader-text">Loading BINALEX...</div>
    </div>

    @include('admin.layouts.sidebar')

    <main class="main-content" id="mainContent">
        @include('admin.layouts.header')
        <div class="inner-content max-sm:px-3 max-lg:px-4 max-lg:py-6 lg:p-6 space-y-6">
            @yield('content')
        </div>
    </main>

    @include('admin.layouts.scripts')
</body>

</html>