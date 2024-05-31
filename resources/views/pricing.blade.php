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
        <nav class="container">
            <div class="wrapper"> 
                <div class="menu-list-desktop">
                    <ul class="menu-list-desktop-left">
                        <li><a href="{{ route('welcome') }}">Features</a></li>
                        <li><a href="{{ route('pricing') }}">Pricing</a></li>
                        <li><a href="{{ route('support') }}">Support</a></li>
                    </ul>
                    <div class="logo">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Company logo">
                    </div>
                    <ul class="menu-list-desktop-right">
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}" class="primary">Try for free</a></li>
                    </ul>
                </div>

                <div class="menu-list-mobile">
                    <div class="flex space-between">
                        <div class="logo">
                            <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Company logo">
                        </div>
                        <a class="hamburger-menu">Menu</a>
                    </div>
                    
                    <ul>
                        <li><a href="{{ route('welcome') }}">Features</a></li>
                        <li><a href="{{ route('pricing') }}">Pricing</a></li>
                        <li><a href="{{ route('support') }}">Support</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Try for free</a></li>
                    </ul>                     
                </div>
            </div>  
        </nav>
        <div class="heading-paragraph-2">
            <h1>Pick your Biblio<mark>Scan</mark> plan!</h1>
            <h4>Track your full library for free – we promise it's yours to keep.</h4>
            <h4>Hungry for more? Biblioscan Pro delivers!</h4>
        </div>
    </header>

    <section>
        <h2>Monthly / annual</h2>
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


    <footer class="footer desktop-footer">
        <div class="footer-content">
            <div class="explore">
                <h3>Explore</h3>
                <ul>
                <li><a href="{{ route('welcome') }}">Features</a></li>
                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Try for free</a></li>
                </ul>
                <a href="#" class="terms">Terms and Conditions</a>
            </div>
            <div class="logo">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="BiblioScan Logo">
            </div>
            <div class="learn">
                <h3>Learn</h3>
                <ul>
                    <li><a href="">Contact us</a></li>
                    <li><a href="{{ route('support') }}">Support</a></li>
                </ul>
                <a href="#" class="privacy">Privacy & cookie policy</a>
            </div>
        </div>
    </footer>
    
    <footer class="footer mobile-footer">
        <div class="footer-content">   
            <div class="learn">
            <div class="logo">
                <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="BiblioScan Logo">
            </div>
                <h3>Learn</h3>
                <ul>
                    <li><a href="">Contact us</a></li>
                    <li><a href="{{ route('support') }}">Support</a></li>
                </ul>
            </div>
        </div>
    </footer> 
</body>
</html>
