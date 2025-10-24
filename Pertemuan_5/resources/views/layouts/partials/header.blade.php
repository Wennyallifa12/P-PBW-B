<header class="bg-white shadow-md">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600">SMP Mentari Ceria</a>
        <nav class="space-x-4">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600">Home</a>
            <a href="{{ url('/bukutamu') }}" class="text-gray-600 hover:text-blue-600">Buku Tamu</a>

            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    class="text-gray-600 hover:text-blue-600">
                        Log Out
                    </a>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">Log in</a>
                <a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600">Register</a>
            @endauth
        </nav>
    </div>
</header>
