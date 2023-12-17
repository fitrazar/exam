<nav class="navbar navbar-light border-0 navbar-expand fixed-bottom"
    style="box-shadow: 5px 15px 30px 5px #888888; background-color: #CDF5FD;">
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                <i class="fa-solid fa-house" style="color: #007bff;"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/shop') }}" class="nav-link">
                <i class="fa-solid fa-store" style="color: #007bff;"></i>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/cart') }}" class="nav-link {{ Request::is('cart*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping" style="color: #007bff;"></i>
            </a>
        </li>
        <li class="nav-item dropup me-1">
            <a href="#" class="nav-link text-center {{ Request::is('profile*') ? 'active' : '' }}" role="button"
                id="dropdownMenuProfile" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-user" style="color: #007bff;"></i>
            </a>
            <!-- Dropup menu for profile -->
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuProfile">
                @guest
                    @if (Route::has('login'))
                        <a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <a class="dropdown-item" href="{{ url('/user/profile') }}">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ url('/logout') }}"
                        onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            @endguest
        </li>
    </ul>
</nav>
