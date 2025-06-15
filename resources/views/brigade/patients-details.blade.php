<x-brigade css="patients details">
    <div x-data="patientsPage()">
        <div class="max-w-7xl mx-auto py-8 px-4">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6"><!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Patient Details</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Total patients: {{ $patients->count() }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button @click="showGraph = !showGraph"
                            class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                            <i class="fas fa-chart-line"></i>
                            <span x-text="showGraph ? 'Hide Graph' : 'Show Graph'">Show Graph</span>
                        </button>
                        <button onclick="window.history.back()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-colors shadow-md flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </button>
                    </div>
                </div>

                <!-- Graph Section -->
                <div x-show="showGraph" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95" class="mb-8">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <div class="flex flex-wrap items-center gap-4 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Patient Statistics Graph
                            </h3>

                            <!-- Graph Controls -->
                            <div class="flex flex-wrap gap-2">
                                <input type="date" x-model="graphConfig.from"
                                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-gray-100">
                                <span class="text-gray-500 dark:text-gray-400 self-center">to</span>
                                <input type="date" x-model="graphConfig.to"
                                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-gray-100">

                                <select x-model="graphConfig.unit"
                                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md text-sm dark:bg-gray-800 dark:text-gray-100">
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                </select>

                                <button @click="loadGraphData()"
                                    class="px-4 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                    Update Graph
                                </button>
                            </div>
                        </div>

                        <!-- Graph Container -->
                        <div class="relative">
                            <canvas id="patientsChart" width="400" height="500"></canvas>
                            <div x-show="graphLoading"
                                class="absolute inset-0 bg-white dark:bg-gray-700 bg-opacity-75 flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-spinner fa-spin text-2xl text-blue-600 mb-2"></i>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Loading graph data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patients Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Student
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Avis Médical
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($patients as $patient)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold mr-3">
                                                <span>{{ substr($patient->matricule, 0, 2) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $patient->student->nom }} {{ $patient->student->prenom }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Matricule: {{ $patient->matricule }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $patient->student->section->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($patient->type_medecin)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200 capitalize">
                                                {{ $patient->type_medecin }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        @if ($patient->avis_medecin)
                                            <div class="max-w-xs truncate" title="{{ $patient->avis_medecin }}">
                                                {{ Str::limit($patient->avis_medecin, 50) }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Aucun avis</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($patient->valider === 1)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                Validé
                                            </span>
                                        @elseif($patient->valider === 0)
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                En attente
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                Refusé
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $patient->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-user-injured text-3xl mb-4"></i>
                                            <p class="text-lg font-medium">Aucun patient trouvé</p>
                                            <p class="text-sm">Aucun patient médical enregistré pour votre brigade</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary Statistics -->
                @if ($patients->count() > 0)
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-l-4 border-green-500">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $patients->where('valider', 1)->count() }}
                            </div>
                            <div class="text-sm text-green-600 dark:text-green-400">Patients Validés</div>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border-l-4 border-yellow-500">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $patients->where('valider', 0)->count() }}
                            </div>
                            <div class="text-sm text-yellow-600 dark:text-yellow-400">En Attente</div>
                        </div>

                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border-l-4 border-red-500">
                            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ $patients->where('valider', 2)->count() }}
                            </div>
                            <div class="text-sm text-red-600 dark:text-red-400">Refusés</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('patientsPage', () => ({
                    showGraph: false,
                    graphLoading: false,
                    chart: null,
                    graphConfig: {
                        from: '',
                        to: '',
                        unit: 'days'
                    },

                    init() {
                        // Set default dates (last 30 days)
                        const today = new Date();
                        const lastMonth = new Date();
                        lastMonth.setDate(today.getDate() - 30);

                        this.graphConfig.to = today.toISOString().split('T')[0];
                        this.graphConfig.from = lastMonth.toISOString().split('T')[0];

                        console.log('Patient graph component initialized');
                    },

                    async loadGraphData() {
                        this.graphLoading = true;

                        try {
                            console.log('Loading graph data for patients...');

                            const response = await fetch(
                                '{{ route('brigade.statistics.graph-data', ['id' => $officer->id]) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        type: 'patients',
                                        from: this.graphConfig.from,
                                        to: this.graphConfig.to,
                                        unit: this.graphConfig.unit
                                    })
                                }
                            );

                            if (!response.ok) {
                                const errorText = await response.text();
                                console.error('Graph response error:', response.status, errorText);
                                throw new Error(`Server error: ${response.status}`);
                            }

                            const data = await response.json();
                            console.log('Graph data received:', data);
                            this.updateChart(data);
                        } catch (error) {
                            console.error('Graph error:', error);
                            alert('Failed to generate graph: ' + error.message);
                        } finally {
                            this.graphLoading = false;
                        }
                    },

                    updateChart(data) {
                        const ctx = document.getElementById('patientsChart').getContext('2d');

                        console.log('Updating chart with data:', data);

                        if (!window.Chart) {
                            console.error('Chart.js not loaded');
                            alert('Chart.js library not loaded');
                            return;
                        }

                        if (this.chart) {
                            this.chart.destroy();
                        }

                        this.chart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Patients',
                                    data: data.data,
                                    borderColor: 'rgb(59, 130, 246)',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    borderWidth: 2,
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top'
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            }
                        });

                        console.log('Chart created successfully');
                    }
                }))
            });
        </script>
</x-brigade>
