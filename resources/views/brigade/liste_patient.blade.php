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
                    <label for="filter-validation" class="text-gray-700 dark:text-gray-300">Trier par:</label>
                    <select name="validation" id="filter-validation"
                        class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                        onchange="this.form.submit()">
                        <option value="" {{ request('validation') === null ? 'selected' : '' }}>Tout</option>
                        <option value="1" {{ request('validation') === '1' ? 'selected' : '' }}>Validé</option>
                        <option value="0" {{ request('validation') === '0' ? 'selected' : '' }}>Non validé</option>
                        <option value="2" {{ request('validation') === '2' ? 'selected' : '' }}>Refusé</option>
                    </select>
                </form>
            </div>

            <!-- Patients Table -->
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
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($patients as $patient)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700" data-patient-id="{{ $patient->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $patient->student->matricule }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $patient->student->nom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $patient->student->prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $patient->student->section_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($patient->valider === 1)
                                        <span
                                            class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                            Validé
                                        </span>
                                    @elseif($patient->valider === 0)
                                        <span
                                            class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                            En attente
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                            Refusé
                                        </span>
                                        @if ($patient->motif_suppression)
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                Motif: {{ Str::limit($patient->motif_suppression, 30) }}
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openDeleteModal({{ $patient->id }})"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300
                                               p-2 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-200"
                                        title="Supprimer le patient">
                                        <i class="fas fa-trash-alt text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

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
                    class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800
                           text-red-800 dark:text-red-200 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span class="text-sm font-medium">Matricule introuvable</span>
                </div>

                <!-- Form -->
                <form id="addPatientForm" action="{{ route('brigade.add_patients', ['id' => $officer->id]) }}"
                    method="POST" class="space-y-6">
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

                    <!-- Type de médecin -->
                    <div>
                        <label for="type_medecin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Type de médecin <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-2">
                            <select name="type_medecin" id="type_medecin" required
                                class="block w-full rounded-lg border-gray-300 shadow-sm
                                   focus:border-blue-500 focus:ring-blue-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                                <option value="">Sélectionnez le type de médecin</option>
                                <option value="médecin générale">Médecin Générale</option>
                                <option value="psycho">Psychologue</option>
                                <option value="dentiste">Dentiste</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Choisissez le type de médecin que l'étudiant doit consulter
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

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <i class="fas fa-trash-alt text-red-500"></i>
                        Supprimer le Patient
                    </h3>
                    <button type="button" onclick="closeDeleteModal()"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="flex items-center gap-3 mb-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    <p class="text-gray-700 dark:text-gray-300">
                        Êtes-vous sûr de vouloir supprimer ce patient définitivement ?
                    </p>
                </div>

                <form id="deleteForm" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')
                    <div>
                        <label for="motif_suppression"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Motif de suppression <span class="text-red-500">*</span>
                        </label>
                        <textarea name="motif_suppression" id="motif_suppression" rows="3" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm
                                   focus:border-red-500 focus:ring-red-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Entrez le motif de suppression..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300
                                   rounded-lg shadow-sm hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300
                                   dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent
                                   rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2
                                   focus:ring-offset-2 focus:ring-red-500 transition-colors flex items-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Add Patient Modal Functions
        function showAddPatientModal() {
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideAddPatientModal() {
            document.getElementById('addPatientModal').classList.add('hidden');
            document.getElementById('errorMessage').style.display = 'none';
            document.getElementById('addPatientForm').reset();
        }

        // Delete Modal Functions
        function openDeleteModal(patientId) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteForm').action = `/patients/${patientId}/delete`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('motif_suppression').value = '';
        }

        // Error message display
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

        // Add form submission handler for the add patient form
        document.getElementById('addPatientForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    // Success - reload the page to show updated list
                    window.location.reload();
                } else {
                    // Show error message
                    showError();
                }
            } catch (error) {
                // Show error message for network errors
                console.error('Error:', error);
                showError();
            }
        });

        // Close modals when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addPatientModal');
            const deleteModal = document.getElementById('deleteModal');

            if (event.target === addModal) {
                hideAddPatientModal();
            }
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        }

        // Close modals on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideAddPatientModal();
                closeDeleteModal();
            }
        });
    </script>
</x-brigade>
