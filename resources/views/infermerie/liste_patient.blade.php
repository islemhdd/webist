<x-infermerie css='liste_patient'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Liste des patients</h1>

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

                <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                    <form method="GET" action="{{ route('patients.index') }}" class="flex items-center space-x-4">
                        <label for="filter-validation" class="text-gray-700 dark:text-gray-300">Trier par :</label>
                        <select name="validation" id="filter-validation"
                            class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            onchange="this.form.submit()">
                            <option value="" {{ request('validation') === null ? 'selected' : '' }}>Tout</option>
                            <option value="1" {{ request('validation') === '1' ? 'selected' : '' }}>Validé</option>
                            <option value="0" {{ request('validation') === '0' ? 'selected' : '' }}>Non validé</option>
                            <option value="2" {{ request('validation') === '2' ? 'selected' : '' }}>Supprimé</option>
                        </select>

                        <label for="per-page" class="text-gray-700 dark:text-gray-300">Par page :</label>
                        <select name="per_page" id="per-page"
                            class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                            onchange="this.form.submit()">
                            <option value="15" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>

                    @if($patients->total() > 0)
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Total : <span class="font-medium">{{ $patients->total() }}</span> patients
                        </div>
                    @endif
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
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions</th>
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
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full badge {{ $patient->getValidationStatusClass() }}">
                                            {{ $patient->getValidationStatusText() }}
                                        </span>
                                        @if($patient->valider === 2 && $patient->motif_suppression)
                                            <div class="text-xs text-red-600 mt-1">
                                                Motif: {{ Str::limit($patient->motif_suppression, 30) }}
                                            </div>
                                        @endif
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
                        <div class="flex flex-1 justify-between sm:hidden">
                            @if($patients->onFirstPage())
                                <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                                    Précédent
                                </span>
                            @else
                                <a href="{{ $patients->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                    Précédent
                                </a>
                            @endif

                            @if($patients->hasMorePages())
                                <a href="{{ $patients->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                    Suivant
                                </a>
                            @else
                                <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                                    Suivant
                                </span>
                            @endif
                        </div>

                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Affichage de
                                    <span class="font-medium">{{ $patients->firstItem() }}</span>
                                    à
                                    <span class="font-medium">{{ $patients->lastItem() }}</span>
                                    sur
                                    <span class="font-medium">{{ $patients->total() }}</span>
                                    résultats
                                </p>
                            </div>
                            <div>
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    {{-- Bouton Précédent --}}
                                    @if($patients->onFirstPage())
                                        <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Précédent</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $patients->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Précédent</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Numéros de page --}}
                                    @foreach($patients->getUrlRange(1, $patients->lastPage()) as $page => $url)
                                        @if($page == $patients->currentPage())
                                            <span aria-current="page" class="relative z-10 inline-flex items-center bg-blue-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Bouton Suivant --}}
                                    @if($patients->hasMorePages())
                                        <a href="{{ $patients->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Suivant</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                            <span class="sr-only">Suivant</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Supprimer le patient</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="motif_suppression" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Motif de suppression *
                            </label>
                            <textarea name="motif_suppression" id="motif_suppression" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                placeholder="Veuillez indiquer le motif de suppression..."></textarea>
                        </div>
                        <div class="flex justify-center space-x-4">
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
    </div>

    <!-- Validation Modal -->
    <div id="validationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 text-center">Validation du patient</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="validationForm" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="avis_medecin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Diagnostic médical <span class="text-red-500">*</span>
                            </label>
                            <textarea name="avis_medecin" id="avis_medecin" rows="4" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                placeholder="Diagnostic médical obligatoire pour validation..."></textarea>

                            <!-- Add a hidden field to indicate this is NOT an AJAX request -->
                            <input type="hidden" name="is_ajax" value="0">
                        </div>
                        <div class="flex justify-center space-x-4">
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
    </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validation Modal Functions
        function openValidationModal(patientId) {
            document.getElementById('validationModal').classList.remove('hidden');
            const form = document.getElementById('validationForm');
            form.action = `/patients/${patientId}/validate-with-diagnosis`;

            // Ensure the form submits normally without AJAX
            form.onsubmit = function() {
                return true; // Allow normal form submission
            };
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

    <!-- Add custom CSS for badge colors -->
    <style>
        .badge-warning {
            background-color: #fbbf24;
            color: #92400e;
        }
        .badge-success {
            background-color: #34d399;
            color: #065f46;
        }
        .badge-danger {
            background-color: #f87171;
            color: #991b1b;
        }
        .badge-secondary {
            background-color: #9ca3af;
            color: #374151;
        }
    </style>
</x-infermerie>
