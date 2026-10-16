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
                Already have a account? 
                <a href="{{ route('login') }}" class="login-btn">Log in</a>
            </div>

            <h2 class="welcome">Join ZeroShop</h2>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            @if ($errors->any())

    <div style="color: red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div id="step-1">
            <div class="input-group">
                <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="input-group">
                <input type="text" name="username" id="username" placeholder="Username" value="{{ old('username') }}" required autocomplete="off">
            </div>

                    <div class="row">
                        <button type="button" class="btn-submit" onclick="nextStep()">Continue ></button>
                    </div>
                </div>
            
                <div id="step-2" style="display: none;">
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required autocomplete="new-password">
                    </div>
                
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                    </div>
                
                    <div class="checkbox-row">
                        <label class="checkbox-container">
                            <input type="checkbox" name="subscribe">
                            <span>Subscribe for promo codes, special deals & free extras</span>
                        </label>
                    </div>
                
                    <div class="row" style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                        <button type="button" class="btn-back" onclick="prevStep()">&lt; Back</button>
                        <button type="submit" class="btn-submit">Create account</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function nextStep() {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');

            const emailInput = document.getElementById('email');
            const usernameInput = document.getElementById('username');

            if (!emailInput.reportValidity()) {
                return;
            }

            if (!usernameInput.reportValidity()) {
                return;
            }

            step1.style.display = 'none';
            step2.style.display = 'block';
            step2.classList.remove('fade-in');
            void step2.offsetWidth;
            step2.classList.add('fade-in');
        }

        function prevStep() {
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');

            step2.style.display = 'none';
            step1.style.display = 'block';
            step1.classList.remove('fade-in');
            void step1.offsetWidth;
            step1.classList.add('fade-in');
        }
    </script>
</body>
</html>