<x-de title="Tableau de bord - Direction d'Études">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-lg mt-6  p-6 ">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-sky-400 pt-8 ">Tableau de bord</h1>
           
                <div class=" rounded-lg  text-center w-full flex justify-end pr-8">
                    <p class="text-sm font-medium text-gray-600 ">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ date('d/m/Y') }}
                    </p>
                   
                </div>
            
           
        </div>

        <!-- Enhanced Filters Section -->
        <div class="bg-white dark:bg-gray-800  my-6  ">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Left side - Search and Grade filters (Real-time) -->
                    <div class="flex-1 space-y-4">
                        <!-- Search Filter -->
                      
                            <div class="mb-6 w-full">
                                <form method="GET" action="{{ route('liste_convoncu') }}" class="relative flex ">
                                    <div class="flex items-center w-[95%]">
                                        <input type="text" id="search" name="search" placeholder="Rechercher un étudiant..."
                                            value="{{ request('search') }}"
                                            class="w-full px-4 py-2 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100">
                                        </div>
                                        <div class="flex items-center justify-end bg-sky-500 hover:bg-sky-400">
                                        <button type="submit"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-white  dark:hover:text-gray-200 bg-sky-500 hover:bg-sky-400 rounded-full">
                                            <i class="fa-solid fa-magnifying-glass m-3"></i>
                                        </button></div>
                                    
                                </form>
                            </div>

                        <!-- Grade Filter -->
                        <div>
                            
                            <div class="flex flex-wrap gap-2 justify-around mt-8">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="all" class="sr-only grade-radio"
                                           {{ ($grade ?? 'all') === 'all' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 hover:from-gray-200 hover:to-gray-300 dark:hover:from-gray-600 dark:hover:to-gray-500 border-2 border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-gray-800 dark:text-gray-200 font-semibold">Toutes</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="1" class="sr-only grade-radio"
                                           {{ $grade === '1' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 hover:from-blue-200 hover:to-blue-300 dark:hover:from-blue-800/50 dark:hover:to-blue-700/50 border-2 border-blue-300 dark:border-blue-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-blue-800 dark:text-blue-200 font-semibold">1ère année</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="2" class="sr-only grade-radio"
                                           {{ $grade === '2' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 hover:from-green-200 hover:to-green-300 dark:hover:from-green-800/50 dark:hover:to-green-700/50 border-2 border-green-300 dark:border-green-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-green-800 dark:text-green-200 font-semibold">2ème année</span>
                                    </div>
                                </label>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="3" class="sr-only grade-radio"
                                           {{ $grade === '3' ? 'checked' : '' }} onchange="applyRealTimeFilters()">
                                    <div class="grade-btn bg-gradient-to-r from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 hover:from-purple-200 hover:to-purple-300 dark:hover:from-purple-800/50 dark:hover:to-purple-700/50 border-2 border-purple-300 dark:border-purple-600 rounded-lg px-4 py-2 transition-all duration-200 text-sm font-medium">
                                        <span class="text-purple-800 dark:text-purple-200 font-semibold">3ème année</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                 
                    
                </div>
            </div>
        </div>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students in Infirmary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden  rounded-xl hover:shadow-lg hover:relative bottom-[8%] shadow-md transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hospital text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Infirmerie</h3>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400" data-stat="total-infirmerie">{{ $stats['total_infirmerie'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Étudiants présents</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RHP Validated -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden  rounded-xl hover:shadow-lg hover:relative bottom-[8%] shadow-md transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center ">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Validées RHP</h3>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400" data-stat="rhp-validated">{{ $stats['rhp_validated'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Validations confirmées</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending RHP Validation -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl hover:shadow-lg hover:relative bottom-[8%] shadow-md transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">En attente RHP</h3>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400" data-stat="rhp-pending">{{ $stats['rhp_pending'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Validations requises</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Expulsions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden  rounded-xl hover:shadow-lg hover:relative bottom-[8%] shadow-md transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                <i class="fas fa-door-open text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Exclusions</h3>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400" data-stat="total-expulsions">{{ $stats['total_expulsions'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Ce mois</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Infirmary RHP Validation Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Validations RHP - Infirmerie</h3>
                <div class="relative h-64">
                    @if($stats['rhp_validated'] + $stats['rhp_pending'] > 0)
                        <canvas id="rhpValidationChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-chart-pie text-4xl mb-2 opacity-50"></i>
                                <div>Aucune donnée disponible</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Expulsions by Reason Chart -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Exclusions par motif</h3>
                <div class="relative h-64">
                    @if(count($stats['expulsions_by_reason']) > 0)
                        <canvas id="expulsionsChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-chart-bar text-4xl mb-2 opacity-50"></i>
                                <div>Aucune exclusion pour cette période</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>

    <!-- Charts JavaScript -->
    <script>
        // Variables globales pour stocker les graphiques
        let rhpChart = null;
        let expulsionsChart = null;

        // Wait for DOM to be fully loaded and Chart.js to be available
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded!');
                return;
            }

            console.log('Chart.js loaded successfully');

            // Initialiser les graphiques avec les données actuelles
            initializeCharts();
        });

        function initializeCharts() {
            // Debug: Log the data being passed to charts
            console.log('RHP Data:', [{{ $stats['rhp_validated'] }}, {{ $stats['rhp_pending'] }}]);
            console.log('Expulsions Data:', {!! json_encode($stats['expulsions_by_reason']) !!});

            // RHP Validation Pie Chart
            const rhpCanvas = document.getElementById('rhpValidationChart');
            if (rhpCanvas) {
                try {
                    const rhpCtx = rhpCanvas.getContext('2d');
                    const rhpValidated = {{ $stats['rhp_validated'] }};
                    const rhpPending = {{ $stats['rhp_pending'] }};

                    if (rhpValidated + rhpPending > 0) {
                        // Détruire le graphique existant s'il existe
                        if (rhpChart) {
                            rhpChart.destroy();
                        }

                        rhpChart = new Chart(rhpCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Validées', 'En attente'],
                                datasets: [{
                                    data: [rhpValidated, rhpPending],
                                    backgroundColor: [
                                        '#10B981', // Green for validated
                                        '#F59E0B'  // Yellow for pending
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true,
                                            color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                        }
                                    }
                                }
                            }
                        });
                        console.log('RHP Chart created successfully');
                    } else {
                        console.log('No RHP data available for chart');
                    }
                } catch (error) {
                    console.error('Error creating RHP chart:', error);
                }
            }

            // Expulsions Bar Chart
            const expulsionsCanvas = document.getElementById('expulsionsChart');
            if (expulsionsCanvas) {
                try {
                    const expulsionsCtx = expulsionsCanvas.getContext('2d');
                    const expulsionsLabels = {!! json_encode(array_keys($stats['expulsions_by_reason'])) !!};
                    const expulsionsData = {!! json_encode(array_values($stats['expulsions_by_reason'])) !!};

                    console.log('Expulsions Labels:', expulsionsLabels);
                    console.log('Expulsions Values:', expulsionsData);

                    if (expulsionsLabels.length > 0 && expulsionsData.length > 0) {
                        // Détruire le graphique existant s'il existe
                        if (expulsionsChart) {
                            expulsionsChart.destroy();
                        }

                        expulsionsChart = new Chart(expulsionsCtx, {
                            type: 'bar',
                            data: {
                                labels: expulsionsLabels,
                                datasets: [{
                                    label: 'Nombre d\'exclusions',
                                    data: expulsionsData,
                                    backgroundColor: '#EF4444',
                                    borderColor: '#DC2626',
                                    borderWidth: 1,
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                        },
                                        grid: {
                                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                        },
                                        grid: {
                                            color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                                        }
                                    }
                                }
                            }
                        });
                        console.log('Expulsions Chart created successfully');
                    } else {
                        console.log('No expulsions data available for chart');
                    }
                } catch (error) {
                    console.error('Error creating Expulsions chart:', error);
                }
            }
        }

        // Fonction pour mettre à jour les graphiques avec de nouvelles données
        function updateCharts(data) {
            console.log('Updating charts with new data:', data);

            // Debug data structure
            console.log('Infirmary data:', data.infirmary);
            console.log('Expulsions data:', data.expulsions);
            console.log('Total expulsions:', data.total_expulsions);

            // Update statistics cards using the new function
            updateStatisticsCards(data);

            // Mettre à jour le graphique RHP
            const rhpCanvas = document.getElementById('rhpValidationChart');
            if (rhpCanvas && data.infirmary.validated + data.infirmary.non_validated > 0) {
                if (rhpChart) {
                    rhpChart.destroy();
                }

                const rhpCtx = rhpCanvas.getContext('2d');
                rhpChart = new Chart(rhpCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Validées', 'En attente'],
                        datasets: [{
                            data: [data.infirmary.validated, data.infirmary.non_validated],
                            backgroundColor: ['#10B981', '#F59E0B'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                    color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                }
                            }
                        }
                    }
                });
            } else if (rhpChart) {
                rhpChart.destroy();
                rhpChart = null;
                // Afficher le message "Aucune donnée"
                const rhpContainer = rhpCanvas.parentElement;
                rhpContainer.innerHTML = `
                    <div class="flex items-center justify-center h-48">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-chart-pie text-4xl mb-2 opacity-50"></i>
                            <div>Aucune donnée disponible</div>
                        </div>
                    </div>
                `;
            }

            // Mettre à jour le graphique des expulsions
            const expulsionsCanvas = document.getElementById('expulsionsChart');
            if (expulsionsCanvas && Object.keys(data.expulsions).length > 0) {
                if (expulsionsChart) {
                    expulsionsChart.destroy();
                }

                const expulsionsCtx = expulsionsCanvas.getContext('2d');
                const labels = Object.keys(data.expulsions);
                const values = Object.values(data.expulsions);

                expulsionsChart = new Chart(expulsionsCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nombre d\'exclusions',
                            data: values,
                            backgroundColor: '#EF4444',
                            borderColor: '#DC2626',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                                }
                            },
                            x: {
                                ticks: {
                                    color: document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151'
                                },
                                grid: {
                                    color: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB'
                                }
                            }
                        }
                    }
                });
            } else if (expulsionsChart) {
                expulsionsChart.destroy();
                expulsionsChart = null;
                // Afficher le message "Aucune exclusion"
                const expulsionsContainer = expulsionsCanvas.parentElement;
                expulsionsContainer.innerHTML = `
                    <div class="flex items-center justify-center h-48">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-chart-bar text-4xl mb-2 opacity-50"></i>
                            <div>Aucune exclusion pour cette période</div>
                        </div>
                    </div>
                `;
            }
        }
    </script>

    <script>
        // AJAX function for filters (comprehensive update)
        function applyAllFilters() {
            const matricule = document.getElementById('search-input').value;
            const gradeRadio = document.querySelector('input[name="grade_filter"]:checked');
            const grade = gradeRadio ? gradeRadio.value : 'all';

            console.log('Applying filters:', { matricule, grade });

            // Show loading indicator
            const loadingIndicator = document.getElementById('loading-indicator');
            if (loadingIndicator) {
                loadingIndicator.classList.remove('hidden');
            }

            // Prepare parameters
            const params = new URLSearchParams();
            if (matricule && matricule.trim() !== '') {
                params.append('matricule', matricule.trim());
            }
            if (grade && grade !== 'all') {
                params.append('grade', grade);
            }

            // Make AJAX call
            const url = `{{ route('de.statistics.data') }}?${params.toString()}`;
            console.log('Sending request to URL:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    console.log('Response status:', response.status);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.error('Response is not JSON:', contentType);
                        throw new Error(`Expected JSON response but got ${contentType}`);
                    }

                    return response.json();
                })
                .then(data => {
                    console.log('Received data from server:', data);

                    // Validate received data structure
                    if (!data || typeof data !== 'object') {
                        throw new Error('Invalid data structure received');
                    }

                    if (!data.infirmary) {
                        console.warn('No infirmary data in response');
                        data.infirmary = { validated: 0, non_validated: 0, total: 0 };
                    }

                    if (!data.expulsions) {
                        console.warn('No expulsions data in response');
                        data.expulsions = {};
                    }

                    if (data.total_expulsions === undefined) {
                        data.total_expulsions = 0;
                    }

                    // Update both charts and cards
                    updateCharts(data);
                    updateStatisticsCards(data);
                })
                .catch(error => {
                    console.error('Error fetching statistics data:', error);

                    // Show user-friendly error message
                    const errorMsg = 'Erreur lors de la mise à jour des statistiques. Veuillez réessayer.';

                    // You can replace this alert with a more elegant notification
                    alert(errorMsg);

                    // Reset to default values on error
                    const defaultData = {
                        infirmary: { validated: 0, non_validated: 0, total: 0 },
                        expulsions: {},
                        total_expulsions: 0
                    };
                    updateStatisticsCards(defaultData);
                })
                .finally(() => {
                    // Hide loading indicator
                    if (loadingIndicator) {
                        loadingIndicator.classList.add('hidden');
                    }
                });
        }

        // AJAX function for search and grade filters (quick update)
        function applyRealTimeFilters() {
            const searchValue = document.getElementById('search-input').value;
            const gradeRadio = document.querySelector('input[name="grade_filter"]:checked');
            const selectedGrade = gradeRadio ? gradeRadio.value : 'all';

            console.log('Applying real-time filters:', { searchValue, selectedGrade });

            // Update visual feedback immediately
            updateGradeButtonStyles();

            // Use comprehensive AJAX update
            applyAllFilters();
        }

        // Function to update statistics cards
        function updateStatisticsCards(data) {
            console.log('Updating statistics cards with:', data);

            // Validate data structure
            if (!data || typeof data !== 'object') {
                console.error('Invalid data received for statistics update:', data);
                return;
            }

            // Update infirmary statistics with validation
            const totalInfirmerieEl = document.querySelector('[data-stat="total-infirmerie"]');
            const rhpValidatedEl = document.querySelector('[data-stat="rhp-validated"]');
            const rhpPendingEl = document.querySelector('[data-stat="rhp-pending"]');
            const totalExpulsionsEl = document.querySelector('[data-stat="total-expulsions"]');

            if (totalInfirmerieEl && data.infirmary) {
                const total = data.infirmary.total || 0;
                totalInfirmerieEl.textContent = total;
                console.log('Updated total infirmerie:', total);
            }

            if (rhpValidatedEl && data.infirmary) {
                const validated = data.infirmary.validated || 0;
                rhpValidatedEl.textContent = validated;
                console.log('Updated RHP validated:', validated);
            }

            if (rhpPendingEl && data.infirmary) {
                const pending = data.infirmary.non_validated || 0;
                rhpPendingEl.textContent = pending;
                console.log('Updated RHP pending:', pending);
            }

            if (totalExpulsionsEl) {
                const totalExpulsions = data.total_expulsions || 0;
                totalExpulsionsEl.textContent = totalExpulsions;
                console.log('Updated total expulsions:', totalExpulsions);
            }
        }

        // Original real-time filtering function for table filtering (if tables exist)
        function applyTableFilters() {
            const searchValue = document.getElementById('search-input').value.toLowerCase();
            const selectedGrade = document.querySelector('input[name="grade_filter"]:checked').value;

            // For dashboard, we can filter any displayed tables if they exist
            const tables = document.querySelectorAll('table tbody tr');
            if (tables.length > 0) {
                tables.forEach(row => {
                    const matricule = (row.dataset.matricule || '').toLowerCase();
                    const grade = row.dataset.grade || '';

                    let matchesSearch = true;
                    let matchesGrade = true;

                    // Search filter
                    if (searchValue && !matricule.includes(searchValue)) {
                        matchesSearch = false;
                    }

                    // Grade filter
                    if (selectedGrade !== 'all') {
                        if (grade && grade.toString().includes(selectedGrade)) {
                            matchesGrade = true;
                        } else if (grade === selectedGrade) {
                            matchesGrade = true;
                        } else {
                            matchesGrade = false;
                        }
                    }

                    // Show/hide row based on filters
                    if (matchesSearch && matchesGrade) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Update grade button styles
            updateGradeButtonStyles();
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
            console.log('Dashboard loaded, initializing charts...');

            // Initialize charts with initial data
            initializeCharts();

            // Update grade button styles
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
