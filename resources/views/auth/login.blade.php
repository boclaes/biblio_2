<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Biblio</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="login-view">
    <div class="container">
        <div class="wrapper">
            <a href="{{ route('welcome') }}"><img class="main-logo" src="{{ Vite::asset('resources/images/logo-hor.png') }}"></a>
            <div class="login-view-box">
                <div class="center">
                    <h1 class="title">Biblio<mark>Scan</mark> login</h1>
                    <div class="signup-message">
                        <span>Don't have an account yet? </span><a href="{{ route('register') }}">Sign up</a>
                    </div>
                </div>

                @if (session('status'))
                <div>
                    {{ session('status') }}
                </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if ($errors->any())
                    <div class="error-msg">
                        @foreach ($errors->all() as $error)
                        <div class="error-msg-text">{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>

                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" minlength="8" required>
                    <button class="primary big-button" type="submit">Login</button>
                </form>
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                </div>
            </div>
        </div> 
    </div>
</body>
</html>
