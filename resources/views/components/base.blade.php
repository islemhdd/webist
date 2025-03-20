{{-- the base component of the dashbord --}}
<!DOCTYPE html>
<html lang="en" mode="dark+-">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/logo.png">
    <link rel="stylesheet" href="/css/css/all.min.css">
    <title>wibist:{{ $title }}</title>
    {{ $css }}{{-- the title of the page --}}
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="/css/principale.css">

</head>

<body>

    <div class="side_bar">
        <img src="/logo.png" alt="">

        <div class="item1"><a href="{{ route('principale', compact('id')) }}"><i
                    class="fa-solid fa-house"></i>principale</a>
            {{-- {{ dd(1) }} --}}

        </div>
        <div class="item2"><a href="{{ route('parametre', compact('id')) }}"><i
                    class="fa-solid fa-gear"></i>paramètre</a></div>
        <div class="item3"><a href="{{ route('cons', compact('id')) }}"><i class="fa-solid fa-list-ul"></i>consigné</a>
        </div>
        @if ($id > 1)
            <div class="item4"><a href="{{ route('weekends', compact('id')) }}"><i
                        class="fa-solid fa-calendar-days"></i>week-end </a>
            </div>
            <div class="item5"><a href="{{ route('infermerie', compact('id')) }}"><i
                        class="fa-solid fa-heart"></i>infermerie</a></div>
            <div class="item6"><a href=""><i class="fa-solid fa-graduation-cap"></i>étudiants </a></div>
            <div class="item7"><a href="#hidden-div" class="clickable-div" id="toggleButton"><i
                        class="fa-solid fa-plus"></i>ordre</a></div>
            <div class="item1">
                <a href="{{ route('showLoginForm') }}"><i
                        class="fa-solid fa-arrow-right-from-bracket"></i>déconnexion</a>
            </div>
        @endif
        <div class="item1">
            <a href="{{ route('logout') }}"><i class="fa-solid fa-arrow-right-from-bracket"></i>déconnexion</a>
        </div>
    </div>
    <nav>

        <img src="/profile.jpg" alt="">
        <div class="nom_utilisateure">
            <p></p>
        </div>
        <a href=""><i class="fa-regular fa-sun"></i></a>
        <a href=""><i class="fa-solid fa-bell"></i></a>

    </nav>

    <script>
        //the pop up div to write an order
        document.addEventListener("DOMContentLoaded", function() {
            let button = document.getElementById("toggleButton");
            let hiddenDiv = document.getElementById("hidden-div");
            let closeButton = document.getElementById("closeButton");

            button.addEventListener("click", function(event) {
                event.preventDefault();
                hiddenDiv.style.display = "block";
                button.classList.add("active");
            });

            closeButton.addEventListener("click", function(event) {
                event.preventDefault();
                hiddenDiv.style.display = "none";
                button.classList.remove("active");
            });
        });
    </script>
    <div class="ordree follow-div" id="hidden-div">
        <a style=" position:relative; right:-80%;    background: transparent;
     border: none;
     font-size: 24px;
     cursor: pointer;
       text-decoration:none;
    color:white;"
            href="#" id="closeButton">✖</a>
        <div class="contenu">
            <p>À:
            <div class="ordree01"></div>
            </p>
            <hr>
            <p>Objet:
            <div class="ordree02"></div>
            </p>
            <hr>
            <div class="ordree03"></div>
        </div>
        <div class="envoi">
            <i class="fa-solid fa-paper-plane"></i>
        </div>
    </div>



    {{ $slot }}


</body>

</html>
