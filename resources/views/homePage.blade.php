<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #74ebd5, #acb6e5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            text-align: center;
            color: white;
        }

        header {
            width: 100%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            transition: 0.3s;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
            color: black;
        }

        .button-container a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 6px;
            background: #74ebd5;
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: 0.3s;
        }

        .button-container a:hover {
            background: #5ac8d4;
        }
    </style>
</head>

<body>
    <header>
        <h1>Welcome to the Website</h1>
        <nav>
            <a href="{{ route('signupForm') }}">Signup</a>
            <a href="{{ route('loginForm') }}">Login</a>
            <a href="{{ route('homePage') }}">About</a>
        </nav>
    </header>

    <div class="container">
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt dolorem beatae quidem ipsam numquam
            sequi accusamus eligendi ipsa, modi quisquam distinctio commodi.</p>
        <div class="button-container">
            <p>What are you?</p>
            <a href="{{ route('signupForm') }}">New?</a>
            <a href="{{ route('loginForm') }}">Member?</a>
            <a href="{{ route('homePage') }}">Wanna learn about me?</a>
        </div>
    </div>
</body>

</html>
