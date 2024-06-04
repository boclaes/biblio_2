<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="contact-view">
    <div class="container">
        <div class="wrapper">
            <a href="{{ route('welcome') }}"><img class="main-logo" src="{{ Vite::asset('resources/images/logo-hor.png') }}"></a>
            <div class="contact-view-box">
                <div class="center">
                    <h1 class="title">Contact us</h1>
                    <div class="login-message">
                        <span>Reach out to us with any inquiries or feedback – we're here to assist you!</span>
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

                    <label for="comments">Comments / Questions</label>
                    <input type="comments" name="comments" placeholder="Type your comment / question here" minlength="8" required>
                    @error('comments')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="primary big-button">Send</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
