<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Book Scanner')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="books-view">

    <!-- Error and success messages -->
    @if(session('error'))
    <div id="error-message" style="display: none; color: red; position: fixed; top: 20px; right: 20px; background-color: #f8d7da; padding: 10px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div id="success-message" style="display: none; color: green; position: fixed; top: 20px; right: 20px; background-color: #dff0d8; padding: 10px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">{{ session('success') }}</div>
    @endif

    <div class="sidebar">
    <a href="{{ route('welcome') }}"><img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Company logo" class="main-logo"></a>
        <nav class="container-library">
            <div class="wrapper-library"> 
                <div class="menu-list-desktop">
                    <ul class="nav-group">
                        @php
                            $currentRoute = Route::currentRouteName();
                            $isWishlist = request()->query('wish') == 1;
                        @endphp
                        <li class="{{ $currentRoute == 'books' || $currentRoute == 'details.book' || $currentRoute == 'edit.notes' || $currentRoute == 'edit.review' || $currentRoute == 'edit.book' ? 'active' : '' }}">
                            <a href="{{ route('books') }}">My library</a>
                        </li>
                        <li class="{{ $currentRoute == 'search.form' && !$isWishlist || $currentRoute == 'search' || $currentRoute == 'add.edit.book' || $currentRoute == 'details.book.search' ? 'active' : '' }}">
                            <a href="{{ route('search.form') }}">Add books</a>
                        </li>
                        <li class="{{ $currentRoute == 'borrowed-books' || $currentRoute == 'books.addBorrow'  || $currentRoute == 'borrowings.edit' ? 'active' : '' }}">
                            <a href="{{ route('borrowed-books') }}">Book borrower</a>
                        </li>
                        <li class="{{ $currentRoute == 'book.recommend' ? 'active' : '' }}">
                            <a href="{{ route('book.recommend') }}">Discover books</a>
                        </li>
                        <li class="{{ $currentRoute == 'accepted.books' || $currentRoute == 'search.form' && $isWishlist ? 'active' : '' }}">
                            <a href="{{ route('accepted.books') }}">Wishlist</a>
                        </li>
                        <li class="{{ $currentRoute == 'account.settings' ? 'active' : '' }}">
                            <a href="{{ route('account.settings') }}">Account</a>
                        </li>
                        <li class="{{ $currentRoute == 'support_platform' || $currentRoute == 'contact_platform' ? 'active' : '' }}">
                            <a href="{{ route('support_platform') }}">Support</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="primary small-button center">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="menu-list-mobile">
                    <div class="flex space-between">
                        <a class="hamburger-menu-library">Menu</a>
                    </div>
                    <ul class="nav-group">
                        @php
                            $currentRoute = Route::currentRouteName();
                        @endphp
                        <li class="{{ $currentRoute == 'books' || $currentRoute == 'details.book' || $currentRoute == 'edit.notes' || $currentRoute == 'edit.review' || $currentRoute == 'edit.book' ? 'active' : '' }}">
                            <a href="{{ route('books') }}">My library</a>
                        </li>
                        <li class="{{ $currentRoute == 'search.form' && !$isWishlist || $currentRoute == 'search' || $currentRoute == 'add.edit.book' || $currentRoute == 'details.book.search' ? 'active' : '' }}">
                            <a href="{{ route('search.form') }}">Add books</a>
                        </li>
                        <li class="{{ $currentRoute == 'borrowed-books' || $currentRoute == 'books.addBorrow'  || $currentRoute == 'borrowings.edit' ? 'active' : '' }}">
                            <a href="{{ route('borrowed-books') }}">Book borrower</a>
                        </li>
                        <li class="{{ $currentRoute == 'book.recommend' ? 'active' : '' }}">
                            <a href="{{ route('book.recommend') }}">Discover books</a>
                        </li>
                        <li class="{{ $currentRoute == 'accepted.books' || $currentRoute == 'search.form' && $isWishlist ? 'active' : '' }}">
                            <a href="{{ route('accepted.books') }}">Wishlist</a>
                        </li>
                        <li class="{{ $currentRoute == 'account.settings' ? 'active' : '' }}">
                            <a href="{{ route('account.settings') }}">Account</a>
                        </li>
                        <li class="{{ $currentRoute == 'support_platform' || $currentRoute == 'contact_platform' ? 'active' : '' }}">
                            <a href="{{ route('support_platform') }}">Support</a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="primary small-button center">Logout</button>
                            </form>
                        </li>
                    </ul>                   
                </div>
            </div>  
        </nav>
    </div>
    @yield('content')
    <script src="{{ asset('js/search.js') }}"></script>
</body>
</html>
