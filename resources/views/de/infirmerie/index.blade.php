<x-de title="Absences de l'infirmerie - Direction d'Études">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Absences de l'infirmerie</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Gestion et validation des absences médicales</p>
        </div>

        <!-- Enhanced Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-6 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <!-- Left side - Search and Grade filters (Real-time) -->
                    <div class="flex-1 space-y-4">
                        <!-- Search Filter -->
                        <div>
                            <label for="search-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fas fa-search mr-1"></i>
                                Rechercher un élève
                            </label>
                            <input
                                type="text"
                                id="search-input"
                                name="matricule"
                                value="{{ request('matricule') ?? '' }}"
                                placeholder="Rechercher par matricule..."
                                class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                onkeyup="applyRealTimeFilters()"
                            >
                        </div>

                        <!-- Grade Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-graduation-cap mr-1"></i>
                                Année d'étude
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

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <i class="fas fa-check-circle mr-1"></i>
                                Statut
                            </label>
                            <select name="status" id="status-filter" class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200" onchange="applyRealTimeFilters()">
                                <option value="">Tous</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>En attente RHP</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Validé RHP</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Supprimé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Right side - Today's Data Display -->
                    <div class="lg:max-w-md">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center shadow-md">
                                        <i class="fas fa-calendar-day text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Données affichées</p>
                                    <p class="text-lg font-bold text-blue-900 dark:text-blue-100">Aujourd'hui uniquement</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">{{ now()->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Note informative -->
        <div class="mb-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                            Règles de validation RHP
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <p>• Les patients avec statut "Non validé par l'infirmerie" (rouge) ne peuvent pas être validés par le RHP</p>
                            <p>• Seuls les patients déjà validés par l'infirmerie peuvent recevoir une validation RHP</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="grid grid-cols-3 gap-6">
                    <!-- Total Patients Stat -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Patients</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- En attente RHP Stat -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En attente RHP</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pending'] ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Validé RHP Stat -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Validé RHP</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['validated'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden" id="patients-table-container">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Liste des Patients</h2>

                @if(isset($patients) && $patients->count() > 0)
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
                                        Statut Médical
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Statut RHP
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800" id="patients-tbody">
                                @foreach($patients as $patient)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 patient-row"
                                        data-matricule="{{ $patient->matricule ?? '' }}"
                                        data-grade="{{ $patient->student->section_id ?? $patient->student->grade ?? '' }}"
                                        data-status="{{ $patient->valider_rhp ?? 0 }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $patient->matricule }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $patient->student->nom ?? '' }} {{ $patient->student->prenom ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $patient->student->section_id ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($patient->valider == 1)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                                    Validé Médical
                                                </span>
                                            @elseif($patient->valider == 0)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                                    En attente
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                                    Supprimé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($patient->valider_rhp == 1)
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Validé RHP
                                                </span>
                                            @elseif(in_array($patient->valider, [1, 2]))
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    En attente RHP
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Non validé médical
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $patient->created_at ? $patient->created_at->format('d/m/Y H:i') : 'Non défini' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(in_array($patient->valider, [1, 2]) && $patient->valider_rhp == 0)
                                                <button onclick="validateRHP({{ $patient->id }})"
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Valider RHP
                                                </button>
                                            @elseif($patient->valider_rhp == 1)
                                                <span class="inline-flex items-center text-green-600 dark:text-green-400 text-xs">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Validé RHP
                                                </span>
                                            @elseif($patient->valider == 0)
                                                <span class="inline-flex items-center text-red-500 dark:text-red-400 text-xs">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Non validé par l'infirmerie
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-gray-400 text-xs">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    En attente validation RHP
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($patients) && $patients->hasPages())
                        <div class="mt-6">
                            {{ $patients->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-user-injured text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">Aucun patient trouvé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function validateRHP(patientId) {
            if (confirm('Êtes-vous sûr de vouloir valider cette absence pour RHP ?')) {
                fetch(`/de/infirmerie/validate/${patientId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload to see the changes
                    } else {
                        alert('Erreur lors de la validation: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            }
        }

        // Real-time filtering function
        function applyRealTimeFilters() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const selectedGrade = document.querySelector('input[name="grade_filter"]:checked').value;
            const selectedStatus = document.getElementById('status-filter').value;

            // Update hidden inputs for period form
            document.getElementById('hidden-matricule').value = document.getElementById('search-input').value;
            document.getElementById('hidden-grade').value = selectedGrade === 'all' ? '' : selectedGrade;
            document.getElementById('hidden-status').value = selectedStatus;

            // Filter table rows
            const rows = document.querySelectorAll('.patient-row');

            rows.forEach(row => {
                const matricule = row.dataset.matricule.toLowerCase();
                const grade = row.dataset.grade;
                const status = row.dataset.status;

                let matchesSearch = true;
                let matchesGrade = true;
                let matchesStatus = true;

                // Search filter
                if (searchValue && !matricule.includes(searchValue)) {
                    matchesSearch = false;
                }

                // Grade filter - improved logic
                if (selectedGrade !== 'all') {
                    // Check if grade contains the selected grade number or matches exactly
                    if (grade && grade.toString().includes(selectedGrade)) {
                        matchesGrade = true;
                    } else if (grade === selectedGrade) {
                        matchesGrade = true;
                    } else {
                        matchesGrade = false;
                    }
                }

                // Status filter
                if (selectedStatus !== '' && status !== selectedStatus) {
                    matchesStatus = false;
                }

                // Show/hide row based on all filters
                if (matchesSearch && matchesGrade && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Style updates for grade filter buttons
        function updateGradeButtonStyles() {
            const gradeRadios = document.querySelectorAll('input[name="grade_filter"]');

            gradeRadios.forEach(radio => {
                const btn = radio.parentNode.querySelector('.grade-btn');
                const span = btn.querySelector('span');
                if (radio.checked) {
                    // Active state
                    btn.classList.add('ring-2', 'ring-blue-500', 'transform', 'scale-105', 'shadow-lg');
                    btn.style.borderColor = '#3b82f6';
                    if (radio.value === 'all') {
                        btn.style.background = 'linear-gradient(to right, #dbeafe, #bfdbfe)';
                        span.style.color = '#1e40af';
                    } else if (radio.value === '1') {
                        btn.style.background = 'linear-gradient(to right, #dbeafe, #bfdbfe)';
                        span.style.color = '#1e40af';
                    } else if (radio.value === '2') {
                        btn.style.background = 'linear-gradient(to right, #dcfce7, #bbf7d0)';
                        span.style.color = '#166534';
                    } else if (radio.value === '3') {
                        btn.style.background = 'linear-gradient(to right, #e9d5ff, #d8b4fe)';
                        span.style.color = '#7c3aed';
                    }
                } else {
                    // Inactive state
                    btn.classList.remove('ring-2', 'ring-blue-500', 'transform', 'scale-105', 'shadow-lg');
                    btn.style.borderColor = '';
                    btn.style.background = '';
                    span.style.color = '';
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateGradeButtonStyles();

            // Add event listeners to grade filter buttons
            const gradeRadios = document.querySelectorAll('input[name="grade_filter"]');
            gradeRadios.forEach(radio => {
                radio.addEventListener('change', updateGradeButtonStyles);
            });
        });
    </script>

    <style>
        /* Enhanced grade button styles */
        .grade-btn {
            transition: all 0.2s ease-in-out;
        }

        .grade-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input[name="grade_filter"]:checked ~ .grade-btn {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }
    </style>
</x-de>
