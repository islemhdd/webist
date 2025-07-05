@php

    use App\Models\Officer;

    use App\Models\User;

    $user = auth()->user();

    $officer = $user->isOfficer();
    if (!$user) {
        return redirect()->route('home');
    }

    if ($officer) {
        $officerid = $officer->id;
    } else {
        $officerid = $user->id;
    }



@endphp


@if ($officer == null && in_array($user->role->name, ['Medecin', 'Psychologue', 'Dentiste', 'Médecin général'])) {{-- ? Médecins de toutes spécialités --}}

    {{-- Dashboard spécialisé pour les médecins non-chef --}}
    @if(in_array($user->role->name, ['Psychologue', 'Dentiste', 'Médecin général']))
        <a href="{{ route('medical.dashboard') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-chart-bar w-5 h-5"></i>
            <span class="ml-3">Tableau de bord</span>
        </a>
    @endif

    {{-- * Menu de rendez-vous avec sous-menu (tous les médecins) --}}
    <div x-data="{ rdvOpen: false }" class="relative">
        <button @click="rdvOpen = !rdvOpen"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-calendar-check w-5 h-5"></i>
            <span class="ml-3">Rendez-vous</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': rdvOpen }"></i>
        </button>

        <div x-show="rdvOpen" class="pl-4 mt-1 space-y-1">
            {{-- Médecin chef a accès à tous les rendez-vous --}}
            @if($user->role->name === 'Medecin')
                <a href="{{ route('liste_rdv.create') }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-plus w-5 h-5"></i>
                    <span class="ml-3">Nouveau rendez-vous</span>
                </a>
                <a href="{{ route('liste_rdv.index') }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-list w-5 h-5"></i>
                    <span class="ml-3">Liste des rendez-vous</span>
                </a>
            {{-- Médecins spécialisés ont accès aux rendez-vous filtrés --}}
            @else
                <a href="{{ route('medical.appointments.create') }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-plus w-5 h-5"></i>
                    <span class="ml-3">Nouveau rendez-vous</span>
                </a>
                <a href="{{ route('medical.appointments') }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-list w-5 h-5"></i>
                    <span class="ml-3">Mes rendez-vous</span>
                </a>
            @endif
        </div>
    </div>

    {{-- Liste des patients (adaptée selon le rôle) --}}
    @if($user->role->name === 'Medecin')
        <a href="{{ route('patients.index') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-hospital-user w-5 h-5"></i>
            <span class="ml-3">Tous les patients</span>
        </a>
    @else
        <a href="{{ route('medical.patients') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-hospital-user w-5 h-5"></i>
            <span class="ml-3">Mes patients</span>
        </a>
    @endif

    {{-- Liste des convoqués (tous les médecins) --}}
    <a href="{{ route('liste_convoncu') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-user-clock w-5 h-5"></i>
        <span class="ml-3">Liste des convoqués</span>
    </a>

    {{-- Sections réservées au médecin chef uniquement --}}
    @if($user->role->name === 'Medecin')
        {{-- Tableau de bord médecin chef --}}
        <a href="{{ route('medical.dashboard') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-chart-bar w-5 h-5"></i>
            <span class="ml-3">Tableau de bord</span>
        </a>

        {{-- Compte rendu médical (médecin chef uniquement) --}}
        <a href="{{ route('compt') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-notes-medical w-5 h-5"></i>
            <span class="ml-3">Comptes Rendus Médicaux</span>
        </a>

        {{-- Menu des exemptions (médecin chef uniquement) --}}
        <div x-data="{ exemptionOpen: false }" class="relative">
            <button @click="exemptionOpen = !exemptionOpen"
                class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-user-shield w-5 h-5"></i>
                <span class="ml-3">Exemptions</span>
                <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': exemptionOpen }"></i>
            </button>

        <div x-show="rdvOpen" class="pl-4 mt-1 space-y-1">
            <a href="{{ route('exemptions.create') }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-plus w-5 h-5"></i>
                <span class="ml-3">Nouveau Exemption</span>
            </a>
            <a href="{{ route('exemptions.index') }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-list w-5 h-5"></i>
                <span class="ml-3">Liste des exemptions</span>
            </a>
        </div>
        {{-- Statistiques --}}

        <a href="{{ route('statistics.index') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-chart-pie w-5 h-5"></i>
            <span class="ml-3">Statistiques générales</span>
        </a>
    @else
        <a href="{{ route('medical.statistics') }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-chart-pie w-5 h-5"></i>
            <span class="ml-3">Mes statistiques</span>
        </a>
    @endif

    {{-- Affichage du type de médecin --}}
    @if($user->role->name !== 'Medecin')
        <div class="px-3 py-2 mt-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                    Connecté comme :
                </div>
                <div class="text-sm text-blue-800 dark:text-blue-300 font-semibold">
                    @switch($user->role->name)
                        @case('Psychologue')
                            Psychologue
                            @break
                        @case('Dentiste')
                            Dentiste
                            @break
                        @case('Médecin général')
                            Médecin Généraliste
                            @break
                    @endswitch
                </div>
            </div>
        </div>
    @endif

    {{-- Déconnexion --}}
    {{-- <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button type="submit"
            class="w-full flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-sign-out-alt w-5 h-5"></i>
            <span class="ml-3">Déconnexion</span>
        </button>
    </form> --}}
