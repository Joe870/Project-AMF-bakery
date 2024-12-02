<nav>
    <ul>


        <li><a href="{{ route('login') }}">Login</a></li>
        <li><a href="{{ route('register') }}">Register</a></li>
        <li><a href="{{ route('profile') }}">Profile</a></li>
        <li><a href="{{ logout }}">Log Out</a></li>




    </ul>
</nav>

<style>
    nav {
        background-color: #e0e0e0;
        color: black;
        padding: 10px 0;
        width: 100%;
    }

    nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
    }

    nav ul li {
        margin: 0 15px;
    }

    nav ul li a {
        color: red;
        text-decoration: none;
    }

    nav ul li a:hover {
        color: black;
    }
</style>
