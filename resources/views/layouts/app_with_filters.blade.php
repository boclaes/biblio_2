<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>@yield('title', 'Book Scanner')</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body class="books-view">
    {{--
    <!-- Error and success messages -->
    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    --}}
    <div class="dropdown">
        <div class="select-wrapper">
            <select id="sort" class="select-custom">
                @isset($includeName)
                    @if ($includeName)
                        <option value="name_asc">Title</option>
                    @endif
                @endisset
                @isset($includeRatings)
                    @if ($includeRatings)
                        <option value="rating_desc">Rating</option>
                    @endif
                @endisset
                @isset($includeAuthor)
                    @if ($includeAuthor)
                        <option value="author">Author</option>
                    @endif
                @endisset
                @isset($includePages)
                    @if ($includePages)
                        <option value="pages">Pages</option>
                    @endif
                @endisset
                @isset($includeDate)
                    @if ($includeDate)
                        <option value="date_desc">Date</option>
                    @endif
                @endisset
            </select>
        </div>
    </div>
    
    <div class="search-bar">
        <input type="text" class="search" id="search" placeholder="Search" autocomplete="off">
        <span class="search-icon"><i class="fas fa-search"></i></span>
    </div>
    
    <button class="grid-button">
        <i class="fas fa-th"></i>
        <span class="button-text-grid">Cover</span>
    </button>

    <div class="alphabet-filter">
        @foreach(range('A', 'Z') as $letter)
            <a href="#" data-letter="{{ $letter }}">{{ $letter }}</a>
        @endforeach
    </div>

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
    <div class="content">
        @yield('content')
    </div>

    <script src="{{ asset('js/sorting.js') }}"></script>
</body>
</html>
