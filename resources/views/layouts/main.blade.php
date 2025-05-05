<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="assign-device-url" content="{{ route('devices.assign') }}">
    <meta name="register-store-url" content="{{ route('register.store') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
@if($errors->any())
<div class="error-popup" id="errorPopup">
    <div class="error-popup-content">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
</div>
@endif
<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="logo">
        <h2 class="logo-title">Dashboard Monitoring</h2>
        <button class="toggle-btn" onclick="toggleSidebar()" id="toggle-btn">«</button>
    </div>
    @auth 
    <div class="user-info">
        <div class="user-icon">
            {!! '<svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960" width="28px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h168q14-36 44-58t68-22q38 0 68 22t44 58h168q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm280-670q13 0 21.5-8.5T510-820q0-13-8.5-21.5T480-850q-13 0-21.5 8.5T450-820q0 13 8.5 21.5T480-790ZM200-246q54-53 125.5-83.5T480-360q83 0 154.5 30.5T760-246v-514H200v514Zm280-194q58 0 99-41t41-99q0-58-41-99t-99-41q-58 0-99 41t-41 99q0 58 41 99t99 41ZM280-200h400v-10q-42-35-93-52.5T480-280q-56 0-107 17.5T280-210v10Zm200-320q-25 0-42.5-17.5T420-580q0-25 17.5-42.5T480-640q25 0 42.5 17.5T540-580q0 25-17.5 42.5T480-520Zm0 17Z"/></svg>' !!}
        </div>
        <div class="user-text">
            <span class="greeting">Halo</span>
            <p class="username">{{ Auth::user()->nama_peg }}</p>
        </div>
    </div>
    @endauth
    <ul>
        <li class="{{ request()->routeIs('register.index') ? 'active' : '' }}">
            <a href="{{ route('register.index') }}">
                {!! '<svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#FFFFFF"><path d="M720-240q25 0 42.5-17.5T780-300q0-25-17.5-42.5T720-360q-25 0-42.5 17.5T660-300q0 25 17.5 42.5T720-240Zm0 120q32 0 59-16t43-42q-23-12-48.5-19t-53.5-7q-28 0-53.5 7T618-178q16 26 43 42t59 16Zm-504-24q-29.7 0-50.85-21.15Q144-186.3 144-216v-528q0-29.7 21.15-50.85Q186.3-816 216-816h528q29.7 0 50.85 21.15Q816-773.7 816-744v258q-17.1-5.76-35.1-9.92T744-502v-242H216v528h241q1.88 19.52 5.94 37.26Q467-161 473-144H216Zm0-96v24-528 242-2 264Zm72-48h172q4-19 10.19-36.97Q476.38-342.93 484-360H288v72Zm0-156h264q26-20 56-34.5t64-20.5v-17H288v72Zm0-156h384v-72H288v72ZM719.77-48Q640-48 584-104.23q-56-56.22-56-136Q528-320 584.23-376q56.22-56 136-56Q800-432 856-375.77q56.22 56.22 56 136Q912-160 855.77-104q-56.22 56-136 56Z"/></svg>' !!}
                Registrasi Infusee
            </a>
        </li>
        <li class="{{ request()->routeIs('devices.index') ? 'active' : '' }}">
            <a href="{{ route('devices.index') }}">
                {!! '<svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#FFFFFF"><path d="M313-192q-13.2 0-24.6-6.29Q277-204.57 271-216l-67-120h52.67L293-264h80v-24h-65l-36-72h-81l-55-96q-3-5-4.5-11.33-1.5-6.34-1.5-12.67 0-3 6-24l55-96h83l36-72h65v-24h-79l-36 72h-56l67-120q6-11.43 17.4-17.71Q299.8-768 313-768h71q20.4 0 34.2 13.8Q432-740.4 432-720v144h-45l-36 24h81v120h-86l-36-72h-79l-26 24h91l35.79 72H432v168q0 20.4-13.8 34.2Q404.4-192 384-192h-71Zm250.76 0Q534-192 513-213.15 492-234.3 492-264q0-20 9.5-36.5T528-326v-308q-17-9-26.5-25.5T492-696q0-29.7 21.21-50.85 21.21-21.15 51-21.15T615-746.85q21 21.15 21 50.85 0 20-9.5 36.5T600-634v90l72-43.32Q672-618 693.21-639t51-21Q774-660 795-638.79t21 51Q816-558 794.82-537q-21.17 21-50.91 21-9.91 0-18.41-2t-16.6-7L627-476l91 73q6-2 12.36-3.5 6.37-1.5 13.64-1.5 30 0 51 21.21t21 51Q816-306 794.91-285t-50.7 21q-33.21 0-54.71-25.26T673-347l-73-58v79q16 9 25.5 25.27T635-264q0 29.7-20.74 50.85Q593.52-192 563.76-192Z"/></svg>' !!}
                Device
            </a>
        </li>
        <li class="{{ request()->routeIs('infusee.index') ? 'active' : '' }}">
            <a href="{{ route('infusee.index') }}">
                {!! '<svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#FFFFFF"><path d="M168-192q-29.7 0-50.85-21.16Q96-234.32 96-264.04v-432.24Q96-726 117.15-747T168-768h624q29.7 0 50.85 21.15Q864-725.7 864-696H168v432h288v72H168Zm0-72v-432 432ZM707.76-48Q678-48 657-69.15 636-90.3 636-120v-75q-68-12-112-64.5T480-384v-168q0-29 21-50.5t51-21.5h240q29 0 50.5 21.5T864-552v168q0 72-44 124.5T708-195v75h108v72H707.76ZM739-408h53v-144H552v72h52q29.05 0 55.03 14Q685-452 701-428q7 10 17.08 15 10.07 5 20.92 5Zm-67 144q37.07 0 66.53-20Q768-304 782-336h-43q-29.05 0-55.03-13.5Q658-363 642.12-386.82q-7.15-9.71-17.08-15.44Q615.12-408 604-408h-52v24q0 51 34.5 85.5T672-264Zm-30-164ZM240-528h168v-72H240v72Zm0 168h168v-72H240v72Z"/></svg>' !!}
                Monitoring Infusee
            </a>
        </li>
        <li class="{{ request()->routeIs('activity.index') ? 'active' : '' }}">
            <a href="{{ route('activity.index') }}">
                {!! '<svg xmlns="http://www.w3.org/2000/svg" height="22px" viewBox="0 -960 960 960" width="22px" fill="#FFFFFF"><path d="M480-120q-138 0-240.5-91.5T122-440h82q14 104 92.5 172T480-200q117 0 198.5-81.5T760-480q0-117-81.5-198.5T480-760q-69 0-129 32t-101 88h110v80H120v-240h80v94q51-64 124.5-99T480-840q75 0 140.5 28.5t114 77q48.5 48.5 77 114T840-480q0 75-28.5 140.5t-77 114q-48.5 48.5-114 77T480-120Zm112-192L440-464v-216h80v184l128 128-56 56Z"/></svg>' !!}
                Histori Aktivitas
            </a>
        </li>
    </ul>
    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-button" onclick="return confirm('Yakin logout?')">
                <box-icon name='log-out' color="#ff4d4d" size="md"></box-icon>
                <span class="logout-text">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Content -->    
<div class="content" id="content">
    <button class="toggle-btn-open" onclick="toggleSidebar()">☰</button>
    @yield('content')
</div>

<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
