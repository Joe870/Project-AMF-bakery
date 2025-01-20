<nav>
    <ul>
        <div class="logo-container">
            <a href="https://amfbakery.com/" target="_blank">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo">
            </a>
        </div>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
        <li><a href="{{ route('profile') }}">Profile</a></li>
    </ul>
</nav>

<style>
    /* Ensure no gaps on the body */
    html, body {
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #e0e0e0;
        color: black;
        padding: 10px 0;
        width: 100%; /* Updated width */
        display: flex;
        align-items: center;
        height: 70px;
        position: relative; /* Ensures it covers the whole page width */
        box-sizing: border-box;
    }

    .logo-container img {
        height: 50px;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }

    nav ul li {
        margin: 0 15px;
    }

    nav ul li a {
        color: #ed2027;
        text-decoration: none;
        font-size: 16px;
    }

    nav ul li a:hover {
        color: black;
    }
</style>
