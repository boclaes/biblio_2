<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="password-view">
    <div class="container">
    <a href="{{ route('login') }}"><img src="{{ Vite::asset('resources/images/back.png') }}" alt="Go back" class="back-pasw" ></a>
        <div class="wrapper">
            <a href="{{ route('welcome') }}"><img class="main-logo" src="{{ Vite::asset('resources/images/logo-hor.png') }}"></a>
            <div class="password-view-box">
                <h2 class="title">Forgot your password?</h2>
                @if (session('status'))
                    <div>{{ session('status') }}</div>
                @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                <button class="primary big-button" type="submit">Send email with link</button>
            </form>
            </div>
</body>
</html>
