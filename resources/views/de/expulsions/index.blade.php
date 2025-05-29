<x-de title="Exclusions de Classe - Direction d'Études">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                    <i class="fas fa-user-times text-red-500 mr-3"></i>
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

        <!-- Enhanced Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6 border border-gray-200 dark:border-gray-700">
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
                                    <div class="grade-btn bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-gray-800 dark:text-gray-200 font-semibold">Toutes</span>
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
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Nouvelle Exclusion
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
                        <tr>
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
    <div id="expulsionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" x-show="false" x-transition>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="expulsionForm" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">
                                    Nouvelle Exclusion
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Étudiant</label>
                                        <select name="student_id" id="student_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Sélectionner un étudiant</option>
                                            @foreach($students as $student)
                                            <option value="{{ $student->id }}">{{ $student->matricule }} - {{ $student->nom }} {{ $student->prenom }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="raison" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Raison de l'exclusion</label>
                                        <textarea name="raison" id="raison" rows="3" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Décrivez la raison de l'exclusion..."></textarea>
                                        <!-- Hidden date field that will be auto-filled -->
                                        <input type="hidden" name="date_expulsion" id="date_expulsion" value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Enregistrer
                        </button>
                        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
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

        // Modal functions
        function openAddModal() {
            document.getElementById('modal-title').textContent = 'Nouvelle Exclusion';
            document.getElementById('expulsionForm').action = '{{ route("de.expulsions.store") }}';
            document.getElementById('expulsionForm').method = 'POST';
            document.getElementById('expulsionModal').classList.remove('hidden');
            resetForm();
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

                    // Set student ID (using the student relationship from data)
                    if (data.student && data.student.id) {
                        document.getElementById('student_id').value = data.student.id;
                    }

                    // Set the reason (from either raison or motif_expulsion)
                    document.getElementById('raison').value = data.raison || data.motif_expulsion || '';

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
            document.getElementById('expulsionForm').reset();
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }
        }

        // Close modal when clicking outside
        document.getElementById('expulsionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
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
