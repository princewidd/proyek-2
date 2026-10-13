<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - ZeroShop</title>
    <link rel="icon" type="image/png" href="{{ asset('img/icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <div class="login-page">
        <div class="left">
            <img src="{{ asset('img/logoLR.png') }}" alt="ZeroShop Logo" class="logo">
            <h1 class="logo-title">ZEROSHOP</h1>
            <p class="logo-subtitle">Game Boosting & Account Store</p>
        </div>

        <div class="divider"></div>

        <div class="right">
            <h1 class="brand">ZEROSHOP</h1>

            <div class="signup-text">
                First time here? 
                <a href="{{ route('register') }}" class="signup-btn">Sign up</a>
            </div>

            <h2 class="welcome">Welcome back</h2>

            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                    <div class="row login-row">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot">Forget Password</a>
                        @else
                            <div></div>
                        @endif

                        <button type="submit" class="btn-login">
                            Log in <span>&gt;</span>
                        </button>
                    </div>
            </form>
        </div>
    </div>

</body>
</html>