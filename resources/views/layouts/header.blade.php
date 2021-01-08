<header class="header">
    <div class="max-w-screen-xl mx-auto p-4 sm:px-6 lg:px-8 flex lg:flex-row sm:flex-row">
        <div class="logo ">
            @auth<a href="/reading-list">@else<a href="/"> @endauth
                <img src="/images/logo.svg" alt="Marvel Reading List">
                <span>Marvel Reading List</span>
            </a>
        </div>
        <nav class="main-nav flex flex-grow items-stretch">
            <ul class="w-full flex lg:flex-row sm:flex-row justify-end items-stretch">
            @auth
                <li class="flex items-center"><a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">@csrf</form>
                </li>
            @else
                <li class="flex items-center"><a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Login</a></li>

                @if (Route::has('register'))
                    <li class="flex items-center"><a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a></li>
                @endif
            @endauth

            </ul>
        </nav>
    </div>
</header>
