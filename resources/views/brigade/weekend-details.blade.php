<x-brigade css="weekend-details">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="weekendDetails()">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Weekend Sortie Details</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Complete history and details of weekend permissions
                        and exits</p>
                </div>
                <a href="{{ route('brigade.statistics', ['id' => $officer->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Statistics
                </a>
            </div>

            <!-- Graph Builder Section -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Construire Graph</h3>
                    <button @click="showGraphBuilder = !showGraphBuilder"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span x-text="showGraphBuilder ? 'Hide Graph Builder' : 'Show Graph Builder'"></span>
                    </button>
                </div>

                <div x-show="showGraphBuilder" x-transition class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                        <input type="date" x-model="graphConfig.from"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                        <input type="date" x-model="graphConfig.to"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unit</label>
                        <select x-model="graphConfig.unit"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
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
                        <canvas id="weekendGraph" class="w-full h-80"></canvas>
                    </div>
                </div>
            </div> <!-- Sortie Details List -->
            <div class="overflow-hidden rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Weekend Permissions History
                        </h3>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <span
                                    x-text="`Showing ${(currentPage - 1) * itemsPerPage + 1} to ${Math.min(currentPage * itemsPerPage, filteredSorties.length)} of ${filteredSorties.length} entries`"></span>
                            </div>
                            <select x-model="itemsPerPage" @change="currentPage = 1"
                                class="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-300">
                                <option value="10">10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="100">100 per page</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Student Name
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Section
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Permission Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Period
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Remarks
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date Created
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="sortie in paginatedSorties" :key="sortie.id">
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300"
                                        x-text="sortie.student ? sortie.student.matricule : 'N/A'">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        <span
                                            x-text="sortie.student ? (sortie.student.nom + ' ' + sortie.student.prenom) : 'N/A'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span
                                            x-text="sortie.student && sortie.student.section ? sortie.student.section.code : 'N/A'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-if="sortie.choix">
                                            <span x-text="getChoixLabel(sortie.choix)"
                                                :class="getChoixClass(sortie.choix)"
                                                class="px-2 py-1 text-xs font-medium rounded-full">
                                            </span>
                                        </template>
                                        <template x-if="!sortie.choix">
                                            <span
                                                class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200">
                                                No Type
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <template x-if="sortie.from && sortie.to">
                                            <span x-text="formatDateRange(sortie.from, sortie.to)"></span>
                                        </template>
                                        <template x-if="!sortie.from || !sortie.to">
                                            <span class="text-gray-400">N/A</span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="sortie.remarque || 'No remarks'"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        <span x-text="formatDate(sortie.created_at)"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span x-text="`Page ${currentPage} of ${totalPages}`"></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Previous Button -->
                            <button @click="currentPage = 1" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded transition-colors dark:text-gray-300">
                                First
                            </button>
                            <button @click="currentPage--" :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded transition-colors dark:text-gray-300">
                                <i class="fas fa-chevron-left"></i>
                            </button>

                            <!-- Page Numbers -->
                            <template x-for="page in visiblePages" :key="page">
                                <button @click="currentPage = page"
                                    :class="page === currentPage ? 'bg-blue-500 text-white border-blue-500' :
                                        'hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-300'"
                                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded transition-colors"
                                    x-text="page">
                                </button>
                            </template>

                            <!-- Next Button -->
                            <button @click="currentPage++" :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded transition-colors dark:text-gray-300">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <button @click="currentPage = totalPages" :disabled="currentPage === totalPages"
                                :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' :
                                    'hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded transition-colors dark:text-gray-300">
                                Last
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function weekendDetails() {
            return {
                showGraphBuilder: false,
                showGraph: false,
                chart: null,
                graphConfig: {
                    from: '',
                    to: '',
                    unit: 'days'
                },

                // Sortie data and pagination
                allSorties: {!! json_encode(
                    $sorties->map(function ($sortie) {
                            return [
                                'id' => $sortie->id,
                                'choix' => $sortie->choix,
                                'remarque' => $sortie->remarque,
                                'from' => $sortie->from,
                                'to' => $sortie->to,
                                'created_at' => $sortie->created_at,
                                'student' => $sortie->student
                                    ? [
                                        'matricule' => $sortie->student->matricule,
                                        'nom' => $sortie->student->nom,
                                        'prenom' => $sortie->student->prenom,
                                        'section' => $sortie->student->section ? ['code' => $sortie->student->section->code] : null,
                                    ]
                                    : null,
                            ];
                        })->toArray(),
                ) !!},
                filteredSorties: [],
                currentPage: 1,
                itemsPerPage: 25,

                init() {
                    // Set default dates (last 30 days)
                    const today = new Date();
                    const lastMonth = new Date();
                    lastMonth.setDate(today.getDate() - 30);

                    this.graphConfig.to = today.toISOString().split('T')[0];
                    this.graphConfig.from = lastMonth.toISOString().split('T')[0];

                    // Initialize filtered sorties
                    this.filteredSorties = [...this.allSorties];
                },

                get totalPages() {
                    return Math.ceil(this.filteredSorties.length / this.itemsPerPage);
                },

                get paginatedSorties() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return this.filteredSorties.slice(start, end);
                },

                get visiblePages() {
                    const pages = [];
                    const totalPages = this.totalPages;
                    const current = this.currentPage;

                    // Show up to 5 page numbers around current page
                    let start = Math.max(1, current - 2);
                    let end = Math.min(totalPages, current + 2);

                    // Adjust if we're near the beginning or end
                    if (end - start < 4) {
                        if (start === 1) {
                            end = Math.min(totalPages, start + 4);
                        } else if (end === totalPages) {
                            start = Math.max(1, end - 4);
                        }
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }

                    return pages;
                },

                getChoixLabel(choix) {
                    switch (choix) {
                        case 'ven':
                            return 'Vendredi';
                        case 'sam':
                            return 'Samedi';
                        case '48h':
                            return '48h';
                        case '36h':
                            return '36h';
                        default:
                            return choix;
                    }
                },

                getChoixClass(choix) {
                    switch (choix) {
                        case 'ven':
                            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200';
                        case 'sam':
                            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200';
                        case '48h':
                            return 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200';
                        case '36h':
                            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200';
                        default:
                            return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200';
                    }
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
                                    type: 'weekend',
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
                    const ctx = document.getElementById('weekendGraph').getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Weekend Sorties',
                                data: data.data,
                                borderColor: '#3b82f6',
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
                                        text: 'Number of Sorties'
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
                },

                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('en-GB');
                },

                formatDateRange(fromDate, toDate) {
                    if (!fromDate || !toDate) return 'N/A';
                    const from = new Date(fromDate).toLocaleDateString('en-GB');
                    const to = new Date(toDate).toLocaleDateString('en-GB');
                    return `${from} - ${to}`;
                }
            }
        }
    </script>
</x-brigade>
