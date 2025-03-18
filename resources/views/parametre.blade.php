<x-base title="parametre">
    <x-slot:css>
        <link rel="stylesheet" href="css/parametre.css">
    </x-slot>
    <div class="parent">
        <div class="partiehaut"><img src="profile.jpg" alt=""></div>
        <div class="partiebas">
            <div class="motdepasse">
                <h2> <i class="fa-solid fa-lock"></i> mot de passe</h2>
                <form action="" method="post">
                    <label for="amdp">l'ancien mot de passe</label><br>
                    <input type="password" name="amdp" id="amdp"><br>
                    <label for="nmdp">le nouveau mot de passe</label><br>
                    <input type="password" name="nmdp" id="nmdp"><br> <br>
                    <input type="submit" value="envoyer" class="submit">
                </form>
            </div>
            <div class="nomdutilisaer">
                <h2><i class="fa-solid fa-user"></i>nom de utilisateur</h2>
                <form action="">
                    <label for="andu">l'ancien nom de utilisateur</label><br>
                    <input type="text" name="andu" id="andu"><br>
                    <label for="nndu">le nouveau nom d'utilisateur</label><br>
                    <input type="text" name="nndu" id="nndu"><br><br>
                    <input type="submit" value="envoyer" class="submit">
                </form>
            </div>
            <div class="hm"><a href=""><i class="fa-solid fa-circle-info"></i>help</a><br> <br>
                <a href=""><i class="fa-solid fa-sun"></i>mode</a>
            </div>
        </div>

    </div>
</x-base>
