<x-base title="cons">
    <x-slot:css>
        <link rel="stylesheet" href="/css/cons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    </x-slot>

    <div class="parent">
        <div class="form">
            <h2>information èleve</h2>
            <form action="" method="post">
                <label for="nomm">nom</label><br>
                <input type="text" id="nomm" name="nomm"><br>
                <label for="prenomm">prenom</label><br>
                <input type="text" name="prenomm" id="prenomm"><br>
                <label for="nb">nombre </label><br>
                <input type="text" name="nb" id="nb"><br>
                <label for="cause">la cause</label><br>
                <input type="text" name="cause" id="cause"><br><br>
                <input type="submit" value="inserer" id="submit">
                <input type="submit" value="modefier" id="submit">
                <input type="reset" value="effacer" id="submit">
                <input type="reset" value="effacer tout" id="submit">
            </form>
            <form action="" class="searche">
                <br>
                <label for="search">Rechercher :</label>
                <input type="text" id="search" name="q" placeholder="Tapez votre recherche ici..." />
                <button type="submit">Rechercher</button>
            </form>
        </div>
        <h2>liste de consigne</h2>
        <div class="conss">
            <table>
                {{-- a dinamiser de la BDD --}}
                <tr>
                    <th>nom</th>
                    <th>prenom</th>
                    <th>nombre</th>
                    <th>la cause</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

    </div>

</x-base>
