<x-base title="weekends">

    <x-slot:css>
        <link rel="stylesheet" href="/css/weekend.css">
    </x-slot>

    <div class="parent">
        <div class="companie">
            @if (!$isCC)
                <table>
                    <tr>
                        <td class="comp01">companie 01 <br>
                            <div class="list list01">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="comp02">companie 02 <br>
                            <div class="list list02">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="comp03">companie 03 <br>
                            <div class="list list03">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="comp04">companie 04 <br>
                            <div class="list list04">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="comp05">companie 05 <br>
                            <div class="list list05">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                        <td class="comp06">companie 06 <br>
                            <div class="list list06">
                                <ul>
                                    <li><a href="">01</a></li>
                                    <li><a href="">02</a></li>
                                    <li> <a href="">03</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            @else
                <table>
                    <tr>
                        {{-- hada css lazm ytrigle ki ykonno 9lmn 5 companie (chef companie) --}}
                        @foreach ($companies as $companie)
                            <td class="comp0{{ $companie }}">companie 0{{ $companie }} <br>
                                <div class="list list0{{ $companie }}">
                                    <ul>
                                        {{-- hadoma les href lazm ndir fihm une page vers la section seull (div b js) --}}
                                        <li><a href="">01</a></li>
                                        <li><a href="">02</a></li>

                                        @if ($companie != 6)
                                            <a href="">03</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>

        </div>
        @endforeach
        </tr>
        </table>
        @endif
    </div>
    <div class="choix">
        <table>
            <tr>
                <td><a class="choix01" href="weekend.html" style="color: deeppink;">liste generale</a></td>
                <td><a class="choix02" href="weekendvendredi.html">vendredi</a></td>
                <td><a class="choix03" href="weekendsamedi.html">samedi</a></td>
            </tr>
        </table>
    </div>
    <div class="marque">
        <table class="table">
            <tr>
                <th>matricule</th>
                <th>nom</th>
                <th>prenom</th>
                <th>section</th>
                <th>samedi</th>
                <th>vendredi</th>
                <th>48H</th>
            </tr>
            <tr>
                {{-- ndir for ala hdi --}}
                <form action="" method="post">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="radio" name="weekend"></td>
                    <td><input type="radio" name="weekend"></td>
                    <td><input type="radio" name="weekend"></td>
                </form>


                </form>
            </tr>
        </table>
    </div>
    </div>
</x-base>
