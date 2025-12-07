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
    {{-- ? Étudiants :m3mbalich wach hada --}}
    <a href="{{ route('students.index') }}"
        class=" flex items-center p-3 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors orange-500">
        <i class="fa-solid fa-graduation-cap w-5 h-5"></i>
        <span class="ml-3">Étudiants</span>
    </a>
@endif

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
