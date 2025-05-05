<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Pegawai</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/authstyle.css') }}">
</head>
<body>
    <div class="content">
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <a href="{{ route('landing') }}" class="back-button" style="position: absolute; top: 20px; left: 20px;">
            ↩
        </a>
        
        <form id="loginForm" class="login-container" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <h2>Login Infusee</h2>

            <div id="login-error" class="error" style="display: none;"></div>

            <div class="input-container">
                <input type="text" name="no_peg" placeholder="NIP" id="no_peg">
                
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Password">
                    <span class="toggle-password">
                        <i class='bx bx-hide'></i>
                    </span>
                </div>

                <button type="submit">Login</button>
            </div>

            <div class="reset-password-link">
                <a href="{{ route('password.reset.form') }}">Lupa Password?</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('.toggle-password');
            const password = document.getElementById('password');
            const eyeIcon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                if (type === 'password') {
                    eyeIcon.classList.remove('bx-show');
                    eyeIcon.classList.add('bx-hide');
                } else {
                    eyeIcon.classList.remove('bx-hide');
                    eyeIcon.classList.add('bx-show');
                }
            });

            setTimeout(() => {
                const errorElement = document.querySelector('.error');
                if (errorElement) {
                    errorElement.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    errorElement.style.opacity = '0';
                    errorElement.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        errorElement.remove();
                    }, 500);
                }
            }, 4000);
        });
    </script>
</body>
</html>