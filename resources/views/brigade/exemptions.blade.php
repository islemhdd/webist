<x-brigade css="list l'infermerie">
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header with Add Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des exemptions</h2>
                <button onclick="showAddexemptionModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter exemption</span>
                </button>
            </div>

            <!-- exemptions Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Matricule</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nom et prenom</th>


                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                cause</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($exemptions as $exemption)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $exemption->student->matricule }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $exemption->student->nom }} {{ $exemption->student->prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $exemption->student->nom }} {{ $exemption->student->prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    {{ $exemption->motifs }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Error Message -->


    <!-- Add exemption Modal -->
    <div id="addexemptionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-md mx-4">
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        Ajouter un exemption
                    </h3>
                    <button onclick="hideAddexemptionModal()"
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
                <form id="addexemptionForm" action="{{ route('brigade.add_exemptions', ['id' => $officer->id]) }}"
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
                        <button type="button" onclick="hideAddexemptionModal()"
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

                <p class="text-gray-500 dark:text-gray-400 mb-6">Êtes-vous sûr de vouloir supprimer ce exemption ?</p>

                <form id="deleteexemptionForm" action=" " method="POST" class="mt-6 flex justify-end space-x-3">
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

    {{-- <script>
        function showAddexemptionModal() {
            document.getElementById('addexemptionModal').classList.remove('hidden');
        }

        function hideAddexemptionModal() {
            document.getElementById('addexemptionModal').classList.add('hidden');
        }

        function deleteexemption(id) {
            const row = document.querySelector(`tr[data-exemption-id="${id}"]`);
            const matricule = row.querySelector('td').textContent.trim();

            document.getElementById('deleteConfirmModal').classList.remove('hidden');
            document.getElementById('deleteexemptionForm').action = `/delete-exemption`;
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

        // Add form submission handler for the add exemption form only
        document.getElementById('addexemptionForm').addEventListener('submit', async function(e) {
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
        }); <
        script >
            // Validation Modal Functions
            function openValidationModal(exemptionId) {
                document.getElementById('validationModal').classList.remove('hidden');
                document.getElementById('validationForm').action = `/exemptions/${exemptionId}/validate-with-diagnosis`;
            }

        function closeValidationModal() {
            document.getElementById('validationModal').classList.add('hidden');
            document.getElementById('avis_medecin').value = '';
        }

        // Delete Modal Functions
        function openDeleteModal(exemptionId) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteForm').action = `/exemptions/${exemptionId}/soft-delete`;
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
    </script> --}}
</x-brigade>
