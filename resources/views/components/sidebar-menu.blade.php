@php

    use App\Models\Officer;

    use App\Models\User;

    $user = auth()->user();

    $officer = $user->isOfficer();
    $officerid = $officer->id;

@endphp


@if ($officer == null) {{-- ? un medcin :  --}}
    {{-- * Menu de rendez-vous avec sous-menu  --}}

    <div x-data="{ rdvOpen: false }" class="relative">
        <button @click="rdvOpen = !rdvOpen"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-calendar-check w-5 h-5"></i>
            <span class="ml-3">Rendez-vous</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': rdvOpen }"></i>
        </button>

        <div x-show="rdvOpen" class="pl-4 mt-1 space-y-1">
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
        </div>
    </div>

    {{-- Liste des patients --}}
    <a href="{{ route('patients.index') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-hospital-user w-5 h-5"></i>
        <span class="ml-3">Liste des patients</span>
    </a>

    {{-- Compte rendu médical --}}
    <a href="{{ route('compt') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-notes-medical w-5 h-5"></i>
        <span class="ml-3">Compte rendu médical</span>
    </a>

    {{-- Liste des convoqués --}}
    <a href="{{ route('liste_convoncu') }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-user-clock w-5 h-5"></i>
        <span class="ml-3">Liste des convoqués</span>
    </a>


    {{-- Menu de rendez-vous sous-menu des exemptions --}}
    <div x-data="{ rdvOpen: false }" class="relative">
        <button @click="rdvOpen = !rdvOpen"
            class="flex items-center w-full p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-user-shield w-5 h-5"></i>
            <span class="ml-3">Exemptions</span>
            <i class="fa-solid fa-chevron-down ml-auto" :class="{ 'rotate-180': rdvOpen }"></i>
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

    </div>
@else
    {{-- Principale --}}
    <a href="{{ route('principale', ['id' => $officerid]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-house w-5 h-5"></i>
        <span class="ml-3">Principale</span>
    </a>

    {{-- Paramètre --}}
    <a href="{{ route('parametre', ['id' => $officerid]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-gear w-5 h-5"></i>
        <span class="ml-3">Paramètre</span>
    </a>

    {{-- Consigné --}}
    <a href="{{ route('cons', ['id' => $officerid]) }}"
        class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
        <i class="fa-solid fa-user-lock w-5 h-5"></i>
        <span class="ml-3">Consigné</span>
    </a>

    @if ($officer->role->name == 'CC' || $officer->role->name == 'CBt')
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

                <a href="#"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-plus w-5 h-5"></i>
                    <span class="ml-3">Nouveau patient</span>
                </a>
                <a href="#"
                    class="flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-file-medical w-5 h-5"></i>
                    <span class="ml-3">Rapports médicaux</span>
                </a>
            </div>
        </div>

        <a href="{{ route('cons', ['id' => $officerid]) }}"
            class=" flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors orange-500">
            <i class="fa-solid fa-graduation-cap w-5 h-5"></i>
            <span class="ml-3">Étudiants</span>
        </a>

        {{-- ? Étudiants :m3mbalich wach hada --}}
        <a href="#"
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
    <form method="POST" action="{{ route('logout') }}" class="mt-auto">
        @csrf
        <button type="submit"
            class="w-full flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-sign-out-alt w-5 h-5"></i>
            <span class="ml-3">Déconnexion</span>
        </button>
    </form>
@endif
