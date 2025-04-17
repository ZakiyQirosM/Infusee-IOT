<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Baru</title>
    <link rel="stylesheet" href="{{ asset('css/authstyle.css') }}">
</head>
<body>
<div class="content">
    <form method="POST" action="{{ route('password.set.submit') }}" class="login-container">
        @csrf
        <h2>Reset Password</h2>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <input type="hidden" name="no_peg" value="{{ $pegawai->no_peg }}">

        <input type="password" name="password" placeholder="Password Baru" required>
        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

        <button type="submit">Simpan Password Baru</button>
    </form>
</div>
</body>
</html>
