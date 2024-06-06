<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
{{--     <h1>Welcome to Book Scanner</h1>
    <a href="{{ route('register') }}">Register</a>
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('pricing') }}">Pricing</a>
    <a href="{{ route('support') }}">Support</a> --}}

    <header class="hero-header" id="top">
        @extends('layouts.welcome_nav')
        <div class="heading-paragraph-2">
            <h1>Cloud cataloging</h1>
            <h3>Unite your library</h3>
            <button class="primary big-button" href="{{ route('register') }}">Get started</button>
        </div>
    </header>

    <section class="features">
        <div class="wrapper">
            <div class="heading-paragraph">
                <h2>Curate & connect your reads</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_boek.png') }}" alt="Logo boek">
                    <h4>Organise your entire collection</h4>
                    <p>Effortlessly catalogue your entire book collection. With our platform, you'll never forget a title, author, or genre again. Keep track of every book you own, from novels to non-fiction.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_glas.png') }}" alt="Logo glas">
                    <h4>Quick and intuitive search</h4>
                    <p>Say goodbye to endless scrolling and searching. With our powerful search and filter options, locating a specific book in your collection is quick and hassle-free.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_klok.png') }}" alt="Logo klok">
                    <h4>Access anytime, anywhere</h4>
                    <p>Our platform is fully accessible on all your devices. Whether you're at home, at work, or on the go, your book collection is always just a click away.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_ster.png') }}" alt="Logo ster">
                    <h4>Personalised recommendations</h4>
                    <p>Based on your reading history and preferences, our platform provides tailored book recommendations. Expand your literary horizons and discover new favourites that align with your tastes and interests.</p>
                </div>
            </div>
            <div class="feature-text">
                <h4>BiblioScan is the best place for cataloging and managing your books online. </h4>
                <h4> Now which version is the best for <mark>you</mark>?</h4>
                
            </div>
            <button class="primary big-button">Discover plans</button>
        </div>
    </section>
    
    <section class="book-section">
        <div class="book-feature">
            <div class="book-feature-content">
                <h3>Automatic data for books and E-books</h3>
                <p>Just use our dedicated scanner to scan the ISBN/UPC barcode, or manually enter the ISBN. We'll then fetch and organise the details for you, making managing your collection a breeze.</p>
            </div>
            <img src="{{ Vite::asset('resources/images/meisje.png') }}" alt="Meisje met boeken">
        </div>
        <div class="book-feature">
            <div class="book-feature-content">
                <h3>Collaborative Book Wishes</h3>
                <p>With BiblioScan, not only can you create a wishlist of books you desire, but you can also easily share it with friends and family. Whether it's for gift ideas or shared reading interests, make book-sharing a communal experience.</p>
            </div>
            <img src="{{ Vite::asset('resources/images/vrouw.png') }}" alt="vrouw met boeken">
        </div>
        <div class="book-feature">
            <div class="book-feature-content">
                <h3>Unlock Advanced Features with BiblioScan Pro</h3>
                <p>Want to track book lending, manage personal recommendations, or enjoy the convenience of our dedicated scanner? BiblioScan Pro offers an array of additional features tailored for the avid book collector. Experience enhanced book management like never before.</p>
            </div>
            <img src="{{ Vite::asset('resources/images/man.png') }}" alt="Man met tablet">
        </div>
    </section>

    @extends('layouts.welcome_footer')