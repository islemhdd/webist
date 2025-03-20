<x-base title="infermerie">
    <x-slot:css>
        <link rel="stylesheet" href="/css/infermerie.css">

    </x-slot>


    <div class="parent">
        <div class="form">
            <h2>information èleve</h2>
            <form action="" method="post">
                {{-- <label for="nomm">nom</label><br>
                <input type="text" id="nomm" name="nomm"><br>
                <label for="prenomm">prenom</label><br>
                <input type="text" name="prenomm" id="prenomm"><br>
                <label for="medcin">medecin</label><br>
                <input type="text" name="medecin" id="medecin"><br> --}}
                <label for="matricule">nom</label><br>
                <input type="text" id="nomm" name="id">
                <label for="td">temps de depart</label><br>
                <input type="time" name="td" id="td">
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

        <h2>liste d'infermerie</h2>
        <div class="infermerie">
            <table>
                <tr>
                    <th>nom</th>
                    <th>prenom</th>
                    <th>médecin </th>
                    <th>temps de départ</th>
                    <th>temps d'arrivé </th>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
            </table>
        </div>
        <h2>liste d'hopital</h2>
        <div class="hopital">
            <table>
                <tr>
                    <th>nom</th>
                    <th>prenom</th>
                    <th>hopital </th>
                    <th>temps de départ</th>
                    <th>temps d'arrivé </th>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
                <tr>
                    <td></td>
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
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</x-base>
