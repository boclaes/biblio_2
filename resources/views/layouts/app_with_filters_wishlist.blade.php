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
        <div class="select-wrapper-wishlist">
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
    
    <div class="search-bar-wishlist">
        <input type="text" class="search-wishlist" id="search" placeholder="Search" autocomplete="off">
        <span class="search-icon-wishlist"><i class="fas fa-search"></i></span>
    </div>

    <button type="button" class="button-add-wishlist" onclick="window.location.href='{{ route('search.form', ['wish' => 1]) }}';">Add Books</button>

    <div class="alphabet-filter-wishlist">
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
                        <li><a href="{{ route('welcome') }}">My library</a></li>
                        <li><a href="{{ route('search.form') }}">Add books</a></li>
                        <li><a href="{{ route('borrowed-books') }}">Book borrower</a></li>
                        <li><a href="{{ route('book.recommend') }}">Discover books</a></li>
                        <li><a href="{{ route('accepted.books') }}">Wishlist</a></li>
                        <li><a href="{{ route('account.settings') }}">Account</a></li>
                        <li><a href="{{ route('welcome') }}">Support</a></li>
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
                        <li><a href="{{ route('welcome') }}">My library</a></li>
                        <li><a href="{{ route('search.form') }}">Add books</a></li>
                        <li><a href="{{ route('borrowed-books') }}">Book borrower</a></li>
                        <li><a href="{{ route('book.recommend') }}">Discover books</a></li>
                        <li><a href="{{ route('accepted.books') }}">Wishlist</a></li>
                        <li><a href="{{ route('account.settings') }}">Account</a></li>
                        <li><a href="{{ route('welcome') }}">Support</a></li>
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
    {{--
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
    --}}
    <div class="content">
        @yield('content')
    </div>

    <script src="{{ asset('js/sorting.js') }}"></script>
</body>
</html>
