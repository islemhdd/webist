<x-brigade>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des Patients</h2>
                <button onclick="showAddModal()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Nouveau Patient</span>
                </button>
            </div>

            <!-- Simple Patient Table -->
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
                                Prenom</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->matricule }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->nom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $patient->prenom }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <button onclick="deletePatient('{{ $patient->id }}')"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Aucun patient trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-md mx-4">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100">Ajouter un Patient</h3>

                <form id="addPatientForm" action="{{ route('patient.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule</label>
                            <input type="text" name="matricule" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                        shadow-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                            <input type="text" name="nom" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                        shadow-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prenom</label>
                            <input type="text" name="prenom" required
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                        shadow-sm focus:ring-2 focus:ring-blue-500 dark:text-white">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="hideAddModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideAddModal() {
            document.getElementById('addPatientModal').classList.add('hidden');
        }

        function deletePatient(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce patient?')) {
                fetch(`/patient/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
            }
        }
    </script>
</x-brigade>
