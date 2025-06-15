<x-de title="Exclusions de Classe - Direction d'Études">
    <div class="min-h-screen bg-white dark:bg-gray-900 py-8 rounded-lg shadow-lg mt-8 px-8 ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8  ">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-sky-400 dark:text-white mb-2">
                    <i class="fas fa-user-times text-sky-600 mr-3"></i>
                    Exclusions de Classe
                </h1>
                <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Gestion et suivi des exclusions de classe des étudiants</p>
                <div class="w-24 h-1 bg-gradient-to-r from-red-500 to-orange-500 mx-auto mt-4 rounded-full"></div>
            </div>

        <!-- Enhanced Statistics Cards with Gradients and Hover Effects -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 justify-center">
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl shadow-lg p-6 border border-red-200 dark:border-red-800/30 hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-700 dark:text-red-300">Total Exclusions</p>
                        <p class="text-3xl font-bold text-red-900 dark:text-red-100">{{ $statistics['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl shadow-lg p-6 border border-blue-200 dark:border-blue-800/30 hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Première Année</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $statistics['grade_1'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl shadow-lg p-6 border border-green-200 dark:border-green-800/30 hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-700 dark:text-green-300">Deuxième Année</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $statistics['grade_2'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl shadow-lg p-6 border border-purple-200 dark:border-purple-800/30 hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Troisième Année</p>
                        <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $statistics['grade_3'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
 <div class=" flex justify-end"> <!-- New Expulsion Button -->
    <button type="button"
    onclick="openAddModal()"
    class="w-[20%] inline-flex items-center justify-center px-4 py-3  bg-sky-500  hover:bg-sky-400 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-600">
  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
  </svg>
  Nouvelle Exclusion
  </button></div>
        <!-- Enhanced Filters Section -->
        <div class="bg-white dark:bg-gray-800  my-6 ">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <!-- Left side - Search and Grade filters (Real-time) -->
                    <div class="flex-1 space-y-4">
                        <!-- Search Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-2 text-blue-500"></i>
                                Recherche en temps réel
                            </label>
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Rechercher par matricule ou nom..."
                                   onkeyup="applyRealTimeFilters()"
                                   class="block w-full px-4 py-3 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        </div>

                        <!-- Grade Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                Filtre par année (temps réel)
                            </label>
                            <div class="flex flex-wrap gap-2">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="all" class="sr-only grade-radio"
                                           {{ (request('grade') ?? 'all') === 'all' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 hover:from-blue-200 hover:to-blue-300 dark:hover:from-blue-800/50 dark:hover:to-blue-700/50 border-2 border-blue-300 dark:border-blue-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-blue-800 dark:text-blue-200 font-semibold">Toutes</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="1" class="sr-only grade-radio"
                                           {{ request('grade') === '1' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 hover:from-blue-200 hover:to-blue-300 dark:hover:from-blue-800/50 dark:hover:to-blue-700/50 border-2 border-blue-300 dark:border-blue-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-blue-800 dark:text-blue-200 font-semibold">1ère année</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="2" class="sr-only grade-radio"
                                           {{ request('grade') === '2' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 hover:from-green-200 hover:to-green-300 dark:hover:from-green-800/50 dark:hover:to-green-700/50 border-2 border-green-300 dark:border-green-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-green-800 dark:text-green-200 font-semibold">2ème année</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="3" class="sr-only grade-radio"
                                           {{ request('grade') === '3' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 hover:from-purple-200 hover:to-purple-300 dark:hover:from-purple-800/50 dark:hover:to-purple-700/50 border-2 border-purple-300 dark:border-purple-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-purple-800 dark:text-purple-200 font-semibold">3ème année</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right side - Today's Data Display and Actions -->
                    <div class="lg:w-80 space-y-4">
                        <!-- Today's Data Display -->
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                                        <i class="fas fa-calendar-day text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Données affichées</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">Aujourd'hui uniquement</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ now()->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- New Expulsion Button -->
                        <button type="button"
                                onclick="openAddModal()"
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-800 to-black hover:from-gray-700 hover:to-gray-900 text-red text-sm font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter une Exclusion
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Note informative -->
        <div class="mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Filtrage intelligent :</strong>
                            Utilisez la recherche et les filtres d'année pour un filtrage en temps réel,
                            et les filtres de période pour appliquer des plages de dates spécifiques.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Expulsions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    <i class="fas fa-list mr-2 text-blue-500"></i>
                    Liste des Exclusions
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr class="bg-sky-100">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Matricule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nom & Prénom
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Section
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Raison
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800" id="expulsionsTableBody">
                        @forelse($expulsions as $expulsion)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 expulsion-row transition-colors duration-200"
                            data-matricule="{{ $expulsion->student ? $expulsion->student->matricule : $expulsion->matricule }}"
                            data-grade="{{ $expulsion->student ? $expulsion->student->grade : '' }}"
                            data-name="{{ $expulsion->student ? strtolower($expulsion->student->nom . ' ' . $expulsion->student->prenom) : 'étudiant non trouvé' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ $expulsion->student ? $expulsion->student->matricule : $expulsion->matricule }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                {{ $expulsion->student ? ($expulsion->student->nom . ' ' . $expulsion->student->prenom) : 'Étudiant non trouvé' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                {{ $expulsion->student && $expulsion->student->section_id ? $expulsion->student->section_id : 'Non définie' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                    {{ $expulsion->raison ?? $expulsion->motif_expulsion ?? 'Non défini' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $expulsion->created_at ? $expulsion->created_at->format('d/m/Y H:i') : 'Non défini' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button onclick="editExpulsion({{ $expulsion->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-2 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/20 transition-colors duration-200"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button onclick="deleteExpulsion({{ $expulsion->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900/20 transition-colors duration-200"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr id="noDataRow">
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Aucune exclusion trouvée
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($expulsions->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $expulsions->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Expulsion Modal -->
    <div id="expulsionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form id="expulsionForm" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                        Nouvelle Exclusion
                                    </h3>
                                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full p-2 transition-all duration-200">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <!-- Recherche d'étudiant avec suggestions -->
                                    <div class="relative">
                                        <label for="student_search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-search mr-1 text-blue-500"></i>
                                            Rechercher un étudiant
                                        </label>
                                        <div class="relative">
                                            <input type="text"
                                                   id="student_search"                                   placeholder="Tapez le matricule, nom ou prénom..."
                                   oninput="searchStudentSuggestions(this.value)"
                                                   autocomplete="off"
                                                   class="block w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

                                            <!-- Hidden input to store selected student ID -->
                                            <input type="hidden" name="student_id" id="selected_student_id">

                                            <!-- Suggestions dropdown -->
                                            <div id="student_suggestions" class="absolute z-[2000] w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-xl hidden max-h-60 overflow-y-auto mt-1">
                                                <!-- Suggestions will be populated here -->
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Informations de l'étudiant -->
                                    <div id="student_info" class="hidden bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border">
                                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-user mr-1 text-green-500"></i>
                                            Informations de l'étudiant
                                        </h4>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="font-medium text-gray-600 dark:text-gray-400">Matricule:</span>
                                                <span id="info_matricule" class="text-gray-900 dark:text-white ml-1">-</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-600 dark:text-gray-400">Section:</span>
                                                <span id="info_section" class="text-gray-900 dark:text-white ml-1">-</span>
                                            </div>
                                            <div class="col-span-2">
                                                <span class="font-medium text-gray-600 dark:text-gray-400">Nom complet:</span>
                                                <span id="info_nom_complet" class="text-gray-900 dark:text-white ml-1">-</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Raison de l'exclusion -->
                                    <div>
                                        <label for="raison" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <i class="fas fa-exclamation-triangle mr-1 text-red-500"></i>
                                            Raison de l'exclusion
                                        </label>
                                        <select name="raison" id="raison" required
                                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Sélectionner une raison</option>
                                            <option value="Absence injustifiée">Absence injustifiée</option>
                                            <option value="Retard répété">Retard répété</option>
                                            <option value="Comportement perturbateur">Comportement perturbateur</option>
                                            <option value="Non-respect du règlement">Non-respect du règlement</option>
                                            <option value="Manque de respect envers l'enseignant">Manque de respect envers l'enseignant</option>
                                            <option value="Utilisation du téléphone en classe">Utilisation du téléphone en classe</option>
                                            <option value="Tenue vestimentaire inappropriée">Tenue vestimentaire inappropriée</option>
                                            <option value="Bavardage excessif">Bavardage excessif</option>
                                            <option value="Refus de travailler">Refus de travailler</option>
                                            <option value="Violence verbale">Violence verbale</option>
                                            <option value="Dégradation du matériel">Dégradation du matériel</option>
                                            <option value="Autre">Autre (préciser ci-dessous)</option>
                                        </select>

                                        <!-- Zone de texte pour "Autre" -->
                                        <textarea name="raison_autre" id="raison_autre"
                                                  class="mt-2 hidden block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                  rows="2"
                                                  placeholder="Précisez la raison..."></textarea>

                                        <!-- Hidden date field that will be auto-filled -->
                                        <input type="hidden" name="date_expulsion" id="date_expulsion" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 flex justify-center">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-red-600 via-red-700 to-red-800 hover:from-red-700 hover:via-red-800 hover:to-red-900 text-red text-base font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-red-300 focus:ring-opacity-50 border border-red-500">
                            <i class="fas fa-user-times mr-3 text-lg"></i>
                            <span class="tracking-wide">Exclure l'étudiant</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>    <style>
        /* Custom styles for modal and suggestions */
        #expulsionModal {
            z-index: 1050;
            backdrop-filter: blur(4px);
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                backdrop-filter: blur(4px);
            }
        }

        #expulsionModal .sm\:max-w-2xl {
            max-width: 48rem;
            margin: 2rem auto;
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        #student_suggestions {
            z-index: 2000;
            max-height: 280px;
            border-radius: 8px;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-top: 4px;
        }

        #student_suggestions .hover\:bg-gray-100:hover {
            background-color: #f3f4f6;
            transition: background-color 0.15s ease-in-out;
        }

        .dark #student_suggestions .hover\:bg-gray-100:hover {
            background-color: #4b5563;
        }

        #student_suggestions::-webkit-scrollbar {
            width: 6px;
        }

        #student_suggestions::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        #student_suggestions::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #student_suggestions::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .dark #student_suggestions::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark #student_suggestions::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark #student_suggestions::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Enhanced grade filter button styles */
        .grade-filter-btn {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 2px solid #e2e8f0;
            color: #64748b;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .grade-filter-btn:hover {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            border-color: #cbd5e1;
            color: #475569;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .grade-filter-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .dark .grade-filter-btn {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border-color: #4b5563;
            color: #d1d5db;
        }

        .dark .grade-filter-btn:hover {
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
            border-color: #6b7280;
            color: #f3f4f6;
        }

        .dark .grade-filter-btn.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-color: #1d4ed8;
            color: white;
        }
    </style>

    <script>
        // Students data for search functionality
        const studentsData = @json($students ?? []);
        console.log('✅ Students loaded:', studentsData?.length || 0, 'students');

        // Debug function - you can call this in browser console
        window.debugStudents = function() {
            console.log('=== 🔧 DEBUGGING STUDENTS DATA ===');
            console.log('studentsData exists:', typeof studentsData !== 'undefined');
            console.log('studentsData is array:', Array.isArray(studentsData));
            console.log('studentsData length:', studentsData ? studentsData.length : 'N/A');
            console.log('First 3 students:', studentsData ? studentsData.slice(0, 3) : 'No data');
            console.log('Sample student fields:', studentsData && studentsData[0] ? Object.keys(studentsData[0]) : 'No data');

            // Test search functionality
            console.log('\n=== 🧪 TESTING SEARCH FUNCTIONALITY ===');

            // Test 1: Search input element
            const searchInput = document.getElementById('student_search');
            console.log('Search input element:', searchInput);

            // Test 2: Suggestions div element
            const suggestionsDiv = document.getElementById('student_suggestions');
            console.log('Suggestions div element:', suggestionsDiv);

            // Test 3: Try manual search
            if (studentsData && studentsData.length > 0) {
                const testStudent = studentsData[0];
                console.log('Testing search with first student:', testStudent);
                if (testStudent.nom) {
                    const testTerm = testStudent.nom.substring(0, 3);
                    console.log('Calling searchStudentSuggestions with:', testTerm);
                    searchStudentSuggestions(testTerm);
                }

                // Test with matricule if available
                if (testStudent.matricule) {
                    const testMatricule = String(testStudent.matricule).substring(0, 4);
                    console.log('Testing with matricule:', testMatricule);
                    setTimeout(() => {
                        searchStudentSuggestions(testMatricule);
                    }, 1000);
                }
            }

            // Test 4: Check if modal is open
            const modal = document.getElementById('expulsionModal');
            console.log('Modal element:', modal);
            console.log('Modal is visible:', modal && !modal.classList.contains('hidden'));

            // Test 5: Try opening modal if not open
            if (modal && modal.classList.contains('hidden')) {
                console.log('🔥 OPENING MODAL FOR TESTING...');
                openAddModal();
                setTimeout(() => {
                    console.log('🔥 Modal should be open now. Testing input...');
                    const searchInput = document.getElementById('student_search');
                    if (searchInput) {
                        searchInput.value = '2022';
                        searchInput.dispatchEvent(new Event('input'));
                        console.log('🔥 Triggered input event programmatically');
                    }
                }, 500);
            }

            return studentsData;
        };

        // Real-time filtering function
        function applyRealTimeFilters() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedGrade = document.querySelector('input[name="grade_filter"]:checked')?.value || 'all';
            const rows = document.querySelectorAll('.expulsion-row');
            const noDataRow = document.getElementById('noDataRow');
            let visibleCount = 0;

            console.log('Filtering with:', { searchTerm, selectedGrade });

            rows.forEach(row => {
                const matricule = row.getAttribute('data-matricule')?.toLowerCase() || '';
                const name = row.getAttribute('data-name')?.toLowerCase() || '';
                const grade = row.getAttribute('data-grade') || '';

                console.log('Row data:', { matricule, name, grade });

                // Check search filter
                const matchesSearch = searchTerm === '' ||
                                    matricule.includes(searchTerm) ||
                                    name.includes(searchTerm);

                // Check grade filter - improved logic to handle various grade formats
                let matchesGrade = selectedGrade === 'all';
                if (!matchesGrade && grade) {
                    // Convert both to strings for comparison
                    const gradeStr = grade.toString().trim();
                    const selectedGradeStr = selectedGrade.toString().trim();

                    // Try exact match first
                    matchesGrade = gradeStr === selectedGradeStr;

                    // If no exact match, try to find the grade number within the string
                    if (!matchesGrade && selectedGradeStr !== 'all') {
                        // Check if the grade string contains the selected grade number
                        matchesGrade = gradeStr.includes(selectedGradeStr);
                    }
                }

                console.log('Matches:', { matchesSearch, matchesGrade });

                if (matchesSearch && matchesGrade) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            console.log('Visible count:', visibleCount);

            // Update grade button visual states
            updateGradeButtonStyles();

            // Show/hide no data message
            if (noDataRow) {
                if (visibleCount === 0 && rows.length > 0) {
                    noDataRow.style.display = '';
                    noDataRow.querySelector('td').innerHTML = `
                        <div class="flex flex-col items-center">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Aucune exclusion ne correspond aux critères de recherche
                        </div>
                    `;
                } else {
                    noDataRow.style.display = 'none';
                }
            }
        }

        // Update visual states of grade filter buttons
        function updateGradeButtonStyles() {
            const selectedGrade = document.querySelector('input[name="grade_filter"]:checked')?.value || 'all';

            document.querySelectorAll('.grade-btn').forEach(btn => {
                const radio = btn.parentElement.querySelector('input[type="radio"]');
                if (radio && radio.value === selectedGrade) {
                    // Active state - enhanced styling
                    btn.style.background = radio.value === 'all'
                        ? 'linear-gradient(135deg, #4b5563 0%, #374151 100%)'
                        : radio.value === '1'
                        ? 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)'
                        : radio.value === '2'
                        ? 'linear-gradient(135deg, #10b981 0%, #047857 100%)'
                        : 'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)';
                    btn.style.borderColor = radio.value === 'all'
                        ? '#374151'
                        : radio.value === '1'
                        ? '#1d4ed8'
                        : radio.value === '2'
                        ? '#047857'
                        : '#6d28d9';
                    btn.style.boxShadow = radio.value === 'all'
                        ? '0 4px 12px rgba(75, 85, 99, 0.4)'
                        : radio.value === '1'
                        ? '0 4px 12px rgba(59, 130, 246, 0.4)'
                        : radio.value === '2'
                        ? '0 4px 12px rgba(16, 185, 129, 0.4)'
                        : '0 4px 12px rgba(139, 92, 246, 0.4)';
                    btn.style.transform = 'translateY(-1px)';
                } else {
                    // Reset to default state
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.style.boxShadow = '';
                    btn.style.transform = '';
                }
            });
        }

        // Students data for suggestions
        // const studentsData = @json($students ?? []); // Déjà défini plus haut

        // Search student suggestions function
        function searchStudentSuggestions(searchTerm) {
            const suggestionsDiv = document.getElementById('student_suggestions');

            if (!suggestionsDiv) {
                console.error('❌ Suggestions div not found!');
                return;
            }

            if (!searchTerm || searchTerm.length < 2) {
                suggestionsDiv.classList.add('hidden');
                return;
            }

            if (!studentsData || !Array.isArray(studentsData)) {
                console.error('❌ Students data not available');
                suggestionsDiv.innerHTML = '<div class="p-3 text-sm text-red-600 dark:text-red-400">Erreur: Données des étudiants non disponibles</div>';
                suggestionsDiv.classList.remove('hidden');
                return;
            }

            const filteredStudents = studentsData.filter(student => {
                if (!student) return false;

                // Convert to string and handle null/undefined values safely
                const matricule = String(student.matricule || '').toLowerCase();
                const nom = String(student.nom || '').toLowerCase();
                const prenom = String(student.prenom || '').toLowerCase();
                const search = searchTerm.toLowerCase();

                return matricule.includes(search) ||
                       nom.includes(search) ||
                       prenom.includes(search);
            }).slice(0, 8); // Limite à 8 résultats

            if (filteredStudents.length === 0) {
                suggestionsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500 dark:text-gray-400">Aucun étudiant trouvé pour "' + searchTerm + '"</div>';
                suggestionsDiv.classList.remove('hidden');
                return;
            }

            let html = '';
            filteredStudents.forEach(student => {
                // Convert to string safely for template
                const matricule = String(student.matricule || '');
                const nom = String(student.nom || '');
                const prenom = String(student.prenom || '');
                const section = String(student.section_id || 'Non définie');

                html += `
                    <div class="cursor-pointer p-3 hover:bg-gray-100 dark:hover:bg-gray-600 border-b border-gray-200 dark:border-gray-600 last:border-b-0"
                         onclick="selectStudent(this)"
                         data-student-id="${matricule}"
                         data-matricule="${matricule}"
                         data-nom="${nom}"
                         data-prenom="${prenom}"
                         data-section="${section}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${nom} ${prenom}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    ${matricule} • ${section}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            suggestionsDiv.innerHTML = html;
            suggestionsDiv.classList.remove('hidden');
        }

        // Select student from suggestions
        function selectStudent(element) {
            try {
                const studentId = element.getAttribute('data-student-id');
                const matricule = element.getAttribute('data-matricule');
                const nom = element.getAttribute('data-nom');
                const prenom = element.getAttribute('data-prenom');
                const section = element.getAttribute('data-section');

                console.log('Selecting student:', { studentId, matricule, nom, prenom, section });

                if (!studentId) {
                    console.error('Student ID not found');
                    return;
                }

                // Update search input with selected student
                const searchInput = document.getElementById('student_search');
                if (searchInput) {
                    searchInput.value = `${matricule} - ${nom} ${prenom}`;
                }

                // Store selected student ID
                const selectedStudentIdInput = document.getElementById('selected_student_id');
                if (selectedStudentIdInput) {
                    selectedStudentIdInput.value = studentId;
                }

                // Update student info display
                const infoMatricule = document.getElementById('info_matricule');
                const infoSection = document.getElementById('info_section');
                const infoNomComplet = document.getElementById('info_nom_complet');
                const studentInfo = document.getElementById('student_info');

                if (infoMatricule) infoMatricule.textContent = matricule || '-';
                if (infoSection) infoSection.textContent = section || 'Non définie';
                if (infoNomComplet) infoNomComplet.textContent = `${nom} ${prenom}`;
                if (studentInfo) studentInfo.classList.remove('hidden');

                // Hide suggestions
                const suggestionsDiv = document.getElementById('student_suggestions');
                if (suggestionsDiv) {
                    suggestionsDiv.classList.add('hidden');
                }

                console.log('Student selected successfully');
            } catch (error) {
                console.error('Error selecting student:', error);
            }
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#student_search') && !e.target.closest('#student_suggestions')) {
                document.getElementById('student_suggestions').classList.add('hidden');
            }
        });

        // Student search function (legacy - kept for compatibility)
        function searchStudent(searchTerm) {
            // This function is now handled by searchStudentSuggestions
            searchStudentSuggestions(searchTerm);
        }

        // Load student information (updated for new system)
        function loadStudentInfo(studentId) {
            if (!studentId) {
                document.getElementById('student_info').classList.add('hidden');
                return;
            }

            const student = studentsData.find(s => s.id == studentId);
            if (student) {
                document.getElementById('info_matricule').textContent = student.matricule || '-';
                document.getElementById('info_section').textContent = student.section_id || 'Non définie';
                document.getElementById('info_nom_complet').textContent = `${student.nom} ${student.prenom}` || '-';
                document.getElementById('student_info').classList.remove('hidden');
            } else {
                document.getElementById('student_info').classList.add('hidden');
            }
        }

        // Handle "Autre" option in reasons
        document.addEventListener('DOMContentLoaded', function() {
            const raisonSelect = document.getElementById('raison');
            const raisonAutre = document.getElementById('raison_autre');

            if (raisonSelect && raisonAutre) {
                raisonSelect.addEventListener('change', function() {
                    if (this.value === 'Autre') {
                        raisonAutre.classList.remove('hidden');
                        raisonAutre.required = true;
                    } else {
                        raisonAutre.classList.add('hidden');
                        raisonAutre.required = false;
                        raisonAutre.value = '';
                    }
                });
            }
        });

        // Modal functions
        function openAddModal() {
            try {
                console.log('Attempting to open modal...');

                const modal = document.getElementById('expulsionModal');
                const title = document.getElementById('modal-title');
                const form = document.getElementById('expulsionForm');

                console.log('Modal element:', modal);
                console.log('Title element:', title);
                console.log('Form element:', form);

                if (!modal) {
                    console.error('Modal not found!');
                    return;
                }

                if (title) {
                    title.textContent = 'Nouvelle Exclusion';
                }

                if (form) {
                    form.action = '/de/expulsions';
                    form.method = 'POST';
                }

                modal.classList.remove('hidden');
                resetForm();
                console.log('Modal opened successfully');
            } catch (error) {
                console.error('Error opening modal:', error);
                alert('Erreur lors de l\'ouverture du modal: ' + error.message);
            }
        }

        function editExpulsion(id) {
            // Fetch expulsion data and populate form
            fetch(`/de/expulsions/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modal-title').textContent = 'Modifier Exclusion';
                    document.getElementById('expulsionForm').action = `/de/expulsions/${id}`;

                    // Add method spoofing for PUT
                    let methodInput = document.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        document.getElementById('expulsionForm').appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    // Set student information
                    if (data.student && data.student.matricule) {
                        const student = data.student;

                        // Update search input with selected student
                        document.getElementById('student_search').value = `${student.matricule} - ${student.nom} ${student.prenom}`;

                        // Store selected student matricule (not id)
                        document.getElementById('selected_student_id').value = student.matricule;

                        // Update student info display
                        document.getElementById('info_matricule').textContent = student.matricule || '-';
                        document.getElementById('info_section').textContent = student.section_id || 'Non définie';
                        document.getElementById('info_nom_complet').textContent = `${student.nom} ${student.prenom}` || '-';
                        document.getElementById('student_info').classList.remove('hidden');
                    }

                    // Set the reason (from either raison or motif_expulsion)
                    const reason = data.raison || data.motif_expulsion || '';
                    const raisonSelect = document.getElementById('raison');
                    const raisonAutre = document.getElementById('raison_autre');

                    // Check if reason exists in dropdown
                    let reasonFound = false;
                    for (let option of raisonSelect.options) {
                        if (option.value === reason) {
                            raisonSelect.value = reason;
                            reasonFound = true;
                            break;
                        }
                    }

                    // If reason not found in dropdown, set as "Autre"
                    if (!reasonFound && reason) {
                        raisonSelect.value = 'Autre';
                        raisonAutre.classList.remove('hidden');
                        raisonAutre.required = true;
                        raisonAutre.value = reason;
                    }

                    document.getElementById('expulsionModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des données:', error);
                    alert('Erreur lors du chargement des données de l\'exclusion');
                });
        }

        function deleteExpulsion(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette exclusion ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/de/expulsions/${id}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function closeModal() {
            document.getElementById('expulsionModal').classList.add('hidden');
            resetForm();
        }

        function resetForm() {
            try {
                console.log('Resetting form...');

                // Reset the form
                const form = document.getElementById('expulsionForm');
                if (form) {
                    form.reset();
                    form.action = '/de/expulsions';
                    form.method = 'POST';
                }

                // Clear student search and selection
                const searchInput = document.getElementById('student_search');
                const selectedStudentId = document.getElementById('selected_student_id');
                const studentInfo = document.getElementById('student_info');
                const suggestions = document.getElementById('student_suggestions');

                if (searchInput) searchInput.value = '';
                if (selectedStudentId) selectedStudentId.value = '';
                if (studentInfo) studentInfo.classList.add('hidden');
                if (suggestions) suggestions.classList.add('hidden');

                // Hide "Autre" reason text area
                const raisonAutre = document.getElementById('raison_autre');
                if (raisonAutre) {
                    raisonAutre.classList.add('hidden');
                    raisonAutre.required = false;
                    raisonAutre.value = '';
                }

                // Remove method input if exists
                const methodInput = document.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }

                // Reset modal title
                const modalTitle = document.getElementById('modal-title');
                if (modalTitle) {
                    modalTitle.textContent = 'Nouvelle Exclusion';
                }

                console.log('Form reset successfully');
            } catch (error) {
                console.error('Error resetting form:', error);
            }
        }

        // Form validation
        document.getElementById('expulsionForm').addEventListener('submit', function(e) {
            const selectedStudentId = document.getElementById('selected_student_id').value;
            const raisonSelect = document.getElementById('raison');
            const raisonAutre = document.getElementById('raison_autre');

            // Validate student selection
            if (!selectedStudentId) {
                e.preventDefault();
                alert('Veuillez sélectionner un étudiant avant de soumettre le formulaire.');
                document.getElementById('student_search').focus();
                return false;
            }

            // Validate reason
            if (!raisonSelect.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une raison pour l\'exclusion.');
                raisonSelect.focus();
                return false;
            }

            // Validate "Autre" reason if selected
            if (raisonSelect.value === 'Autre' && !raisonAutre.value.trim()) {
                e.preventDefault();
                alert('Veuillez préciser la raison de l\'exclusion.');
                raisonAutre.focus();
                return false;
            }

            return true;
        });

        // Close modal when clicking outside
        document.getElementById('expulsionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('expulsionModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize grade filter visual states
            updateGradeButtonStyles();
            // Apply initial filtering
            applyRealTimeFilters();
        });
    </script>
        </div>
    </div>
</x-de>
