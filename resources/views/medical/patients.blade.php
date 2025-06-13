<x-infermerie css='liste_patient'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Header avec badge de spécialité -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            Mes patients - {{ $specialtyName }}
                        </h1>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas {{ $specialtyIcon }} w-4 h-4 mr-2"></i>
                                {{ $specialtyName }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $patients->total() }} patient(s) total
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Messages de succès et d'erreur -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Note d'information sur la spécialité -->
                <div class="mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                    Restriction de spécialité
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>• Vous ne voyez que les patients de votre spécialité : <strong>{{ $specialtyName }}</strong></p>
                                    <p>• Seuls les patients avec type_medecin correspondant sont affichés</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('medical.patients') }}" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Rechercher par nom, prénom ou matricule..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div>
                            <select name="status" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Tous les statuts</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Non validé</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Validé</option>
                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Supprimé</option>
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Filtrer
                        </button>
                    </form>
                </div>

                <!-- Table des patients -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Patient
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type médecin
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $patient->nom }} {{ $patient->prenom }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $patient->grade }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $patient->matricule }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $patient->section }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ $patient->type_medecin }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($patient->valider === 1)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <i class="fas fa-check-circle w-3 h-3 mr-1"></i>
                                                Validé
                                            </span>
                                        @elseif($patient->valider === 2)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                <i class="fas fa-times-circle w-3 h-3 mr-1"></i>
                                                Supprimé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                <i class="fas fa-clock w-3 h-3 mr-1"></i>
                                                En attente
                                            </span>
                                        @endif

                                        @if($patient->valider === 2 && $patient->motif_suppression)
                                            <div class="text-xs text-red-600 mt-1">
                                                Motif: {{ Str::limit($patient->motif_suppression, 30) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $patient->created_at ? $patient->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                        @if($patient->valider === 0)
                                            <!-- Actions seulement pour les patients non validés -->
                                            <button onclick="openValidationModal({{ $patient->id }})"
                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                                Valider
                                            </button>
                                            <button onclick="openDeleteModal({{ $patient->id }})"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                                Supprimer
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($patients->hasPages())
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6">
                        {{ $patients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals pour validation et suppression (identiques à la vue originale) -->
    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-center">Supprimer le patient</h3>
                <form id="deleteForm" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif de suppression</label>
                        <textarea id="motif_suppression" name="motif_suppression" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Entrez le motif de suppression..."></textarea>
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Validation Modal -->
    <div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-center">Validation du patient</h3>
                <form id="validationForm" method="POST">
                    @csrf
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Avis médical ({{ $specialtyName }})</label>
                        <textarea id="avis_medecin" name="avis_medecin" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Entrez votre avis médical..."></textarea>
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <button type="button" onclick="closeValidationModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
                            Valider Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validation Modal Functions
        function openValidationModal(patientId) {
            document.getElementById('validationModal').classList.remove('hidden');
            document.getElementById('validationForm').action = `/medical/patients/${patientId}/validate`;
        }

        function closeValidationModal() {
            document.getElementById('validationModal').classList.add('hidden');
            document.getElementById('avis_medecin').value = '';
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
</x-infermerie>
