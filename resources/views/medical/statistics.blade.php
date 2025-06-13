<x-infermerie css='statistics'>
    @push('styles')
    <link rel="stylesheet" href="/css/statistics.css">
    @endpush
    <div class="container mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">
                            @if(isset($specialtyName))
                                Statistiques - {{ $specialtyName }}
                            @else
                                Statistiques Médicales
                            @endif
                        </h1>
                        @if(isset($specialtyName))
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <i class="{{ $specialtyIcon ?? 'fa-solid fa-user-doctor' }} mr-2"></i>
                                Spécialité : {{ $specialtyName }}
                            </p>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ date('d/m/Y') }}
                    </div>
                </div>

                <!-- Note pour les médecins spécialisés -->
                @if($allowedSpecialty !== 'all')
                    <div class="mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mr-3"></i>
                                <div>
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">Statistiques spécialisées</h3>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                                        Ces statistiques sont filtrées pour votre spécialité uniquement et concernent les données d'aujourd'hui.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Statistics Grid - 3 colonnes au lieu de 4 (sans exemptions) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <!-- Patients Validation Chart -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 shadow-sm">
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
                                <div class="font-semibold text-green-600 dark:text-green-400" id="valid-patients-detail">{{ $validPatientsToday }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">En attente</span>
                                </div>
                                <div class="font-semibold text-yellow-600 dark:text-yellow-400" id="invalid-patients-detail">{{ $invalidPatientsToday }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Appointments Chart -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 shadow-sm">
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
                                <div class="font-semibold text-blue-600 dark:text-blue-400" id="consultation-rdv-detail">{{ $consultationRdvToday }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Urgences</span>
                                </div>
                                <div class="font-semibold text-red-600 dark:text-red-400" id="urgence-rdv-detail">{{ $urgenceRdvToday }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Convocations Chart -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">
                            @if($allowedSpecialty === 'psycho')
                                Convocations Psychologiques
                            @elseif($allowedSpecialty === 'dentiste')
                                Convocations Dentaires
                            @elseif($allowedSpecialty === 'médecin générale')
                                Convocations Médicales
                            @else
                                Convocations - Total
                            @endif
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
                                    <span class="text-gray-700 dark:text-gray-300">Validées</span>
                                </div>
                                <div class="font-semibold text-indigo-600 dark:text-indigo-400" id="convocations-with-psy-detail">{{ $convocationsWithPsy }}</div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">En attente</span>
                                </div>
                                <div class="font-semibold text-gray-600 dark:text-gray-400" id="convocations-without-psy-detail">{{ $convocationsWithoutPsy }}</div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
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
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            @if($allowedSpecialty === 'psycho')
                                Conv. Psycho
                            @elseif($allowedSpecialty === 'dentiste')
                                Conv. Dentaires
                            @elseif($allowedSpecialty === 'médecin générale')
                                Conv. Médicales
                            @else
                                Convocations Total
                            @endif
                        </div>
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
        };

        // Initialisation des contextes des graphiques
        const patientsCtx = document.getElementById('patientsChart').getContext('2d');
        const appointmentsCtx = document.getElementById('appointmentsChart').getContext('2d');
        const convocationsCtx = document.getElementById('convocationsChart').getContext('2d');

        // Variables globales pour stocker les graphiques
        let patientsChart, appointmentsChart, convocationsChart;

        // Créer les graphiques
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
                labels: ['Validées', 'En attente'],
                datasets: [{
                    data: [{{ $convocationsWithPsy }}, {{ $convocationsWithoutPsy }}],
                    backgroundColor: ['#6366f1', '#6b7280'],
                    borderWidth: 0
                }]
            },
            options: commonOptions
        });

        // Animation d'entrée pour les cartes
        const cards = document.querySelectorAll('.bg-gradient-to-br');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    </script>
    @endpush
</x-infermerie>
