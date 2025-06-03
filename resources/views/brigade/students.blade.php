<x-brigade css="list students">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="studentsList()">
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
                                    <nav class="flex -mb-px">
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
                                        <h3 class="text-lg font-medium mb-4">Sanction History</h3>
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
                                        <h3 class="text-lg font-medium mb-4">Report History</h3>
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
                                        <h3 class="text-lg font-medium mb-4">Medical History</h3>
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

                                    <!-- Sorties Tab -->
                                    <div x-show="activeStatsTab === 'sorties'" x-transition>
                                        <h3 class="text-lg font-medium mb-4">Weekend Permission History</h3>
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
                students: @json($students ?? []),
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

                async searchStudents() {
                    // Trim whitespace from search query
                    const query = this.searchQuery.trim();

                    if (query === '') {
                        // If search is empty, show all students (or reload initial data)
                        this.students = @json($students ?? []);
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
                    this.students = @json($students ?? []);
                },

                async openStudentModal(studentId) {
                    this.currentStudentId = studentId;
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
