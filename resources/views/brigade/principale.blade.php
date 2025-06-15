<x-brigade css='statistics'>
    @push('styles')
        <link rel="stylesheet" href="/css/statistics.css">
    @endpush
    <div class="container mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Statistiques de brigade</h1>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ date('d/m/Y') }}
                    </div>
                </div>

                <!-- Grade Filter Section -->
                <div class="mb-8">
                    <div
                        class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 text-center">
                            Filtrer par Année d'Étude
                        </h3>
                        <div class="flex justify-center">
                            <div class="flex flex-wrap gap-3 justify-center">
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="all" class="sr-only" checked>
                                    <div
                                        class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">Toutes</span>
                                    </div>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="1" class="sr-only">
                                    <div
                                        class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">1ère année</span>
                                    </div>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="2" class="sr-only">
                                    <div
                                        class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">2ème année</span>
                                    </div>
                                </label>
                                <label class="flex items-center cursor-pointer group">
                                    <input type="radio" name="grade_filter" value="3" class="sr-only">
                                    <div
                                        class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">3ème année</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <div id="loading-indicator" class="hidden">
                                <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                                <span class="text-gray-600 dark:text-gray-400 text-sm">Mise à jour...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">

                    <!-- Patients Validation Chart -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">
                            Patients - Aujourd'hui
                        </h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="patientsChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                            {{ $validPatientsToday + $invalidPatientsToday }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Validés</span>
                                </div>
                                <div class="font-semibold text-green-600 dark:text-green-400"
                                    id="valid-patients-detail">{{ $validPatientsToday }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">En attente</span>
                                </div>
                                <div class="font-semibold text-yellow-600 dark:text-yellow-400"
                                    id="invalid-patients-detail">{{ $invalidPatientsToday }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments Chart -->
                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">
                            Rendez-vous - Aujourd'hui
                        </h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="appointmentsChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                            {{ $consultationRdvToday + $urgenceRdvToday }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Consultation</span>
                                </div>
                                <div class="font-semibold text-blue-600 dark:text-blue-400"
                                    id="consultation-rdv-detail">{{ $consultationRdvToday }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Urgences</span>
                                </div>
                                <div class="font-semibold text-red-600 dark:text-red-400" id="urgence-rdv-detail">
                                    {{ $urgenceRdvToday }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Convocations Chart -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">
                            Convocations - Total
                        </h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="convocationsChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                            {{ $convocationsWithPsy + $convocationsWithoutPsy }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Validés</span>
                                </div>
                                <div class="font-semibold text-indigo-600 dark:text-indigo-400"
                                    id="convocations-with-psy-detail">{{ $convocationsWithPsy }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">En attente</span>
                                </div>
                                <div class="font-semibold text-gray-600 dark:text-gray-400"
                                    id="convocations-without-psy-detail">{{ $convocationsWithoutPsy }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Exemptions Chart -->
                    <div
                        class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">
                            Exemptions par Motif - Aujourd'hui
                        </h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                @if ($exemptionsToday->count() > 0)
                                    <canvas id="exemptionsChart" class="w-full h-full"></canvas>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                                {{ $exemptionsToday->sum('count') }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-chart-pie text-4xl mb-2 opacity-50"></i>
                                            <div>Aucune exemption aujourd'hui</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4 space-y-2 max-h-32 overflow-y-auto" id="exemptions-details-list">
                            @if ($exemptionsToday->count() > 0)
                                @foreach ($exemptionsToday as $index => $exemption)
                                    <div class="flex justify-between text-sm">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 rounded-full mr-2"
                                                style="background-color: {{ ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899'][$index % 7] }}">
                                            </div>
                                            <span
                                                class="text-gray-700 dark:text-gray-300">{{ ucfirst($exemption->motif) }}</span>
                                        </div>
                                        <span
                                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $exemption->count }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-gray-500 dark:text-gray-400 text-sm py-4">
                                    Aucune exemption aujourd'hui
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-blue-500">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-patients">
                            {{ $validPatientsToday + $invalidPatientsToday }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Patients Aujourd'hui</div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-purple-500">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400" id="total-rdv">
                            {{ $consultationRdvToday + $urgenceRdvToday }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">RDV Aujourd'hui</div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-green-500">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400" id="total-convocations">
                            {{ $convocationsWithPsy + $convocationsWithoutPsy }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Convocations Total</div>
                    </div>
                    <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-orange-500">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400" id="total-exemptions">
                            {{ $exemptionsToday->sum('count') }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Exemptions Aujourd'hui</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Configuration commune pour tous les graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }; // Graphiques initiaux - maintenant gérés dans la section de filtrage// Animation d'entrée pour les cartes
            const cards = document.querySelectorAll('.bg-gradient-to-br');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            }); // Initialisation des contextes des graphiques
            const patientsCtx = document.getElementById('patientsChart').getContext('2d');
            const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
            const convocationsCtx = document.getElementById('convocationsChart').getContext('2d');
            @if ($exemptionsToday->count() > 0)
                const exemptionsCtx = document.getElementById('exemptionsChart').getContext('2d');
            @endif // Variables globales pour stocker les graphiques
            let patientsChart, appointmentsChart, convocationsChart;

            // Utiliser window pour exemptionsChart pour éviter les conflits de portée
            window.exemptionsChart = null;

            // Stocker les références des graphiques
            patientsChart = new Chart(patientsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Validés', 'En attente'],
                    datasets: [{
                        data: [{{ $validPatientsToday }}, {{ $invalidPatientsToday }}],
                        backgroundColor: ['#22c55e', '#eab308'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            appointmentsChart = new Chart(appointmentsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Consultation', 'Urgences'],
                    datasets: [{
                        data: [{{ $consultationRdvToday }}, {{ $urgenceRdvToday }}],
                        backgroundColor: ['#3b82f6', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            convocationsChart = new Chart(convocationsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Avec Psy', 'Sans Psy'],
                    datasets: [{
                        data: [{{ $convocationsWithPsy }}, {{ $convocationsWithoutPsy }}],
                        backgroundColor: ['#6366f1', '#6b7280'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });
            @if ($exemptionsToday->count() > 0)
                window.exemptionsChart = new Chart(exemptionsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: [
                            @foreach ($exemptionsToday as $exemption)
                                '{{ ucfirst($exemption->motif) }}',
                            @endforeach
                        ],
                        datasets: [{
                            data: [
                                @foreach ($exemptionsToday as $exemption)
                                    {{ $exemption->count }},
                                @endforeach
                            ],
                            backgroundColor: [
                                '#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6', '#ec4899'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: commonOptions
                });
            @endif

            // Gestion du filtrage par grade
            document.addEventListener('DOMContentLoaded', function() {
                const gradeRadios = document.querySelectorAll('input[name="grade_filter"]');
                const loadingIndicator = document.getElementById('loading-indicator');

                // Gérer les styles des boutons radio personnalisés
                function updateRadioStyles() {
                    gradeRadios.forEach(radio => {
                        const customDiv = radio.nextElementSibling;
                        if (radio.checked) {
                            customDiv.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'border-gray-300',
                                'dark:border-gray-600');
                            customDiv.classList.add('bg-blue-500', 'border-blue-500', 'shadow-lg', 'transform',
                                'scale-105');
                            customDiv.querySelector('span').classList.remove('text-gray-700',
                                'dark:text-gray-300');
                            customDiv.querySelector('span').classList.add('text-white');
                        } else {
                            customDiv.classList.add('bg-gray-100', 'dark:bg-gray-700', 'border-gray-300',
                                'dark:border-gray-600');
                            customDiv.classList.remove('bg-blue-500', 'border-blue-500', 'shadow-lg',
                                'transform', 'scale-105');
                            customDiv.querySelector('span').classList.add('text-gray-700',
                                'dark:text-gray-300');
                            customDiv.querySelector('span').classList.remove('text-white');
                        }
                    });
                }

                // Initialiser les styles
                updateRadioStyles();

                // Gérer les changements de filtre
                gradeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.checked) {
                            updateRadioStyles();
                            filterStatistics(this.value);
                        }
                    });
                }); // Fonction pour filtrer les statistiques
                function filterStatistics(grade) {
                    loadingIndicator.classList.remove('hidden');

                    // Appel AJAX pour obtenir les données filtrées
                    fetch(`/infermerie/statistics/filter?grade=${grade}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                throw new Error(data.error);
                            }
                            updateCharts(data);
                            updateSummaryCards(data);
                            updateDetailCards(data);
                            loadingIndicator.classList.add('hidden');
                        })
                        .catch(error => {
                            console.error('Erreur lors du filtrage:', error);
                            loadingIndicator.classList.add('hidden');
                            alert('Erreur lors de la mise à jour des statistiques: ' + error.message);
                        });
                } // Fonction pour mettre à jour les graphiques
                function updateCharts(data) {
                    // Mettre à jour le graphique des patients
                    if (patientsChart) {
                        patientsChart.data.datasets[0].data = [data.validPatientsToday, data.invalidPatientsToday];
                        patientsChart.update('active');
                    }

                    // Mettre à jour le graphique des rendez-vous
                    if (appointmentsChart) {
                        appointmentsChart.data.datasets[0].data = [data.consultationRdvToday, data.urgenceRdvToday];
                        appointmentsChart.update('active');
                    }

                    // Mettre à jour le graphique des convocations
                    if (convocationsChart) {
                        convocationsChart.data.datasets[0].data = [data.convocationsWithPsy, data
                            .convocationsWithoutPsy
                        ];
                        convocationsChart.update('active');
                    } // Mettre à jour le graphique des exemptions
                    updateExemptionsChart(data.exemptionsToday);

                    // Mettre à jour les totaux au centre des graphiques
                    updateCenterTotals(data);
                }

                // Fonction pour mettre à jour les totaux au centre
                function updateCenterTotals(data) {
                    const patientsTotal = document.querySelector('#patientsChart').parentElement.querySelector(
                        '.absolute .text-2xl');
                    const appointmentsTotal = document.querySelector('#appointmentsChart').parentElement.querySelector(
                        '.absolute .text-2xl');
                    const convocationsTotal = document.querySelector('#convocationsChart').parentElement.querySelector(
                        '.absolute .text-2xl');

                    if (patientsTotal) {
                        patientsTotal.textContent = data.validPatientsToday + data.invalidPatientsToday;
                    }
                    if (appointmentsTotal) {
                        appointmentsTotal.textContent = data.consultationRdvToday + data.urgenceRdvToday;
                    }
                    if (convocationsTotal) {
                        convocationsTotal.textContent = data.convocationsWithPsy + data.convocationsWithoutPsy;
                    }
                } // Fonction pour mettre à jour les cartes de résumé
                function updateSummaryCards(data) {
                    const totalPatients = data.validPatientsToday + data.invalidPatientsToday;
                    const totalRdv = data.consultationRdvToday + data.urgenceRdvToday;
                    const totalConvocations = data.convocationsWithPsy + data.convocationsWithoutPsy;
                    const totalExemptions = data.exemptionsToday.reduce((sum, item) => sum + item.count, 0);

                    document.getElementById('total-patients').textContent = totalPatients;
                    document.getElementById('total-rdv').textContent = totalRdv;
                    document.getElementById('total-convocations').textContent = totalConvocations;
                    document.getElementById('total-exemptions').textContent = totalExemptions;
                }

                // Fonction pour mettre à jour les cartes de détail sous les graphiques
                function updateDetailCards(data) {
                    // Détails des patients
                    const validPatientsDetail = document.getElementById('valid-patients-detail');
                    const invalidPatientsDetail = document.getElementById('invalid-patients-detail');
                    if (validPatientsDetail) validPatientsDetail.textContent = data.validPatientsToday;
                    if (invalidPatientsDetail) invalidPatientsDetail.textContent = data.invalidPatientsToday;

                    // Détails des rendez-vous
                    const consultationDetail = document.getElementById('consultation-rdv-detail');
                    const urgenceDetail = document.getElementById('urgence-rdv-detail');
                    if (consultationDetail) consultationDetail.textContent = data.consultationRdvToday;
                    if (urgenceDetail) urgenceDetail.textContent = data.urgenceRdvToday;

                    // Détails des convocations
                    const convocationsWithPsyDetail = document.getElementById('convocations-with-psy-detail');
                    const convocationsWithoutPsyDetail = document.getElementById('convocations-without-psy-detail');
                    if (convocationsWithPsyDetail) convocationsWithPsyDetail.textContent = data.convocationsWithPsy;
                    if (convocationsWithoutPsyDetail) convocationsWithoutPsyDetail.textContent = data
                        .convocationsWithoutPsy;

                    // Détails des exemptions
                    updateExemptionsDetailsList(data.exemptionsToday);
                } // Fonction pour mettre à jour la liste des détails d'exemptions
                function updateExemptionsDetailsList(exemptions) {
                    const exemptionsDetailsList = document.getElementById('exemptions-details-list');
                    if (exemptionsDetailsList) {
                        if (exemptions.length > 0) {
                            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6',
                                '#ec4899'
                            ];
                            exemptionsDetailsList.innerHTML = exemptions.map((exemption, index) => `
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-2" style="background-color: ${colors[index % 7]}"></div>
                                    <span class="text-gray-700 dark:text-gray-300">${exemption.motif}</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200">${exemption.count}</span>
                            </div>
                        `).join('');
                        } else {
                            exemptionsDetailsList.innerHTML = `
                            <div class="text-center text-gray-500 dark:text-gray-400 text-sm py-4">
                                Aucune exemption pour ce filtre
                            </div>
                        `;
                        }
                    }
                }

                // Fonction pour mettre à jour le graphique d'exemptions
                function updateExemptionsChart(exemptions) {
                    // Chercher le container principal des exemptions de manière plus robuste
                    const exemptionsChart = document.getElementById('exemptionsChart');
                    let exemptionsChartContainer;

                    if (exemptionsChart) {
                        exemptionsChartContainer = exemptionsChart.closest('.relative');
                    } else {
                        // Si le canvas n'existe pas, chercher le container par la classe
                        exemptionsChartContainer = document.querySelector(
                            '.bg-gradient-to-br.from-orange-50 .relative');
                    }

                    if (!exemptionsChartContainer) {
                        console.error('Container des exemptions non trouvé');
                        return;
                    }

                    // Détruire le graphique existant s'il existe
                    if (window.exemptionsChart) {
                        window.exemptionsChart.destroy();
                        window.exemptionsChart = null;
                    }

                    if (exemptions.length > 0) {
                        // Reconstruire le container du graphique
                        exemptionsChartContainer.innerHTML = `
                        <canvas id="exemptionsChart" class="w-full h-full"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                                    ${exemptions.reduce((sum, item) => sum + item.count, 0)}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                            </div>
                        </div>
                    `;

                        // Recréer le graphique
                        const newCanvas = document.getElementById('exemptionsChart');
                        if (newCanvas) {
                            const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6', '#8b5cf6',
                                '#ec4899'
                            ];

                            window.exemptionsChart = new Chart(newCanvas.getContext('2d'), {
                                type: 'doughnut',
                                data: {
                                    labels: exemptions.map(item => item.motif),
                                    datasets: [{
                                        data: exemptions.map(item => item.count),
                                        backgroundColor: exemptions.map((_, index) => colors[index %
                                            7]),
                                        borderWidth: 0
                                    }]
                                },
                                options: commonOptions
                            });
                        }
                    } else {
                        // Afficher le message "Aucune exemption"
                        exemptionsChartContainer.innerHTML = `
                        <div class="flex items-center justify-center h-full">
                            <div class="text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-chart-pie text-4xl mb-2 opacity-50"></i>
                                <div>Aucune exemption pour ce filtre</div>
                            </div>
                        </div>
                    `;
                    }

                    // Mettre à jour la liste des détails d'exemptions
                    updateExemptionsDetailsList(exemptions);
                }
            });
        </script>
    @endpush
    </x-infermerie>
