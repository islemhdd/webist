<!-- hadi hia  / -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wibist</title>
    <link rel="shortcut icon" href="logo.png">
    <style>
        /* * {
            margin: 0;
            border: 0;
        } */

        header {
            width: 100%;
            height: 10vh;
            background-color: #ACE4EC;
        }

        .hello {
            width: 100%;
            height: 70vh;
        }

        .hello img {
            width: 40%;
            height: 100%;
            float: left;
        }

        p {
            font-size: 2.5em;
            color: #778DA9;
            font-family: system-ui, 'Segoe UI', 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        h1 {
            font-size: 5em;
            color: #FF8FAB;
            font-family: system-ui, 'Segoe UI', 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .login1 {
            width: 200px;
            height: 100px;
            background-color: #DCD6F7;

        }
    </style>
</head>

<body>
    <header></header>
    <div class="hello"><img src="/wibist.jpg" alt="">
        <p>Bienvenue sur </p>
        <h1>wibist</h1>
        <p class="blabla">Notre application conçue spécialement pour renforcer la communication entre l'abrigade et les
            étudiants, de manière simple et efficace .</p>
        <div class="login1"><a href="{{ route('showLoginForm') }}">login</a></div>
        {{-- manich fahm hadi alah hello2 --}}
        <div class="hello2">
            <h1></h1>
            <div class="hi">
                <p></p>
            </div>
            <div class="hi">
                <p></p>
            </div>
            <div class="hi">
                <p></p>
            </div>
            <div class="hi">
                <p></p>
            </div>
        </div>
        <div class="album">
            <!--m3mbalich wchno haduma-->
            <div class="photo1"> <img src="" alt=""> </div>
            <div class="photo2"><img src="" alt=""></div>
            <div class="photo3"><img src="" alt=""></div>
            <div class="photo4"><img src="" alt=""></div>
            <div class="photo5"><img src="" alt=""></div>
        </div>
    </div>
</body>

</html>
