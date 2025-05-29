<x-brigade css="list l'infermerie">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header with Add Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des Patients</h2>
                <button onclick=showAddPatientModal()
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
                                            Supprimé
                                        </span>
                                        @if ($patient->motif_suppression)
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                                Motif: {{ Str::limit($patient->motif_suppression, 30) }}
                                            </div>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endforeach
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
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Confirmer la suppression
                    </h3>
                    <button type="button" onclick="hideDeleteConfirmModal()"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-500 dark:text-gray-400 mb-6">Êtes-vous sûr de vouloir supprimer ce patient ?</p>

                <form id="deletePatientForm" action=" " method="POST" class="mt-6 flex justify-end space-x-3">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="matricule" id="deleteMatricule" value="">
                    <button type="button" onclick="hideDeleteConfirmModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                            hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600
                            dark:hover:bg-gray-600 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent
                            rounded-lg shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2
                            focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddPatientModal() {
            alert("ok");
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideAddPatientModal() {
            document.getElementById('addPatientModal').classList.add('hidden');
        }

        function deletePatient(id) {
            const row = document.querySelector(`tr[data-patient-id="${id}"]`);
            const matricule = row.querySelector('td').textContent.trim();

            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('deletePatientForm').action = `/delete-patient`;
            document.getElementById('deleteMatricule').value = matricule;
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

        // Add form submission handler for the add patient form only
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

        // Validation Modal Functions
        function showAddPatientModal() {
            const modal = document.getElementById('addPatientModal');
            if (modal) modal.classList.remove('hidden');
        }

        function hideAddPatientModal() {
            const modal = document.getElementById('addPatientModal');
            if (modal) {
                modal.classList.add('hidden');
                // Optionnel : reset les erreurs et le formulaire
                document.getElementById('errorMessage').style.display = 'none';
                document.getElementById('addPatientForm').reset();
            }
        }

        function openValidationModal(patientId) {
            const modal = document.getElementById('validationModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.getElementById('validationForm').action = `/patients/${patientId}/validate-with-diagnosis`;
            }
        }

        function closeValidationModal() {
            const modal = document.getElementById('validationModal');
            if (modal) {
                modal.classList.add('hidden');
                document.getElementById('avis_medecin').value = '';
            }
        }

        // Delete Modal Functions
        function openDeleteModal(patientId) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteForm').action = `/patients/${patientId}/soft-delete`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('motif_suppression').value = '';
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const deleteModal = document.getElementById('deleteModal');
            const validationModal = document.getElementById('validationModal');
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === validationModal) {
                closeValidationModal();
            }
        }
    </script>
</x-brigade>
