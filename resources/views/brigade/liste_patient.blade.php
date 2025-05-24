<x-brigade css="list l'infermerie">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header with Add Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des Patients</h2>
                <button onclick="showAddPatientModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter Patient</span>
                </button>
            </div>
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

            <!-- Patients Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Matricule</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Nom</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Section</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->matricule }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->student->nom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->student->section_id }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $patient->valider
                                            ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-300'
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-300' }}">
                                        {{ $patient->valider ? 'Validé' : 'En attente' }}
                                    </span>
                                </td>
                                @if (!$patient->valider)
                                    <td class="px-4 py-3 text-sm text-right space-x-3">
                                        <button onclick="deletePatient('{{ $patient->id }}')"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Aucun patient trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Error Message -->


    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-md mx-4">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Ajouter un Patient
                    </h3>
                    <button onclick="hideAddPatientModal()"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Error Message -->
                <div id="errorMessage" style="display: none"
                    class="mb-4
                       text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="text-sm font-medium">Matricule introuvable</span>
                </div>

                <!-- Form -->
                <form id="addPatientForm"
                    action="{{ route('brigade.list_patients.add_patients', ['id' => $officer->id]) }}" method="POST"
                    class="space-y-6">
                    @csrf
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Matricule de l'étudiant <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2">
                            <input type="text" name="matricule" id="matricule" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm
                                   focus:border-blue-500 focus:ring-blue-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                                placeholder="Entrez le matricule de l'étudiant">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Entrez le matricule de l'étudiant à ajouter à l'infirmerie
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="hideAddPatientModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white
                               border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50
                               dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600
                               dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600
                               border border-transparent rounded-lg shadow-sm hover:bg-blue-700
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                               transition-colors">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-sm mx-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirmer la suppression
                </h3>
                <p class="text-gray-500 dark:text-gray-400">Êtes-vous sûr de vouloir supprimer ce patient ?</p>

                <form id="deletePatientForm" method="POST" class="mt-6 flex justify-end space-x-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="hideDeleteConfirmModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddPatientModal() {
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideAddPatientModal() {
            document.getElementById('addPatientModal').classList.add('hidden');
        }

        function deletePatient(id) {
            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('deletePatientForm').action = `/patients/${id}`;
        }

        function hideDeleteConfirmModal() {
            document.getElementById('deleteConfirmModal').classList.add('hidden');
        }

        function showError() {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.style.display = 'flex';
            errorMessage.style.opacity = '1';

            setTimeout(() => {
                errorMessage.style.opacity = '0';
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 300);
            }, 3000);
        }

        // Add form submission handler
        document.getElementById('addPatientForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Success - reload the page to show updated list
                    window.location.reload();
                } else {
                    // Show error message
                    showError();
                }
            } catch (error) {
                // Show error message for network errors
                showError();
            }
        });
    </script>
</x-brigade>
