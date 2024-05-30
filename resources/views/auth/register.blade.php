<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="register-view">
    <div class="container">
        <div class="wrapper">
            <a href="{{ route('welcome') }}"><img class="main-logo" src="{{ Vite::asset('resources/images/logo-hor.png') }}"></a>
            <div class="register-view-box">
                <div class="center">
                    <h1 class="title">Signup for Biblio<mark>Scan</mark></h1>
                    <div class="login-message">
                        <span>Already have an account? </span><a href="{{ route('login') }}">Login</a>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                    @error('name')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    @error('email')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" minlength="8" required>
                    @error('password')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <label for="password_confirmation">Confirm password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                    @error('password_confirmation')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="primary big-button">Start my library</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
