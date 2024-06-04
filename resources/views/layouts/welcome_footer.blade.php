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
