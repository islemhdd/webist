<x-brigade css="consigne">

    <div class="overflow-hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Liste des Consignes</h2>
            <button onclick="document.getElementById('addConsigneModal').classList.remove('hidden')"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Ajouter une Consigne
            </button>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Matricule
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            motif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date Début
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date Fin
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($consignedStudents as $consigne)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $consigne->student->matricule }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $consigne->motif }}</div>
                            </td>




                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editConsigne({{ $consigne->id }})"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Modifier
                                </button>
                                <button onclick="deleteConsigne({{ $consigne->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    Supprimer
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Consigne Modal -->
    <div id="addConsigneModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Ajouter une Consigne</h3>
                <form id="addConsigneForm" class="mt-4">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="matricule">
                            Matricule
                        </label>
                        <input type="text" name="matricule" id="matricule" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Description
                        </label>
                        <textarea name="description" id="description" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="type">
                            Type
                        </label>
                        <select name="type" id="type" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="medical">Médical</option>
                            <option value="disciplinary">Disciplinaire</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">
                            Date Début
                        </label>
                        <input type="datetime-local" name="start_date" id="start_date" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">
                            Date Fin
                        </label>
                        <input type="datetime-local" name="end_date" id="end_date" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button"
                            onclick="document.getElementById('addConsigneModal').classList.add('hidden')"
                            class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="errorMessage" style="display: none;"
        class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-md">
    </div>

    <script>
        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 3000);
        }

        document.getElementById('addConsigneForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('/consignes', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const error = await response.json();
                    showError(error.message || 'Une erreur est survenue');
                }
            } catch (error) {
                showError('Une erreur est survenue');
            }
        });

        function editConsigne(id) {
            // Implement edit functionality
        }

        function deleteConsigne(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette consigne ?')) {
                fetch(`/consignes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    } else {
                        showError('Erreur lors de la suppression');
                    }
                }).catch(() => {
                    showError('Une erreur est survenue');
                });
            }
        }
    </script>
</x-brigade>
