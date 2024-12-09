<nav>
    <ul>
        <div class="logo-container">
            <a href="https://amfbakery.com/" target="_blank">

            <img src="{{ asset('images/logo.webp') }}" alt="Logo">
        </div>
        <li><a href="{{ route('dashboard') }}">Dashboard</li>

        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>

    </ul>
</nav>

<style>
    nav {
        background-color: #e0e0e0;
        color: black;
        padding: 10px 0;
        width: 100%;
        display: flex; 
        align-items: center;
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
