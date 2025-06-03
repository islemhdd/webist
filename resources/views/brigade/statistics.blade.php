<x-brigade css='statistics'>
    @push('styles')
        <link rel="stylesheet" href="/css/statistics.css">
    @endpush

    <div class="container mx-auto p-6">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200">Brigade Statistics</h1>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ date('d/m/Y') }}
                    </div>
                </div>
                @unless (in_array(auth()->user()->role->name, ['Chef de compagnie', 'chef de bataillant']))
                    <!-- Grade Filter Section -->
                    <div class="mb-8">
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 text-center">Filter by Year
                            </h3>
                            <div class="flex justify-center">
                                <div class="flex flex-wrap gap-3 justify-center">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="grade_filter" value="all" class="sr-only" checked>
                                        <div
                                            class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                            <span class="text-gray-700 dark:text-gray-300 font-medium">All</span>
                                        </div>
                                    </label>
                                    @for ($year = 1; $year <= 3; $year++)
                                        <label class="flex items-center cursor-pointer group">
                                            <input type="radio" name="grade_filter" value="{{ $year }}"
                                                class="sr-only">
                                            <div
                                                class="grade-radio-custom bg-gray-100 dark:bg-gray-700 group-hover:bg-gray-200 dark:group-hover:bg-gray-600 border-2 border-gray-300 dark:border-gray-600 group-hover:border-gray-400 dark:group-hover:border-gray-500 rounded-lg px-3 py-2 transition-all duration-200 text-sm">
                                                <span
                                                    class="text-gray-700 dark:text-gray-300 font-medium">{{ $year }}st
                                                    Year</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <div id="loading-indicator" class="hidden">
                                    <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                                    <span class="text-gray-600 dark:text-gray-400 text-sm">Updating...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless

                <!-- Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    <!-- Weekend Permissions Chart -->
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Weekend
                            Permissions</h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="weekendChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200"
                                            id="total-weekend-permissions">
                                            {{ $weekendStats['total'] }}
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
                                    <span class="text-gray-700 dark:text-gray-300">Vendredi</span>
                                </div>
                                <div class="font-semibold text-blue-600 dark:text-blue-400" id="vendredi-detail">
                                    {{ $weekendStats['vendredi'] }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Samedi</span>
                                </div>
                                <div class="font-semibold text-green-600 dark:text-green-400" id="samedi-detail">
                                    {{ $weekendStats['samedi'] }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">48h</span>
                                </div>
                                <div class="font-semibold text-purple-600 dark:text-purple-400" id="h48-detail">
                                    {{ $weekendStats['h48'] }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">36h</span>
                                </div>
                                <div class="font-semibold text-yellow-600 dark:text-yellow-400" id="h36-detail">
                                    {{ $weekendStats['h36'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sanctions Chart -->
                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Sanctions
                        </h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="sanctionsChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200"
                                            id="total-sanctions">
                                            {{ $sanctionsStats['total'] }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2" id="sanctions-details-list">
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Active Arrests</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" id="active-arrests-detail">
                                    {{ $sanctionsStats['activeArrests'] }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Past Arrests</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" id="past-arrests-detail">
                                    {{ $sanctionsStats['pastArrests'] }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Weekend Restrictions</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200"
                                    id="weekend-restrictions-detail">
                                    {{ $sanctionsStats['weekendRestrictions'] }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Warnings</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-gray-200" id="warnings-detail">
                                    {{ $sanctionsStats['warnings'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Patine Chart -->
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Patient
                            history</h3>
                        <div class="flex justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="patineChart" class="w-full h-full"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200"
                                            id="total-patine">
                                            {{ $patineStats['total'] }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-around text-sm">
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Pending</span>
                                </div>
                                <div class="font-semibold text-yellow-600 dark:text-yellow-400"
                                    id="pending-patine-detail">
                                    {{ $patineStats['pending'] }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Rejected</span>
                                </div>
                                <div class="font-semibold text-red-600 dark:text-red-400" id="rejected-patine-detail">
                                    {{ $patineStats['rejected'] }}
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-gray-700 dark:text-gray-300">Validated</span>
                                </div>
                                <div class="font-semibold text-green-600 dark:text-green-400"
                                    id="validated-patine-detail">
                                    {{ $patineStats['validated'] }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-xl p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 text-center">Summary
                        </h3>
                        <div class="space-y-4">
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-blue-500">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400" id="total-students">
                                    {{ $totalStudents }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Total Students</div>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-green-500">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400"
                                    id="weekend-approval-rate">
                                    {{ number_format($weekendStats['total'] > 0 ? (($weekendStats['vendredi'] + $weekendStats['samedi']) / $weekendStats['total']) * 100 : 0, 1) }}%
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Weekend Approval Rate</div>
                            </div>
                            <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border-l-4 border-purple-500">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400"
                                    id="patine-approval-rate">
                                    {{ number_format($patineStats['total'] > 0 ? ($patineStats['validated'] / $patineStats['total']) * 100 : 0, 1) }}%
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">Patine Approval Rate</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Common chart options
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

            // Initialize charts
            const weekendCtx = document.getElementById('weekendChart').getContext('2d');
            const sanctionsCtx = document.getElementById('sanctionsChart').getContext('2d');
            const patineCtx = document.getElementById('patineChart').getContext('2d');

            // Create charts
            let weekendChart = new Chart(weekendCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Vendredi', 'Samedi', '48h', '36h'],
                    datasets: [{
                        data: [
                            {{ $weekendStats['vendredi'] }},
                            {{ $weekendStats['samedi'] }},
                            {{ $weekendStats['h48'] }},
                            {{ $weekendStats['h36'] }}
                        ],
                        backgroundColor: ['#3b82f6', '#22c55e', '#8b5cf6', '#eab308'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            let sanctionsChart = new Chart(sanctionsCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active Arrests', 'Past Arrests', 'Weekend Restrictions', 'Warnings'],
                    datasets: [{
                        data: [
                            {{ $sanctionsStats['activeArrests'] }},
                            {{ $sanctionsStats['pastArrests'] }},
                            {{ $sanctionsStats['weekendRestrictions'] }},
                            {{ $sanctionsStats['warnings'] }}
                        ],
                        backgroundColor: ['#ef4444', '#f97316', '#eab308', '#6b7280'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            let patineChart = new Chart(patineCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Rejected', 'Validated'],
                    datasets: [{
                        data: [
                            {{ $patineStats['pending'] }},
                            {{ $patineStats['rejected'] }},
                            {{ $patineStats['validated'] }}
                        ],
                        backgroundColor: ['#eab308', '#ef4444', '#22c55e'],
                        borderWidth: 0
                    }]
                },
                options: commonOptions
            });

            // Handle grade filter changes
            document.addEventListener('DOMContentLoaded', function() {
                const gradeRadios = document.querySelectorAll('input[name="grade_filter"]');
                const loadingIndicator = document.getElementById('loading-indicator');

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

                updateRadioStyles();

                gradeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        if (this.checked) {
                            updateRadioStyles();
                            filterStatistics(this.value);
                        }
                    });
                });

                function filterStatistics(grade) {
                    loadingIndicator.classList.remove('hidden');

                    fetch(`/brigade/statistics/filter?grade=${grade}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            updateCharts(data);
                            updateDetailCards(data);
                            updateSummaryCards(data);
                            loadingIndicator.classList.add('hidden');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            loadingIndicator.classList.add('hidden');
                            alert('Error updating statistics: ' + error.message);
                        });
                }

                function updateCharts(data) {
                    // Update Weekend Permissions chart
                    weekendChart.data.datasets[0].data = [
                        data.weekendStats.vendredi,
                        data.weekendStats.samedi,
                        data.weekendStats.h48,
                        data.weekendStats.h36
                    ];
                    weekendChart.update('active');

                    // Update Sanctions chart
                    sanctionsChart.data.datasets[0].data = [
                        data.sanctionsStats.activeArrests,
                        data.sanctionsStats.pastArrests,
                        data.sanctionsStats.weekendRestrictions,
                        data.sanctionsStats.warnings
                    ];
                    sanctionsChart.update('active');

                    // Update Patine chart
                    patineChart.data.datasets[0].data = [
                        data.patineStats.pending,
                        data.patineStats.rejected,
                        data.patineStats.validated
                    ];
                    patineChart.update('active');
                }

                function updateDetailCards(data) {
                    // Update Weekend Permissions details
                    document.getElementById('vendredi-detail').textContent = data.weekendStats.vendredi;
                    document.getElementById('samedi-detail').textContent = data.weekendStats.samedi;
                    document.getElementById('h48-detail').textContent = data.weekendStats.h48;
                    document.getElementById('h36-detail').textContent = data.weekendStats.h36;

                    // Update Sanctions details
                    document.getElementById('active-arrests-detail').textContent = data.sanctionsStats.activeArrests;
                    document.getElementById('past-arrests-detail').textContent = data.sanctionsStats.pastArrests;
                    document.getElementById('weekend-restrictions-detail').textContent = data.sanctionsStats
                        .weekendRestrictions;
                    document.getElementById('warnings-detail').textContent = data.sanctionsStats.warnings;

                    // Update Patine details
                    document.getElementById('pending-patine-detail').textContent = data.patineStats.pending;
                    document.getElementById('rejected-patine-detail').textContent = data.patineStats.rejected;
                    document.getElementById('validated-patine-detail').textContent = data.patineStats.validated;
                }

                function updateSummaryCards(data) {
                    document.getElementById('total-students').textContent = data.totalStudents;

                    // Calculate and update rates
                    const weekendApprovalRate = data.weekendStats.total > 0 ?
                        ((data.weekendStats.vendredi + data.weekendStats.samedi) / data.weekendStats.total * 100)
                        .toFixed(1) :
                        '0.0';
                    document.getElementById('weekend-approval-rate').textContent = `${weekendApprovalRate}%`;

                    const patineApprovalRate = data.patineStats.total > 0 ?
                        (data.patineStats.validated / data.patineStats.total * 100).toFixed(1) :
                        '0.0';
                    document.getElementById('patine-approval-rate').textContent = `${patineApprovalRate}%`;
                }
            });
        </script>
    @endpush
</x-brigade>
