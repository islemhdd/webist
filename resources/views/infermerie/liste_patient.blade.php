<x-infermerie css='liste_patient'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Liste des patients</h1>

                <div class="mb-6">
                    <form method="GET" action="{{ route('patients.index') }}" class="flex items-center space-x-4">
                        <label for="filter-validation" class="text-gray-700 dark:text-gray-300">Trier par :</label>
                        <select name="validation" id="filter-validation"
                            class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            onchange="this.form.submit()">
                            <option value="" {{ request('validation') === null ? 'selected' : '' }}>Tout</option>
                            <option value="1" {{ request('validation') === '1' ? 'selected' : '' }}>Validé</option>
                            <option value="0" {{ request('validation') === '0' ? 'selected' : '' }}>Non validé
                            </option>
                        </select>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
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
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    État</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $patient->matricule }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $patient->nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $patient->prenom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $patient->section_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($patient->valider)
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900">
                                                Validé ✓
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('patients.valider', $patient->id) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-200 dark:text-blue-900">
                                                    Valider
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-infermerie>