@elseif ($user->role->name == 'Directeur des etudes') {{-- ? Director of Studies :  --}}
    {{-- Dashboard --}}
    <a href="{{ route('de.dashboard') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-chart-bar w-5 h-5"></i>
        <span class="ml-3">Tableau de bord</span>
    </a>

    {{-- RHP Management --}}
    <a href="{{ route('de.rhp.index') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-calendar-alt w-5 h-5"></i>
        <span class="ml-3">Gestion RHP</span>
    </a>

    {{-- Absences Menu with dropdown --}}
    <div x-data="{ absencesOpen: false }" class="relative">
        <button @click="absencesOpen = !absencesOpen"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-user-injured w-5 h-5"></i>
            <span class="ml-3">Absences</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': absencesOpen }"></i>
        </button>

        <div x-show="absencesOpen" class="pl-4 mt-1 space-y-1">
            <a href="{{ route('de.infirmerie.index') }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-hospital w-5 h-5"></i>
                <span class="ml-3">Infirmerie</span>
            </a>
            <a href="{{ route('de.expulsions.index') }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-door-open w-5 h-5"></i>
                <span class="ml-3">Exclusions de classe</span>
            </a>
        </div>
    </div>
@else
    {{-- Statistiques --}}
    <a href="{{ route('brigade.statistics', ['id' => auth()->user()->id]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-chart-pie w-5 h-5"></i>
        <span class="ml-3">Statistiques</span>
    </a>

    {{-- Paramètre --}}
    <a href="{{ route('parametre', ['id' => $officerid]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-gear w-5 h-5"></i>
        <span class="ml-3">Paramètre</span>
    </a>

    {{-- Consigné --}}
    <a href="{{ route('sanctions.index', ['id' => $officerid]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-user-lock w-5 h-5"></i>
        <span class="ml-3">Sancions</span>
    </a>

    @if ($officer->role->name == 'Chef de compagnie' || $officer->role->name == 'Chef de batallaint')
        {{-- Week-end --}}
        <a href="{{ route('weekends', ['id' => $officerid]) }}"
            class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-calendar-days w-5 h-5"></i>
            <span class="ml-3">Week-end</span>
        </a>

        {{-- Infirmerie  --}}

        <div x-data="{ infirmerieOpen: false }" class="relative">
            <button @click="infirmerieOpen = !infirmerieOpen"
                class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-hospital w-5 h-5"></i>
                <span class="ml-3">Infirmerie</span>
                <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': infirmerieOpen }"></i>
            </button>
            <div x-show="infirmerieOpen" class="pl-4 mt-1 space-y-1">

                <a href="{{ route('brigade.list_patients', ['id' => $officerid]) }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-list w-5 h-5"></i>
                    <span class="ml-3">Liste des patients</span>
                </a>
                <a href="{{ route('brigade.rdv-list', ['id' => $officerid]) }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-calendar-check w-5 h-5"></i>
                    <span class="ml-3">Rendez-vous de demain</span>
                </a>
                <a href="{{ route('brigade.exemption-list', ['id' => $officerid]) }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-user-shield w-5 h-5"></i>
                    <span class="ml-3">Exemptions actives</span>
                </a>
                <a href="{{ route('brigade.convocation-list', ['id' => $officerid]) }}"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-user-clock w-5 h-5"></i>
                    <span class="ml-3">Convocations médicales</span>
                </a>

                {{-- <a href="#"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-plus w-5 h-5"></i>
                    <span class="ml-3">Nouveau patient</span>
                </a> --}}
                {{-- <a href="#"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-medical w-5 h-5"></i>
                    <span class="ml-3">Rapports médicaux</span>
                </a> --}}
            </div>
        </div>

        {{-- ? Étudiants :m3mbalich wach hada --}}
        <a href="{{ route('students.index') }}"
            class=" flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors orange-500">
            <i class="fa-solid fa-graduation-cap w-5 h-5"></i>
            <span class="ml-3">Étudiants</span>
        </a>
    @endif
    {{-- Ordre (with dropdow --}}
    {{-- <div x-data="{ ordreOpen: false }" class="relative">
        <button @click="ordreOpen = !ordreOpen" id="toggleButton"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-plus w-5 h-5"></i>
            <span class="ml-3">Ordre</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': ordreOpen }"></i>
        </button>
        <div x-show="ordreOpen" class="pl-4 mt-1 space-y-1">
          Add your ordre submtems here
        </div>
    </div> --}}

    {{-- Reports Dropdown --}}
    <div x-data="{ reportsOpen: false }" class="relative">
        <button @click="reportsOpen = !reportsOpen"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-file-lines w-5 h-5"></i>
            <span class="ml-3">Reports</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': reportsOpen }"></i>
        </button>

        <div x-show="reportsOpen" class="pl-4 mt-1 space-y-1">
            <a href="{{ route('report.index', ['id' => $officerid]) }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-pen w-5 h-5"></i>
                <span class="ml-3">Rapports créés</span>
            </a>
            <a href="{{ route('report.received', ['id' => $officerid]) }}"
                class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fa-solid fa-inbox w-5 h-5"></i>
                <span class="ml-3">Rapports reçus</span>
            </a>
        </div>
    </div>
    {{-- Déconnexion --}}
@endif
<form method="POST" action="{{ route('logout') }}" class="mt-auto">
    @csrf
    <button type="submit"
        class="w-full flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-sign-out-alt w-5 h-5"></i>
        <span class="ml-3">Déconnexion</span>
    </button>
</form>
