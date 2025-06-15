<x-brigade css="reports-details">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="reportsDetails()">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Reports Details</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Complete overview of reports data</p>
                </div>
                <a href="{{ route('brigade.statistics', ['id' => $officer->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Statistics
                </a>
            </div>

            <!-- Graph Builder Section -->
            <div
                class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Construire Graph</h3>
                    <button @click="showGraphBuilder = !showGraphBuilder"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span x-text="showGraphBuilder ? 'Hide Graph Builder' : 'Show Graph Builder'"></span>
                    </button>
                </div>

                <div x-show="showGraphBuilder" x-transition class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" x-model="graphConfig.from"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" x-model="graphConfig.to"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit</label>
                        <select x-model="graphConfig.unit"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-300">
                            <option value="days">Days</option>
                            <option value="weeks">Weeks</option>
                            <option value="months">Months</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button @click="generateGraph()"
                            class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Generate Graph
                        </button>
                    </div>
                </div>

                <!-- Graph Display -->
                <div x-show="showGraph" x-transition class="mt-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-inner">
                        <canvas id="reportsGraph" class="w-full h-80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Reports List -->
            <div class="overflow-hidden rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Reports List</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Student
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Title
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Created Date
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($reports as $report)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $report->student ? $report->student->nom . ' ' . $report->student->prenom : 'N/A' }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $report->student ? $report->student->matricule : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($report->is_medical)
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                Medical
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($report->refused)
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">
                                                Refused
                                            </span>
                                        @elseif($report->status === 'DONE')
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                                Validated
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                {{ $report->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('report.show', ['id' => $officer->id, 'report_id' => $report->id]) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-eye"></i>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function reportsDetails() {
            return {
                showGraphBuilder: false,
                showGraph: false,
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
                },

                async generateGraph() {
                    if (!this.graphConfig.from || !this.graphConfig.to) {
                        alert('Please select both from and to dates');
                        return;
                    }

                    try {
                        const response = await fetch(
                            '{{ route('brigade.statistics.graph-data', ['id' => $officer->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    type: 'reports',
                                    from: this.graphConfig.from,
                                    to: this.graphConfig.to,
                                    unit: this.graphConfig.unit
                                })
                            });

                        const data = await response.json();

                        if (response.ok) {
                            this.createChart(data);
                            this.showGraph = true;
                        } else {
                            console.error('Graph generation failed:', data);
                            alert('Failed to generate graph');
                        }
                    } catch (error) {
                        console.error('Graph generation error:', error);
                        alert('Error generating graph');
                    }
                },

                createChart(data) {
                    const ctx = document.getElementById('reportsGraph').getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Reports',
                                data: data.data,
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
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
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false
                                }
                            },
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: this.getXAxisLabel()
                                    }
                                },
                                y: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Number of Reports'
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },

                getXAxisLabel() {
                    switch (this.graphConfig.unit) {
                        case 'days':
                            return 'Days';
                        case 'weeks':
                            return 'Weeks';
                        case 'months':
                            return 'Months';
                        default:
                            return 'Time Period';
                    }
                }
            }
        }
    </script>
</x-brigade>
