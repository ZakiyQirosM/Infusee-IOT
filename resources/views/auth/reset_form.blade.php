<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="{{ asset('css/authstyle.css') }}">
</head>
<body>
<div class="content">
<a href="{{ route('login') }}" class="back-button">↩</a>
<form method="POST" action="{{ route('password.reset.sendlink') }}" class="login-container">
    @csrf
    <h2>Reset Password</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <input type="text" name="no_peg" placeholder="Masukkan NIK" value="{{ old('no_peg') }}" required>
    <button type="submit">Kirim Link Reset ke WhatsApp</button>
</form>
</div>
</body>
</html>
