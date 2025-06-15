<x-brigade css="rdv-list">
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="rdvList()" x-init="init()">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Rendez-vous de Demain</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ now()->addDay()->format('d/m/Y') }} - <span x-text="rdvs.length"></span> rendez-vous
                        programmés
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total:</span>
                    <span
                        class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium"
                        x-text="filteredRdvs.length"></span>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchRdvs()"
                        placeholder="Rechercher par matricule, nom, motif ou service..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="flex items-center justify-center py-8">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-600 dark:text-blue-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">Recherche en cours...</p>
                </div>
            </div>

            <!-- RDV Table -->
            <div x-show="!loading" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Étudiant
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Section
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Motif
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Service
                            </th>

                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Créé le
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="rdv in paginatedRdvs">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                            x-text="rdv.student_name"></div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400"
                                            x-text="'Matricule: ' + rdv.matricule"></div>
                                    </div>
            </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="rdv.section"></td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span
                    :class="rdv.motif === 'urgences' ?
                        'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200' :
                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200'"
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                    x-text="rdv.motif"></span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100" x-text="rdv.service"></td>

            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="rdv.created_at">
            </td>
            </tr>
            </template>

            <!-- No results message -->
            <tr x-show="filteredRdvs.length === 0 && !loading">
                <td colspan="6" class="px-6 py-8 text-center">
                    <div class="text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar-times text-3xl mb-4"></i>
                        <p class="text-lg font-medium">Aucun rendez-vous trouvé</p>
                        <p class="text-sm" x-show="searchQuery">Essayez de modifier vos critères de
                            recherche</p>
                        <p class="text-sm" x-show="!searchQuery">Aucun rendez-vous programmé pour demain</p>
                    </div>
                </td>
            </tr>
            </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div x-show="!loading && filteredRdvs.length > perPage"
            class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-700 dark:text-gray-300">Afficher</span>
                <select x-model="perPage" @change="updatePagination()"
                    class="border border-gray-300 dark:border-gray-600 rounded px-2 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-700 dark:text-gray-300">par page</span>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-700 dark:text-gray-300"
                    x-text="`Affichage ${startIndex + 1} à ${Math.min(endIndex, filteredRdvs.length)} sur ${filteredRdvs.length} résultats`"></span>
            </div>

            <div class="flex items-center space-x-1">
                <button @click="goToPage(1)" :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-l">
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 border-t border-b border-gray-300 dark:border-gray-600">
                    <i class="fas fa-angle-left"></i>
                </button>

                <template x-for="page in visiblePages">
                    <button @click="goToPage(page)"
                        :class="page === currentPage ? 'bg-blue-500 text-white' :
                            'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'"
                        class="px-3 py-2 text-sm border-t border-b border-gray-300 dark:border-gray-600"
                        x-text="page"></button>
                </template>

                <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' :
                        'hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 border-t border-b border-gray-300 dark:border-gray-600">
                    <i class="fas fa-angle-right"></i>
                </button>
                <button @click="goToPage(totalPages)" :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' :
                        'hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-r">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>
        </div>
    </div>
    </div>
    <script>
        function rdvList() {
            return {
                rdvs: [],
                filteredRdvs: [],
                paginatedRdvs: [],
                searchQuery: '',
                loading: false,

                // Pagination properties
                currentPage: 1,
                perPage: 10,

                async init() {
                    await this.loadRdvs();
                },

                async loadRdvs() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('brigade.rdv-list', $officer->id) }}', {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            this.rdvs = data.rdvs || [];
                            console.log(data);
                            this.filteredRdvs = this.rdvs;
                            this.updatePagination();
                        } else {
                            console.error('Failed to load RDVs:', response.status);
                            this.rdvs = [];
                            this.filteredRdvs = [];
                        }
                    } catch (error) {
                        console.error('Error loading RDVs:', error);
                        this.rdvs = [];
                        this.filteredRdvs = [];
                    } finally {
                        this.loading = false;
                    }
                },

                get totalPages() {
                    return Math.ceil(this.filteredRdvs.length / this.perPage);
                },

                get startIndex() {
                    return (this.currentPage - 1) * this.perPage;
                },

                get endIndex() {
                    return this.startIndex + this.perPage;
                },

                get visiblePages() {
                    const pages = [];
                    const maxVisible = 5;
                    let start = Math.max(1, this.currentPage - Math.floor(maxVisible / 2));
                    let end = Math.min(this.totalPages, start + maxVisible - 1);

                    if (end - start + 1 < maxVisible) {
                        start = Math.max(1, end - maxVisible + 1);
                    }

                    for (let i = start; i <= end; i++) {
                        pages.push(i);
                    }
                    return pages;
                },

                updatePagination() {
                    this.currentPage = 1;
                    this.paginatedRdvs = this.filteredRdvs.slice(this.startIndex, this.endIndex);
                },

                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        this.paginatedRdvs = this.filteredRdvs.slice(this.startIndex, this.endIndex);
                    }
                },

                async searchRdvs() {
                    if (this.searchQuery.trim() === '') {
                        this.filteredRdvs = this.rdvs;
                        this.updatePagination();
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch(
                            `{{ route('brigade.rdv-list.search', $officer->id) }}?search=${encodeURIComponent(this.searchQuery)}`, {
                                method: 'GET',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                }
                            });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();
                        this.filteredRdvs = data.rdvs;
                        this.updatePagination();
                        console.log(data.rdvs);
                    } catch (error) {
                        console.error('Search error:', error);
                        this.filteredRdvs = [];
                        this.updatePagination();
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-brigade>
