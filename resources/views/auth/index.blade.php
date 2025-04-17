<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Pegawai</title>
    <link rel="stylesheet" href="{{ asset('css/authstyle.css') }}">
</head>
<body>
<div class="content">
<a href="{{ route('landing') }}" class="back-button" style="position: absolute; top: 20px; left: 20px;">
     ↩
</a>
    <form method="POST" action="{{ route('login.submit') }}" class="login-container">
        @csrf
        <h2>Login Infusee</h2>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif
        <div class="input-container">
            <input type="text" name="no_peg" placeholder="NIP" value="{{ old('no_peg') }}">
            <input type="password" name="password" placeholder="Password">
            <button type="submit">Login</button>
        </div>

        <div class="reset-password-link">
            <a href="{{ route('password.reset.form') }}">Lupa Password?</a>
        </div>
    </form>
</div>
</body>
</html>
