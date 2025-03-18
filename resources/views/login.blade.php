<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/auth/style.css">
    <title>Login</title>

</head>
<style>
    .errors {

        color: red;
        font-size: 14px;
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: left;

        padding: 5px;
        border: 1px solid red;

        border-radius: 5px;
        background-color: rgba(255, 0, 0, 0.2);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
        transition: all 0.3s ease-in-out;

        display: block;


        transition: all 0.3s ease-in-out;






    }
</style>

<body>

    <div class="container">
        <h2>login</h2>
        <form action="{{ route('Authlogin') }}" method="POST">
            @csrf
            <input type="id" name="id" placeholder="give us ur id" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>dont have an acount? <a href="{{ route('signupForm') }}">signup</a></p>

        @if ($errors->any())
            <div class="errors " style="color: red; font-size: 14px; margin-top: 10px;  ">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

    </div>

</body>

</html>
