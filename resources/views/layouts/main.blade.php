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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">
        <h2 class="logo-title">Dashboard Monitoring</h2>
        <button class="toggle-btn" onclick="toggleSidebar()" id="toggle-btn">«</button>
    </div>
    <ul>
        <li class="{{ request()->routeIs('register.index') ? 'active' : '' }}">
            <a href="{{ route('register.index') }}">Registrasi Infusee</a>
        </li>
        <li class="{{ request()->routeIs('devices.index') ? 'active' : '' }}">
            <a href="{{ route('devices.index') }}">Device Aktif</a>
        </li>
        <li class="{{ request()->routeIs('infusee.index') ? 'active' : '' }}">
            <a href="{{ route('infusee.index') }}">Monitoring Infusee</a>
        </li>
    </ul>
</div>


<!-- Content -->    
<div class="content" id="content">
    <button class="toggle-btn-open" onclick="toggleSidebar()">☰</button>
    @yield('content')
</div>

<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
