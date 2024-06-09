<nav class="container">
            <div class="wrapper"> 
                <div class="menu-list-desktop">
                    <ul class="menu-list-desktop-left">
                        @php
                            $currentRoute = Route::currentRouteName();
                        @endphp
                        <li class="{{ $currentRoute == 'welcome' ? 'active' : '' }}">
                            <a href="{{ route('welcome') }}">Features</a>
                        </li>
                        <li class="{{ $currentRoute == 'pricing' ? 'active' : '' }}">
                            <a href="{{ route('pricing') }}">Pricing</a>
                        </li>
                        <li class="{{ $currentRoute == 'support' ? 'active' : '' }}">
                            <a href="{{ route('support') }}">Support</a>
                        </li>
                    </ul>
                    <div class="logo">
                        <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Company logo">
                    </div>
                    <ul class="menu-list-desktop-right">
                        <li class="{{ $currentRoute == 'login' ? 'active' : '' }}">
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="{{ $currentRoute == 'register' ? 'active' : '' }}">
                            <a href="{{ route('register') }}" class="primary">Try for free</a>
                        </li>
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
                        <li class="{{ $currentRoute == 'welcome' ? 'active' : '' }}">
                            <a href="#home">Features</a>
                        </li>
                        <li class="{{ $currentRoute == 'pricing' ? 'active' : '' }}">
                            <a href="{{ route('pricing') }}">Pricing</a>
                        </li>
                        <li class="{{ $currentRoute == 'support' ? 'active' : '' }}">
                            <a href="{{ route('support') }}">Support</a>
                        </li>
                        <li class="{{ $currentRoute == 'login' ? 'active' : '' }}">
                            <a href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="{{ $currentRoute == 'register' ? 'active' : '' }}">
                            <a href="{{ route('register') }}">Try for free</a>
                        </li>
                    </ul>                     
                </div>
            </div>  
        </nav>