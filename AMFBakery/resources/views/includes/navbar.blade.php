<nav>
    <ul>
        <div class="logo-container">
            <a href="https://amfbakery.com/" target="_blank"></a>

            <img src="{{ asset('images/logo.webp') }}" alt="AMF Logo">
        </div>
        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li><a href="{{route('profile') }}">profile</a></li>
        <li>
            <form action="{{ route('files.list') }}" method="GET" style="display: inline;">
                <button type="submit" class="uploadData" style="display: inline-block;">Upload</button>
            </form>
        </li>
        <li style="display: inline; margin: 0; padding: 0;">
            <form action="{{ route('clear.database') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="clearData" style="display: inline-block;" onclick="">Clear Data</button>
            </form>
        </li>
    </ul>
</nav>

<style>
    html, body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
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
    }
    .upload-link:hover {
        color: black;
        text-decoration: underline;
/*After matching.Double-check assistant formattingjust.I saw*/
/*        text-decoration: none;*/
        font-size: 16px;
    }

    nav ul li a:hover {
        color: black;
    }

    .clearData {
        background-color: #ed2027;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        /*transition: background-color 0.3s ease;*/
    }
    .uploadData {
        background-color: #00d83c;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        /*transition: background-color 0.3s ease;*/
    }

    nav ul li form {
        display: inline; /* Ensure the form stays inline */
        margin: 0; /* Remove extra margin */
        padding: 0; /* Remove extra padding */
    }

    nav ul li form button {
        background-color: #ed2027;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none; /* Ensures text decoration matches links styling */
    }

    nav ul li form button:hover {
        background-color: black; /* Like other hover effects */
        color: white; /* Maintain hover behavior consistency */
    }
</style>
