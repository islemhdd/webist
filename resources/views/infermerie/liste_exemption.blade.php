<x-infermerie css='liste_exemption'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
            <!-- Liste des exemptions -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <form method="GET" action="{{ route('exemptions.index') }}" class="mb-6 flex items-center gap-4">
                        <label class="text-sm text-gray-700 dark:text-gray-200">
                            Date début:
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="ml-2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </label>
                        <label class="text-sm text-gray-700 dark:text-gray-200">
                            Date fin:
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="ml-2 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </label>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Filtrer</button>
                        <a href="{{ route('exemptions.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">Réinitialiser</a>
                    </form>

                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Liste des exemptions</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matricule</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motif</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date début</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date fin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($exemptions as $exemption)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->matricule }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->motif }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->date_debut }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->date_fin }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-infermerie>
