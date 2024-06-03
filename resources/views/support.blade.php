<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support - Book Scanner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>

    <header class="hero-header-faq" id="top">
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
            <h1>Frequently asked questions</h1>
        </div>
    </header>

    <div class="search-bar-support">
        <input type="text" class="search-support" id="search" placeholder="Search" autocomplete="off">
        <span class="search-icon-support"><i class="fas fa-search"></i></span>
    </div>

    <div class="contact-button">
        <button href="{{ route('contact') }}" class="primary big-button">Ask a question</button>
    </div>

    <div class="accordion">
    <div class="accordion-item">
        <button class="accordion-button" type="button">
            My item is missing some information, what can I do?
        </button>
        <div class="accordion-content">
            <p>All users can edit covers, titles, descriptions and creators. So feel free to make these changes as needed. Pro users can edit all data, so any missing information can be quickly added and removed. Alternatively, every item on the website has a “flag” option. If an item is flagged it will go under review and a Libib staff member will make any necessary changes within 48 business hours.</p>
        </div>
    </div>
    <div class="accordion-item">
        <button class="accordion-button" type="button">
            Is there a limit to how many items I can store?
        </button>
        <div class="accordion-content">
            <p>You can store up to 5000 books with the free version. With BiblioScan Pro you can store up to 10000 items.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            What does a Pro subscription get me?
        </button>
        <div class="accordion-content">
            <p>It gives you much more options and storage. Visit the pricing page for more information.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            What happens when I make a change to my subscription?
        </button>
        <div class="accordion-content">
            <p>If you change to pro you get a lot more options. If you change to free, all the extra's will become unavailable.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            How can I delete my account?
        </button>
        <div class="accordion-content">
            <p>You can delete your account in 'account settings'.</p>
        </div>
    </div>
    <!-- Repeat for other items -->
</div>


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
                    <li><a href="{{ route('contact') }}">Contact us</a></li>
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
                    <li><a href="{{ route('contact') }}">Contact us</a></li>
                    <li><a href="{{ route('support') }}">Support</a></li>
                </ul>
            </div>
        </div>
    </footer> 
</body>
</html>
