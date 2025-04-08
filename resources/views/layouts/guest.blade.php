<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="assign-device-url" content="{{ route('devices.assign') }}">
    <meta name="register-store-url" content="{{ route('register.store') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Infusee</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
<!-- Content -->    
    <div class="content" id="content">
    <a href="{{ route('landing') }}" class="back-button" style="position: absolute; top: 20px; left: 20px;">
     ↩
    </a>
        @yield('content')
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
