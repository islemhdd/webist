<!DOCTYPE html>
<html lang="en">

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/css/all.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"> --}}
    <link rel="shortcut icon" href="logo.png">
    <title>wibist:login</title>
</head>

<body>
    <img class="login" src="this.jpg" alt="">
    <img src="logo.png" alt="" class="logo">
    <div class="input">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <br><br>
            <label for="nu">ID</label>
            <br><br>
            <input type="tel"pattern="[0-9]*" maxlenght="7" name="id" id="nu"><br><br><br>
            <label for="password">mot de passe</label><br><br>

            <input type="password" name="password" id="password">
            <span id="togglePassword" class="eye-icon"><i class="fa-regular fa-eye eye-icon"></i></span>


            {{-- <script>
                document.getElementById("togglePassword").addEventListener("click", function() {
                    let passwordInput = document.getElementById("password");

                    if (passwordInput.type === "password") {
                        passwordInput.type = "text"; // Afficher le mot de passe
                        this.classList.replace("fa-eye", "fa-eye-slash"); // Changer l'icône
                    } else {
                        passwordInput.type = "password"; // Cacher le mot de passe
                        this.classList.replace("fa-eye-slash", "fa-eye"); // Revenir à l'icône d'origine
                    }
                });
            </script> --}}
            {{-- tmskhir hada --}}
            <br><br><br><br>
            <input type="submit" value="login">
        </form>
    </div>
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordInput = document.getElementById("password");
            let icon = this.querySelector("i");

            if (passwordInput.type === "password") {
                passwordInput.type = "text"; // Afficher le mot de passe
                icon.classList.replace("fa-eye", "fa-eye-slash"); // Changer l'icône
            } else {
                passwordInput.type = "password"; // Cacher le mot de passe
                icon.classList.replace("fa-eye-slash", "fa-eye"); // Revenir à l'icône d'origine
            }
        });
    </script>


</body>

</html>
