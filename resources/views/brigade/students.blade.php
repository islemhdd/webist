<x-brigade css="list students">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="studentsList()" x-init="init()">>
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des Étudiants</h2>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="searchStudents()"
                            placeholder="Rechercher par matricule, nom ou prénom..."
                            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg
                                      focus:ring-blue-500 focus:border-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300
                                      dark:placeholder-gray-400 dark:focus:border-blue-500">
                    </div>
                    <button @click="clearSearch()" x-show="searchQuery.length > 0"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700
                                   transition-colors shadow-md flex items-center gap-2">
                        <i class="fas fa-times"></i>
                        <span>Effacer</span>
                    </button>
                </div>

                <!-- Loading indicator -->
                <div x-show="loading" class="mt-2">
                    <div class="flex items-center text-blue-600 dark:text-blue-400">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        <span class="text-sm">Recherche en cours...</span>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Matricule
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nom
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Prénom
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Section
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Niveau
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                companie
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                        <template x-for="student in students">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150"
                                @click="openStudentModal(student.matricule)">

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300"
                                    x-text="student.matricule">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"
                                    x-text="student.nom">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"
                                    x-text="student.prenom">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"
                                    x-text="student.section_id || 'N/A'">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300"
                                    x-text="student.grade || 'N/A'">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                    x-text="student.section.companie || 'N/A'">
                                </td>
                            </tr>
                        </template>

                        <!-- Empty state -->
                        <tr x-show="students.length === 0 && !loading">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-users text-4xl mb-4 text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-lg font-medium mb-2">Aucun étudiant trouvé</p>
                                    <p class="text-sm" x-show="searchQuery.length > 0">
                                        Aucun résultat pour "<span x-text="searchQuery"></span>"
                                    </p>
                                    <p class="text-sm" x-show="searchQuery.length === 0">
                                        La liste des étudiants est vide
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Detail Modal -->
        <!-- Student Statistics Modal -->
        <div x-show="showStatsModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @click.self="closeStatsModal()" style="display: none;">

            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-50"></div>

            <!-- Modal container -->
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-6xl max-h-[90vh] overflow-hidden">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div
                                class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 rounded-full w-12 h-12 flex items-center justify-center text-lg font-bold">
                                <span x-text="statsData?.student?.matricule.toString().substring(0, 2)"></span>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                    x-text="statsData?.student?.nom + ' ' + statsData?.student?.prenom"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400"
                                    x-text="'Matricule: ' + statsData?.student?.matricule"></p>
                            </div>
                        </div>
                        <button @click="closeStatsModal()"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300
                               hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                        <!-- Loading state -->
                        <div x-show="statsLoading" class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <i class="fas fa-spinner fa-spin text-3xl text-blue-600 dark:text-blue-400 mb-4"></i>
                                <p class="text-gray-600 dark:text-gray-400">Chargement des statistiques...</p>
                            </div>
                        </div>

                        <!-- Error state -->
                        <div x-show="statsError && !statsLoading" class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
                                <p class="text-red-600 dark:text-red-400 mb-2">Erreur lors du chargement</p>
                                <p class="text-gray-600 dark:text-gray-400 text-sm" x-text="statsError"></p>
                                <button @click="loadStudentStats()"
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    Réessayer
                                </button>
                            </div>
                        </div>

                        <!-- Content area -->
                        <div x-show="!statsLoading && !statsError && statsData" class="space-y-8">
                            <!-- Quick Stats Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Sanctions Card -->
                                <div
                                    class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-700 dark:text-gray-300">Sanctions</h3>
                                        <span
                                            class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200 rounded-full text-xs"
                                            x-text="'Total: ' + statsData.sanctions.total"></span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Active</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sanctions.active.length"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Consigne</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sanctions.stats.consigne"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Arret</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sanctions.stats.arret"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reports Card -->
                                <div
                                    class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-700 dark:text-gray-300">Reports</h3>
                                        <span
                                            class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-full text-xs"
                                            x-text="'Total: ' + statsData.reports.stats.total"></span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Pending</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.reports.stats.pending"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Validated</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.reports.stats.validated"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Refused</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.reports.stats.refused"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Medical Card -->
                                <div
                                    class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-700 dark:text-gray-300">Medical</h3>
                                        <span
                                            class="px-2 py-1 bg-blue-100 dark:blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-xs"
                                            x-text="'Visits: ' + statsData.medical.patients.length"></span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Active Exemptions</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.exemptions.active.length"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Upcoming RDVs</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.rdvs.upcoming.length"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Convocation</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.medical.convocation ? 'Yes' : 'No'"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sorties Card -->
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-medium text-gray-700 dark:text-gray-300">Weekend Permissions
                                        </h3>
                                        <span
                                            class="px-2 py-1 bg-purple-100 dark:purple-900/30 text-purple-800 dark:text-purple-200 rounded-full text-xs"
                                            x-text="'Total: ' + statsData.sorties.stats.total"></span>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Vendredi</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sorties.stats.vendredi"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">Samedi</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sorties.stats.samedi"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-700 dark:text-gray-300">48h</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-200"
                                                x-text="statsData.sorties.stats.h48"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Detailed Sections with Tabs -->
                            <div class="bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                                <div class="border-b border-gray-200 dark:border-gray-600">
                                    <nav class="flex -mb-px overflow-x-auto">
                                        <button @click="activeStatsTab = 'sanctions'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'sanctions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'sanctions' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            Sanctions
                                        </button>
                                        <button @click="activeStatsTab = 'reports'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'reports' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            Reports
                                        </button>
                                        <button @click="activeStatsTab = 'medical'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'medical', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'medical' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            Medical
                                        </button>
                                        <button @click="activeStatsTab = 'rdvs'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'rdvs', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'rdvs' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            RDV
                                        </button>
                                        <button @click="activeStatsTab = 'exemptions'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'exemptions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'exemptions' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            Exemptions
                                        </button>
                                        <button @click="activeStatsTab = 'sorties'"
                                            :class="{ 'border-blue-500 text-blue-600 dark:text-blue-400': activeStatsTab === 'sorties', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeStatsTab !== 'sorties' }"
                                            class="whitespace-nowrap py-4 px-4 border-b-2 font-medium text-sm">
                                            Weekend Permissions
                                        </button>
                                    </nav>
                                </div>

                                <!-- Tab Contents -->
                                <div class="p-6">
                                    <!-- Sanctions Tab -->
                                    <div x-show="activeStatsTab === 'sanctions'">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Sanction History</h3>
                                            <button @click="openGraphModal('sanctions')"
                                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-bar"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        {{-- ! not gettong the histoey --}}
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Type</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Motif</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Period</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <template x-for="sanction in statsData.sanctions.active"
                                                        :key="sanction.id">
                                                        <tr class="bg-red-50 dark:bg-red-900/10">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200"
                                                                x-text="sanction.type"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="sanction.motif"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="sanction.date_debut + ' to ' + sanction.date_fin">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">Active</span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                    <template x-for="sanction in statsData.sanctions.past"
                                                        :key="sanction.id">
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200"
                                                                x-text="sanction.type"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="sanction.motif"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="sanction.date_debut + ' to ' + sanction.date_fin">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Completed</span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Reports Tab -->
                                    <div x-show="activeStatsTab === 'reports'" x-transition>
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Report History</h3>
                                            <button @click="openGraphModal('reports')"
                                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-pie"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Title</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Status</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Date</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Officer</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <template x-for="report in statsData.reports.reports"
                                                        :key="report.id">
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200"
                                                                x-text="report.title"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <template x-if="report.status === 'DONE'">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Validated</span>
                                                                </template>
                                                                <template x-if="report.status === 'REFUSED'">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">Refused</span>
                                                                </template>
                                                                <template
                                                                    x-if="report.status !== 'DONE' && report.status !== 'REFUSED'">
                                                                    <span
                                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">Pending</span>
                                                                </template>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="new Date(report.created_at).toLocaleDateString()">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="report.officer"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Medical Tab -->
                                    <div x-show="activeStatsTab === 'medical'" x-transition>
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Medical History</h3>
                                            <button @click="openGraphModal('medical')"
                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-area"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        <div class="space-y-6">
                                            <!-- Medical Visits -->
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Medical Visits</h4>
                                                <div class="overflow-x-auto">
                                                    <table
                                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Date</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Type</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Avis</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody
                                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                            <template x-for="patient in statsData.medical.patients"
                                                                :key="patient.id">
                                                                <tr>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="new Date(patient.created_at).toLocaleDateString()">
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="patient.type_medecin"></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="patient.avis_medecin"></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <template x-if="patient.valider">
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">Validated</span>
                                                                        </template>
                                                                        <template x-if="!patient.valider">
                                                                            <span
                                                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200">Pending</span>
                                                                        </template>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- Exemptions -->
                                            <div>
                                                <h4 class="text-md font-medium mb-2">Medical Exemptions</h4>
                                                <div class="overflow-x-auto">
                                                    <table
                                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Motif</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Period</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                                    Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody
                                                            class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                            <template x-for="exemption in statsData.exemptions.active"
                                                                :key="exemption.id">
                                                                <tr class="bg-red-50 dark:bg-red-900/10">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="exemption.motif"></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="exemption.date_debut + ' to ' + exemption.date_fin">
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span
                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200">Active</span>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                            <template x-for="exemption in statsData.exemptions.past"
                                                                :key="exemption.id">
                                                                <tr>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="exemption.motif"></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                        x-text="exemption.date_debut + ' to ' + exemption.date_fin">
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <span
                                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Expired</span>
                                                                    </td>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RDVs Tab -->
                                    <div x-show="activeStatsTab === 'rdvs'" x-transition>
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Appointment History</h3>
                                            <button @click="openGraphModal('rdvs')"
                                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-doughnut"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Motif</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Service</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Date</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <template
                                                        x-for="rdv in [...(statsData.rdvs?.upcoming || []), ...(statsData.rdvs?.past || [])]"
                                                        :key="rdv.date">
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                                                x-text="rdv.motif"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="rdv.service"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="new Date(rdv.date).toLocaleDateString()"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    :class="rdv.is_upcoming ?
                                                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'"
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                                    x-text="rdv.is_upcoming ? 'Upcoming' : 'Past'">
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Exemptions Tab -->
                                    <div x-show="activeStatsTab === 'exemptions'" x-transition>
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Exemption History</h3>
                                            <button @click="openGraphModal('exemptions')"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-bar"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Motif</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Start Date</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            End Date</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <template
                                                        x-for="exemption in [...(statsData.exemptions?.active || []), ...(statsData.exemptions?.past || [])]"
                                                        :key="exemption.date_debut">
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                                                x-text="exemption.motif"></td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="new Date(exemption.date_debut).toLocaleDateString()">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="new Date(exemption.date_fin).toLocaleDateString()">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <span
                                                                    :class="exemption.is_active ?
                                                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'"
                                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                                    x-text="exemption.is_active ? 'Active' : 'Expired'">
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Sorties Tab -->
                                    <div x-show="activeStatsTab === 'sorties'" x-transition>
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium">Weekend Permission History</h3>
                                            <button @click="openGraphModal('sorties')"
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-chart-line"></i>
                                                <span>Show Graph</span>
                                            </button>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-700">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Type</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Period</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                            Remarque</th>
                                                    </tr>
                                                </thead>
                                                <tbody
                                                    class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                                    <template x-for="sortie in statsData.sorties.sorties"
                                                        :key="sortie.id">
                                                        <tr>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                                                <template x-if="sortie.choix === 'ven'">
                                                                    <span>Vendredi</span>
                                                                </template>
                                                                <template x-if="sortie.choix === 'sam'">
                                                                    <span>Samedi</span>
                                                                </template>
                                                                <template x-if="sortie.choix === '48h'">
                                                                    <span>48h</span>
                                                                </template>
                                                                <template x-if="sortie.choix === '36h'">
                                                                    <span>36h</span>
                                                                </template>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="new Date(sortie.from).toLocaleDateString() + ' - ' + new Date(sortie.to).toLocaleDateString()">
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                                                x-text="sortie.remarque || 'N/A'"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graph Modal -->
        <div x-show="showGraphModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
            @click.self="closeGraphModal()">
            <div
                class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <span
                            x-text="'Graphiques - ' + (selectedStudent?.nom + ' ' + selectedStudent?.prenom || 'Étudiant')"></span>
                    </h3>
                    <button @click="closeGraphModal()"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="mt-4">
                    <!-- Graph Controls -->
                    <div class="mb-6 space-y-4">
                        <!-- Data Type and Graph Type Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Type de données
                                </label>
                                <select x-model="graphConfig.dataType"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    <option value="sorties">Sorties</option>
                                    <option value="sanctions">Sanctions</option>
                                    <option value="medical">Consultations médicales</option>
                                    <option value="reports">Rapports</option>
                                    <option value="rdvs">Rendez-vous</option>
                                    <option value="exemptions">Exemptions</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Type de graphique
                                </label>
                                <select x-model="graphConfig.graphType"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    <option value="line">Ligne</option>
                                    <option value="bar">Barres</option>
                                    <option value="pie">Camembert</option>
                                    <option value="doughnut">Anneau</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range and Time Unit (only for timeline charts) -->
                        <div x-show="['line', 'bar'].includes(graphConfig.graphType)"
                            class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date de début
                                </label>
                                <input type="date" x-model="graphConfig.from"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date de fin
                                </label>
                                <input type="date" x-model="graphConfig.to"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Unité de temps
                                </label>
                                <select x-model="graphConfig.unit"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                    <option value="days">Jours</option>
                                    <option value="weeks">Semaines</option>
                                    <option value="months">Mois</option>
                                </select>
                            </div>
                        </div>

                        <!-- Date Range for pie/doughnut charts -->
                        <div x-show="['pie', 'doughnut'].includes(graphConfig.graphType)"
                            class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date de début
                                </label>
                                <input type="date" x-model="graphConfig.from"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Date de fin
                                </label>
                                <input type="date" x-model="graphConfig.to"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                            </div>
                        </div>

                        <!-- Generate Button -->
                        <div class="flex justify-center">
                            <button @click="loadGraphData()" :disabled="graphLoading"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-md flex items-center gap-2">
                                <i class="fas fa-chart-line" x-show="!graphLoading"></i>
                                <i class="fas fa-spinner fa-spin" x-show="graphLoading"></i>
                                <span x-text="graphLoading ? 'Génération...' : 'Générer le graphique'"></span>
                            </button>
                        </div>
                    </div>

                    <!-- Graph Container -->
                    <div class="mt-6" x-show="!graphLoading && chartInstance">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <canvas id="studentChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div x-show="graphLoading" class="flex justify-center items-center py-8">
                        <div class="text-center">
                            <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">Génération du graphique...</p>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div x-show="graphError"
                        class="mt-4 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 mr-2"></i>
                            <span class="text-red-700 dark:text-red-300" x-text="graphError"></span>
                        </div>
                    </div>

                    <!-- No Data State -->
                    <div x-show="!graphLoading && !chartInstance && !graphError"
                        class="flex justify-center items-center py-8">
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-chart-line text-4xl mb-4"></i>
                            <p>Configurez les paramètres et cliquez sur "Générer le graphique"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function studentStatsModal() {
                return {
                    showStatsModal: false,
                    statsLoading: false,
                    statsError: null,
                    statsData: null,
                    activeStatsTab: 'sanctions',
                    currentStudentMatricule: null,

                    async openStatsModal(matricule) {
                        this.currentStudentMatricule = matricule;
                        this.showStatsModal = true;
                        this.statsLoading = true;
                        this.statsError = null;
                        await this.loadStudentStats();
                    },

                    async loadStudentStats() {
                        if (!this.currentStudentMatricule) {
                            this.statsError = 'No student matricule provided';
                            this.statsLoading = false;
                            return;
                        }

                        try {
                            const response = await fetch(`/show/${this.currentStudentMatricule}`, {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                }
                            });

                            if (response.ok) {
                                const data = await response.json();
                                this.statsData = data;

                                // Ensure sanctions data exists (from your controller analysis)
                                if (!this.statsData.sanctions) {
                                    this.statsData.sanctions = {
                                        active: [],
                                        past: [],
                                        total: 0,
                                        stats: {
                                            consigne: 0,
                                            arret: 0,
                                            blame: 0,
                                            avert: 0
                                        }
                                    };
                                }
                            } else {
                                const errorData = await response.json().catch(() => ({}));
                                this.statsError = errorData.error || `Error ${response.status}: ${response.statusText}`;
                            }
                        } catch (error) {
                            console.error('Error loading student stats:', error);
                            this.statsError = 'Connection error. Please try again.';
                        } finally {
                            this.statsLoading = false;
                        }
                    },

                    closeStatsModal() {
                        this.showStatsModal = false;
                        this.statsLoading = false;
                        this.statsError = null;
                        this.statsData = null;
                        this.currentStudentMatricule = null;
                        this.activeStatsTab = 'sanctions';
                    }
                }
            }
        </script>
    </div>

    <script>
        function studentsList() {
            return {
                students: [],
                searchQuery: '',
                loading: false,
                showModal: false,
                modalLoading: false,
                modalError: null,
                studentDetail: null,
                currentStudentId: null,

                // Statistics modal properties
                showStatsModal: false,
                statsLoading: false,
                statsError: null,
                statsData: null,
                activeStatsTab: 'sanctions',
                currentStudentMatricule: null,

                // Graph modal properties
                showGraphModal: false,
                graphLoading: false,
                graphError: null,
                selectedStudent: null,
                chartInstance: null,
                graphConfig: {
                    dataType: 'sorties',
                    graphType: 'line',
                    from: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
                    to: new Date().toISOString().split('T')[0],
                    unit: 'days'
                },

                async init() {
                    // Load students data when component initializes
                    await this.loadStudents();
                },

                async loadStudents() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('students.index') }}', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.students = data.students || [];
                        } else {
                            console.error('Failed to load students:', response.status);
                            this.students = [];
                        }
                    } catch (error) {
                        console.error('Error loading students:', error);
                        this.students = [];
                    } finally {
                        this.loading = false;
                    }
                },

                async searchStudents() {
                    // Trim whitespace from search query
                    const query = this.searchQuery.trim();

                    if (query === '') {
                        // If search is empty, reload all students
                        await this.loadStudents();
                        return;
                    }

                    // Validate that search query is numeric
                    if (!/^\d+$/.test(query)) {
                        console.error('Search query must be numeric');
                        this.students = [];
                        return;
                    }

                    // Validate search query length (max 7 digits for matricule)
                    if (query.length > 7) {
                        console.error('Search query too long');
                        this.students = [];
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch('{{ route('student.search') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                search: query
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.students = data.students || [];
                        } else {
                            const errorData = await response.json().catch(() => ({}));
                            console.error('Search failed:', response.status, errorData.message || response.statusText);
                            this.students = [];
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                        this.students = [];
                    } finally {
                        this.loading = false;
                    }
                },

                clearSearch() {
                    this.searchQuery = '';
                    // Reload all students when clearing search
                    this.loadStudents();
                },

                async openStudentModal(studentId) {
                    this.currentStudentId = studentId;
                    this.currentStudentMatricule = studentId; // Use matricule as ID
                    this.showModal = true;
                    this.modalLoading = true;
                    this.modalError = null;
                    this.studentDetail = null;

                    // Open both modals (detail and stats)
                    await Promise.all([
                        this.loadStudentDetail(),
                        this.openStatsModal(studentId)
                    ]);
                },

                async loadStudentDetail() {
                    if (!this.currentStudentId) {
                        this.modalError = 'No student ID provided';
                        this.modalLoading = false;
                        return;
                    }

                    try {
                        const response = await fetch(`{{ route('student.show', '') }}/${this.currentStudentId}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();

                            // Handle different response structures
                            if (data.student) {
                                this.studentDetail = data;
                            } else if (data.matricule) {
                                // If the response directly contains student data
                                this.studentDetail = {
                                    student: data
                                };
                            } else {
                                this.modalError = 'Invalid response format';
                            }
                        } else {
                            const errorData = await response.json().catch(() => ({}));

                            if (response.status === 403) {
                                this.modalError = 'Access denied - You do not have permission to view this student';
                            } else if (response.status === 404) {
                                this.modalError = 'Student not found';
                            } else {
                                this.modalError = `Error ${response.status}: ${errorData.error || response.statusText}`;
                            }
                        }
                    } catch (error) {
                        console.error('Error loading student details:', error);
                        this.modalError = 'Connection error. Please try again.';
                    } finally {
                        this.modalLoading = false;
                    }
                },

                async openStatsModal(matricule) {
                    this.currentStudentMatricule = matricule;
                    this.showStatsModal = true;
                    this.statsLoading = true;
                    this.statsError = null;
                    await this.loadStudentStats();
                },

                async loadStudentStats() {
                    if (!this.currentStudentMatricule) {
                        this.statsError = 'No student matricule provided';
                        this.statsLoading = false;
                        return;
                    }

                    try {
                        const response = await fetch(`/show/${this.currentStudentMatricule}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.statsData = data;

                            // Ensure sanctions data exists
                            if (!this.statsData.sanctions) {
                                this.statsData.sanctions = {
                                    active: [],
                                    past: [],
                                    total: 0,
                                    stats: {
                                        consigne: 0,
                                        arret: 0,
                                        blame: 0,
                                        avert: 0
                                    }
                                };
                            }
                        } else {
                            const errorData = await response.json().catch(() => ({}));
                            this.statsError = errorData.error || `Error ${response.status}: ${response.statusText}`;
                        }
                    } catch (error) {
                        console.error('Error loading student stats:', error);
                        this.statsError = 'Connection error. Please try again.';
                    } finally {
                        this.statsLoading = false;
                    }
                },

                closeModal() {
                    this.showModal = false;
                    this.modalLoading = false;
                    this.modalError = null;
                    this.studentDetail = null;
                    this.currentStudentId = null;
                },

                closeStatsModal() {
                    this.showStatsModal = false;
                    this.statsLoading = false;
                    this.statsError = null;
                    this.statsData = null;
                    this.currentStudentMatricule = null;
                    this.activeStatsTab = 'sanctions';
                },

                async retryLoadStudentDetail() {
                    this.modalLoading = true;
                    this.modalError = null;
                    await this.loadStudentDetail();
                },

                // Graph functionality
                openGraphModal(dataType = 'sorties') {
                    this.graphConfig.dataType = dataType;
                    this.showGraphModal = true;
                    this.graphError = null;
                    this.chartInstance = null;

                    // Set selectedStudent from statsData if available
                    if (this.statsData?.student) {
                        this.selectedStudent = this.statsData.student;
                    }

                    // Ensure we have the current student matricule set
                    if (!this.currentStudentMatricule && this.statsData?.student?.matricule) {
                        this.currentStudentMatricule = this.statsData.student.matricule;
                    }
                },

                closeGraphModal() {
                    this.showGraphModal = false;
                    this.graphError = null;
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                        this.chartInstance = null;
                    }
                },

                async loadGraphData() {
                    if (!this.currentStudentMatricule) {
                        this.graphError = 'Aucun étudiant sélectionné';
                        return;
                    }

                    this.graphLoading = true;
                    this.graphError = null;

                    try {
                        console.log('Loading graph data for:', this.currentStudentMatricule, this.graphConfig);

                        const requestBody = {
                            data_type: this.graphConfig.dataType,
                            graph_type: this.graphConfig.graphType,
                            from: this.graphConfig.from,
                            to: this.graphConfig.to,
                            unit: this.graphConfig.unit
                        };

                        const response = await fetch(
                            `/student/${this.currentStudentMatricule}/all-graph-data`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify(requestBody)
                            }
                        );

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            console.error('Graph response error:', response.status, errorData);
                            throw new Error(errorData.message || `Erreur serveur: ${response.status}`);
                        }

                        const data = await response.json();
                        console.log('Graph data received:', data);

                        if (!data.success) {
                            throw new Error(data.message || 'Erreur lors de la génération du graphique');
                        }

                        this.updateChart(data.data);
                    } catch (error) {
                        console.error('Graph error:', error);
                        this.graphError = error.message || 'Échec de génération du graphique';
                    } finally {
                        this.graphLoading = false;
                    }
                },

                updateChart(data) {
                    const ctx = document.getElementById('studentChart').getContext('2d');

                    console.log('Updating chart with data:', data);

                    if (!window.Chart) {
                        console.error('Chart.js not loaded');
                        this.graphError = 'Bibliothèque Chart.js non chargée';
                        return;
                    }

                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    // Get chart configuration based on type and data
                    const chartConfig = this.getChartConfig(data);

                    try {
                        this.chartInstance = new Chart(ctx, chartConfig);
                        console.log('Chart created successfully');
                    } catch (error) {
                        console.error('Chart creation error:', error);
                        this.graphError = 'Erreur lors de la création du graphique';
                    }
                },

                getChartConfig(data) {
                    const dataTypeLabels = {
                        sorties: 'Sorties',
                        sanctions: 'Sanctions',
                        medical: 'Visites médicales',
                        reports: 'Rapports',
                        rdvs: 'Rendez-vous',
                        exemptions: 'Exemptions'
                    };

                    const dataTypeColors = {
                        sorties: {
                            border: 'rgb(34, 197, 94)',
                            background: 'rgba(34, 197, 94, 0.1)'
                        },
                        sanctions: {
                            border: 'rgb(239, 68, 68)',
                            background: 'rgba(239, 68, 68, 0.1)'
                        },
                        medical: {
                            border: 'rgb(59, 130, 246)',
                            background: 'rgba(59, 130, 246, 0.1)'
                        },
                        reports: {
                            border: 'rgb(245, 158, 11)',
                            background: 'rgba(245, 158, 11, 0.1)'
                        },
                        rdvs: {
                            border: 'rgb(168, 85, 247)',
                            background: 'rgba(168, 85, 247, 0.1)'
                        },
                        exemptions: {
                            border: 'rgb(6, 182, 212)',
                            background: 'rgba(6, 182, 212, 0.1)'
                        }
                    };

                    const label = dataTypeLabels[this.graphConfig.dataType] || 'Données';
                    const colors = dataTypeColors[this.graphConfig.dataType] || dataTypeColors.sorties;

                    const baseConfig = {
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: label,
                                data: data.data,
                                borderColor: colors.border,
                                backgroundColor: colors.background,
                                borderWidth: 2
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
                                title: {
                                    display: true,
                                    text: `Graphique des ${label.toLowerCase()}`
                                }
                            }
                        }
                    };

                    // Configure based on chart type
                    switch (this.graphConfig.graphType) {
                        case 'line':
                            return {
                                type: 'line',
                                    ...baseConfig,
                                    data: {
                                        ...baseConfig.data,
                                        datasets: [{
                                            ...baseConfig.data.datasets[0],
                                            fill: true,
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        ...baseConfig.options,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    }
                            };

                        case 'bar':
                            return {
                                type: 'bar',
                                    ...baseConfig,
                                    options: {
                                        ...baseConfig.options,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    }
                            };

                        case 'pie':
                        case 'doughnut':
                            // For pie/doughnut charts, use category data
                            const pieColors = [
                                'rgba(239, 68, 68, 0.8)', // Red
                                'rgba(34, 197, 94, 0.8)', // Green
                                'rgba(59, 130, 246, 0.8)', // Blue
                                'rgba(245, 158, 11, 0.8)', // Yellow
                                'rgba(168, 85, 247, 0.8)', // Purple
                                'rgba(6, 182, 212, 0.8)', // Cyan
                                'rgba(236, 72, 153, 0.8)', // Pink
                                'rgba(75, 85, 99, 0.8)' // Gray
                            ];

                            return {
                                type: this.graphConfig.graphType,
                                    data: {
                                        labels: data.labels,
                                        datasets: [{
                                            label: label,
                                            data: data.data,
                                            backgroundColor: pieColors.slice(0, data.labels.length),
                                            borderWidth: 1,
                                            borderColor: '#fff'
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'right'
                                            },
                                            title: {
                                                display: true,
                                                text: `Répartition des ${label.toLowerCase()}`
                                            }
                                        }
                                    }
                            };

                        default:
                            return baseConfig;
                    }
                },

                goToProfile(studentId) {
                    window.location.href = `/student/profile/${studentId}`;
                },

                // Helper method to format student name
                formatStudentName(student) {
                    return `${student.grade || ''} ${student.nom || ''} ${student.prenom || ''}`.trim();
                },

                // Helper method to get section display
                getSectionDisplay(student) {
                    if (student.section) {
                        return `Section ${student.section.num} - ${student.section.companie}`;
                    }
                    return 'No section assigned';
                },

                // Helper method to check if student is consigned
                isConsigned(student) {
                    return student.consigned === 1 || student.consigned === true;
                }
            }
        }
    </script>
</x-brigade>
