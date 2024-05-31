<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Book Scanner')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="books-view">
    <!-- Error and success messages -->
    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <div class="sidebar">
        <a href="{{ route('welcome') }}"><img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Company logo" class="main-logo"></a>
        <nav>
            <ul class="nav-group">
                <li><a href="{{ route('welcome') }}">My library</a></li>
                <li><a href="{{ route('search.form') }}">Add books</a></li>
                <li><a href="{{ route('borrowed-books') }}">Book borrower</a></li>
                <li><a href="{{ route('book.recommend') }}">Discover books</a></li>
                <li><a href="{{ route('accepted.books') }}">Wishlist</a></li>
            </ul>
            <ul class="nav-group">
                <li><a href="{{ route('account.settings') }}">Account</a></li>
                <li><a href="{{ route('welcome') }}">Support</a></li>
            </ul>
            <ul class="nav-group">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="primary small-button center">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>

    @yield('content')
</body>
</html>
