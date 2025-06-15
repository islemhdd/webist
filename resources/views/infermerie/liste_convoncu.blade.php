<x-infermerie css='liste_convoncu'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">
                    Liste des convoqués
                    @if(isset($userRole) && $userRole !== 'Medecin')
                        <span class="text-sm font-normal text-blue-600 dark:text-blue-400">
                            @switch($userRole)
                                @case('Psychologue')
                                    (Étudiants sans diagnostic psychologique)
                                    @break
                                @case('Dentiste')
                                    (Étudiants sans diagnostic dentaire)
                                    @break
                                @case('Medecin general')
                                    (Étudiants sans diagnostic médecin général)
                                    @break
                            @endswitch
                        </span>
                    @endif
                </h1>

                <div class="mb-6">
                    <form method="GET" action="{{ route('liste_convoncu') }}" class="relative">
                        <div class="flex items-center">
                            <input type="text" id="search" name="search" placeholder="Rechercher un étudiant..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            <button type="submit"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Prénom
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section
                                </th>
                                @if(isset($userRole) && $userRole === 'Medecin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    État de convocation
                                </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                            @forelse($convoncus as $convoncu)
                                <tr onclick="window.location='{{ route('fiche.show', $convoncu->matricule) }}'"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $convoncu->matricule }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $convoncu->nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $convoncu->prenom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $convoncu->section_id }}
                                    </td>
                                    @if(isset($userRole) && $userRole === 'Medecin')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $completed = [];
                                                $pending = [];

                                                if (!empty($convoncu->psy)) $completed[] = 'Psy';
                                                else $pending[] = 'Psy';

                                                if (!empty($convoncu->medGen)) $completed[] = 'MédGen';
                                                else $pending[] = 'MédGen';

                                                if (!empty($convoncu->chirDent)) $completed[] = 'Dentiste';
                                                else $pending[] = 'Dentiste';

                                                if (!empty($convoncu->avisSpe)) $completed[] = 'AvisSpe';
                                                else $pending[] = 'AvisSpe';
                                            @endphp

                                            @foreach($completed as $spec)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fas fa-check mr-1"></i>{{ $spec }}
                                                </span>
                                            @endforeach

                                            @foreach($pending as $spec)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <i class="fas fa-clock mr-1"></i>{{ $spec }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ isset($userRole) && $userRole === 'Medecin' ? '5' : '4' }}" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        @if(isset($userRole) && $userRole !== 'Medecin')
                                            Aucun étudiant en attente de votre diagnostic
                                        @else
                                            Aucun étudiant convoqué trouvé
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($convoncus->hasPages())
                    <div class="mt-4">
                        {{ $convoncus->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-infermerie>
