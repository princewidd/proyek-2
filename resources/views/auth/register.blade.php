<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join - ZeroShop</title>
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
                Already have an account? 
                <a href="{{ route('login') }}" class="login-btn">Log in</a>
            </div>

            <h2 class="welcome">Join ZeroShop</h2>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <input type="text" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="off">
                </div>

                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                </div>

                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password">
                </div>

                <div class="input-group">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmai Password" required autocomplete="new-password">
                </div>

                <div class="checkbox-row">
                    <label class="checkbox-container">
                        <input type="checkbox" name="subscribe">
                        <span class="checkbox-text">Subscribe for promo codes, special deals & free extras</span>
                    </label>
                </div>

                <div class="row">
                    <button type="submit" class="btn-submit">Create account</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>