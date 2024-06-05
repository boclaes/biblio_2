<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="password-view">
    <div class="container">
    <a href="{{ route('login') }}"><img src="{{ Vite::asset('resources/images/back.png') }}" alt="Go back" class="back-pas" ></a>
        <div class="wrapper">
            <a href="{{ route('welcome') }}"><img class="main-logo" src="{{ Vite::asset('resources/images/logo-hor.png') }}"></a>
            <div class="password-view-box">
                <h2 class="title">Reset your password</h2>
                @if (session('status'))
                    <div>{{ session('status') }}</div>
                @endif
            <form method="POST" action="{{ route('password.update') }}">
            @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" placeholder="Email" value="{{ $email ?? old('email') }}" required>
                <input type="password" name="password" placeholder="New Password" minlength="8" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" minlength="8" required>
                <button class="primary big-button" type="submit">Reset password</button>
            </form>
            </div>
<body>

</html>
