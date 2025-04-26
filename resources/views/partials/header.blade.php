<header>
    <nav>
        <a href="{{ route('home') }}">Home</a>
        @guest
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @else
            @if(Auth::user()->role == 'donor')
                <a href="{{ route('donor.dashboard') }}">Donor Dashboard</a>
            @elseif(Auth::user()->role == 'recipient')
                <a href="{{ route('recipient.dashboard') }}">Recipient Dashboard</a>
            @elseif(Auth::user()->role == 'admin')
                <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @endguest
    </nav>
</header>