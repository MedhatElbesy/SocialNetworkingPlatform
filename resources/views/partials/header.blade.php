<header>
    <h1>Social Media App</h1>
    <nav class="navbar">
        <ul>
            @if (Auth::check())
                <li><a href="{{ route('feeds.index') }}">Home</a></li>
                <li><a href="{{ route('profiles.show', Auth::id()) }}">Profile</a></li>
                <li><a href="{{ route('posts.create') }}">Create Post</a></li>
                <li>
                    <a href="#" id="logout-link">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endif
        </ul>
    </nav>
</header>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

header {
    background-color: #333;
    color: white;
    padding: 10px;
}

h1 {
    margin: 0;
}

.navbar {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.navbar ul {
    list-style-type: none;
    display: flex;
}

.navbar li {
    margin: 0 15px;
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.navbar a:hover {
    background-color: #555;
}
</style>

<script>
document.getElementById('logout-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('logout-form').submit(); 
});
</script>
