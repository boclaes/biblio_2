<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Book Scanner</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>

    <header class="hero-header-price" id="top">
        @extends('layouts.welcome_nav')
        <div class="heading-paragraph-3">
            <h1>Pick your Biblio<mark>Scan</mark> plan!</h1>
            <h4>Track your full library for free – we promise it's yours to keep.</h4>
            <h4>Hungry for more? Biblioscan Pro delivers!</h4>
        </div>
    </header>

    <section>
        <div class="toggle-button-group">
            <div class="toggle-slide"></div>
            <button class="toggle-button" onclick="toggleSlide('monthly')">Monthly</button>
            <button class="toggle-button" onclick="toggleSlide('annual')">Annual</button>
        </div>
        <div class="comparison-container">
        <div class="plan-card-left">
            <h2>BiblioScan Free</h2>
            <p class="plan-price">€0/month</p>
            <p class="plan-info">Ideal for individual book enthusiasts and novice collectors.</p>
            <ul class="plan-features">
                <li class="plan-feature">Book organization</li>
                <li class="plan-feature"><a>Up to 5000 items</a></li>
                <li class="plan-feature">Wishlist sharing</li>
                <li class="plan-feature"><a>Share your wishlist with friends and family</a></li>
                <li class="plan-feature">Reading Progress</li>
                <li class="plan-feature"><a>Mark books as "currently reading"</a></li>
                <li class="plan-feature">Filtering Options</li>
                <li class="plan-feature"><a>Filter books by genre, alphabetically, by author, or by title using the search function</a></li>
            </ul>
        </div>
        <div class="plan-card-right">
            <h2>BiblioScan <mark>Pro</mark></h2>
            <p class="plan-price">€9/month</p>
            <p class="plan-info">Specially designed for passionate readers, home libraries, and serious collectors.</p>
            <ul class="plan-features">
                <li class="plan-feature">Book organization</li>
                <li class="plan-feature"><a>Up to 100,000 items</a></li>
                <li class="plan-feature ">Scanner Inclusion</li>
                <li class="plan-feature"><a>Includes a scanner for easy book input</a></li>
                <li class="plan-feature">Recommendations</li>
                <li class="plan-feature"><a>Receive book recommendations based on your reading history and preferences</a></li>
                <li class="plan-feature">Add Notes and Reviews</li>
                <li class="plan-feature"><a>Write and attach notes and reviews to your own books in your list</a></li>
            </ul>
        </div>
        </div>
    </section>

    <section class="features">
        <div class="wrapper">
            <div class="heading-paragraph">
                <h2 class="wide">What does BiblioScan Pro offer?</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_scan.png') }}" alt="Logo scan">
                    <h4>Comprehensive scanning</h4>
                    <p>Effortlessly scan ISBN barcodes or manually enter ISBNs to add books to your collection.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_boekman.png') }}" alt="Logo boek met icon">
                    <h4>Book borrowing</h4>
                    <p>Keep track of which books you've lent out and to whom, ensuring you never lose a borrowed book again.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_notes.png') }}" alt="Logo notitieblok">
                    <h4>Add notes</h4>
                    <p>Personalise your reading experience by adding notes to your books, helping you remember key insights and details.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_ster.png') }}" alt="Logo ster">
                    <h4>Recommendations</h4>
                    <p>Get tailored book suggestions based on your reading history and preferences.</p>
                </div>
                      <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_sterren.png') }}" alt="Logo sterren">
                    <h4>Reviews</h4>
                    <p>Share your thoughts and opinions by adding reviews to your books, providing valuable feedback for yourself.</p>
                </div>
                <div class="feature-card">
                    <img src="{{ Vite::asset('resources/images/logo_pen.png') }}" alt="Logo pen">
                    <h4>Edit everything</h4>
                    <p>Easily update and modify book details, ensuring your collection is always up-to-date and accurate the way you want it.</p>
                </div>
            </div>
    </section>

    @extends('layouts.welcome_footer')