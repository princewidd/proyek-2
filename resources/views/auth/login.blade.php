<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | ZEROSHOP</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-page">
        <div class="left">
            <img src="{{ asset('img/zeroshop-black.png') }}" alt="ZEROSHOP" class="logo">
        </div>
        <div class="divider"></div>
        <div class="right">
            <h1 class="brand">ZEROSHOP</h1>
            <p class="signup-text">
                First time here?
                <a href="{{ route('register') }}" class="signup-btn">Sign up</a>
            </p>
            <h2 class="welcome">Welcome back</h2>

            @if ($errors->any())
                <p class="error">{{ $errors->first() }}</p>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <div class="row">
                    <a href="{{ route('password.request') }}" class="forgot">Forget Password</a>
                    <button type="submit" class="btn-login">Log in <span class="arrow">&gt;</span></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>