<html lang="en">


<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/auth/style.css">
    <style>
        #err {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>signup</h2>
        <form action="{{ route('Authsignup') }}" method="post">
            @csrf



            <input type="text" name="name" placeholder="name" required>
            <input type="number" name="grade" placeholder="grade" required>
            <input type="password" name=" password"placeholder="password" required>


            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <button type="submit">signup</button>
        </form>
        @if ($errors->any())
            <div id="err">
                <ul></ul>
                @foreach ($errors->all() as $error)
                    <li> {{ $error }}
                    </li>
                @endforeach
            </div>
        @endif
        <p>already have an acount? <a href="{{ route('loginForm') }}">login</a></p>

    </div>

</body>

</html>
