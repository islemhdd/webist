<x-infermerie css='liste_convoncu'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-sky-400 dark:text-gray-200 mb-10 flex justify-center">Liste des convoqués</h1>

                <div class="mb-6">
                    <form method="GET" action="{{ route('liste_convoncu') }}" class="relative flex ">
                        <div class="flex items-center w-[95%]">
                            <input type="text" id="search" name="search" placeholder="Rechercher un étudiant..."
                                value="{{ request('search') }}"
                                class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100">
                            </div>
                            <div class="flex items-center justify-end bg-sky-500 hover:bg-sky-400">
                            <button type="submit"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white  dark:hover:text-gray-200 bg-sky-500 hover:bg-sky-400 rounded-full">
                                <i class="fa-solid fa-magnifying-glass m-3"></i>
                            </button></div>
                        
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr class="bg-sky-100">
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Matricule</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nom</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Prénom</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section</th>
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
                                        {{ $convoncu->section }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucun étudiant convoqué trouvé
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
