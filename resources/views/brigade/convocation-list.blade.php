<x-brigade>
    <div class="max-w-7xl mx-auto py-8 px-4" x-data="convocationList()">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Convocations Médicales</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ now()->format('d/m/Y') }} - {{ $convocations->total() }} convocations actives
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total:</span>
                    <span
                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 rounded-full text-sm font-medium"
                        x-text="filteredConvocations.length"></span>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchConvocations()"
                        placeholder="Rechercher par matricule ou nom d'étudiant..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="flex items-center justify-center py-8">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin text-3xl text-green-600 dark:text-green-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">Recherche en cours...</p>
                </div>
            </div>

            <!-- Convocations Table -->
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
                                Services Requis
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Psychiatre
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Médecin Général
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Chirurgien Dentiste
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Avis Spécialisé
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Créé le
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="convocation in filteredConvocations" :key="convocation.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-200 rounded-full w-10 h-10 flex items-center justify-center text-sm font-bold mr-3">
                                            <span x-text="convocation.matricule.toString().substring(0, 2)"></span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                                x-text="convocation.student_name"></div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400"
                                                x-text="'Matricule: ' + convocation.matricule"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
                                    x-text="convocation.section"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        <template x-for="service in convocation.services" :key="service">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-200 mr-1"
                                                x-text="service"></span>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <template x-if="convocation.psy">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                    </template>
                                    <template x-if="!convocation.psy">
                                        <i class="fas fa-times-circle text-gray-300 text-lg"></i>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <template x-if="convocation.medGen">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                    </template>
                                    <template x-if="!convocation.medGen">
                                        <i class="fas fa-times-circle text-gray-300 text-lg"></i>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <template x-if="convocation.chirDent">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                    </template>
                                    <template x-if="!convocation.chirDent">
                                        <i class="fas fa-times-circle text-gray-300 text-lg"></i>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <template x-if="convocation.avisSpe">
                                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                    </template>
                                    <template x-if="!convocation.avisSpe">
                                        <i class="fas fa-times-circle text-gray-300 text-lg"></i>
                                    </template>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"
                                    x-text="convocation.created_at"></td>
                            </tr>
                        </template>

                        <!-- No results message -->
                        <tr x-show="filteredConvocations.length === 0 && !loading">
                            <td colspan="8" class="px-6 py-8 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-user-clock text-3xl mb-4"></i>
                                    <p class="text-lg font-medium">Aucune convocation trouvée</p>
                                    <p class="text-sm" x-show="searchQuery">Essayez de modifier vos critères de
                                        recherche</p>
                                    <p class="text-sm" x-show="!searchQuery">Aucune convocation médicale active
                                        actuellement</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> <!-- Pagination would go here if needed -->
            <div class="mt-6">
                {{ $convocations->links() }}
            </div>
        </div>
    </div>

    <script>
        function convocationList() {
            return {
                convocations: @json(
                    $convocations->items()->map(function ($convocation) {
                        $services = [];
                        if ($convocation->psy) {
                            $services[] = 'Psychiatre';
                        }
                        if ($convocation->medGen) {
                            $services[] = 'Médecin Général';
                        }
                        if ($convocation->chirDent) {
                            $services[] = 'Chirurgien Dentiste';
                        }
                        if ($convocation->avisSpe) {
                            $services[] = 'Avis Spécialisé';
                        }
                
                        return [
                            'id' => $convocation->id,
                            'matricule' => $convocation->matricule,
                            'student_name' => $convocation->student->nom . ' ' . $convocation->student->prenom,
                            'section' => $convocation->student->section->name ?? 'N/A',
                            'services' => $services,
                            'services_text' => implode(', ', $services),
                            'psy' => $convocation->psy,
                            'medGen' => $convocation->medGen,
                            'chirDent' => $convocation->chirDent,
                            'avisSpe' => $convocation->avisSpe,
                            'created_at' => $convocation->created_at->format('d/m/Y'),
                            'formatted_date' => $convocation->created_at->format('d/m/Y H:i'),
                        ];
                    })),
                filteredConvocations: [],
                searchQuery: '',
                loading: false,

                init() {
                    this.filteredConvocations = this.convocations;
                },

                async searchConvocations() {
                    if (this.searchQuery.trim() === '') {
                        this.filteredConvocations = this.convocations;
                        return;
                    }

                    this.loading = true;

                    try {
                        const response = await fetch(
                            `{{ route('brigade.convocation-list.search', $officer->id) }}?search=${encodeURIComponent(this.searchQuery)}`, {
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
                        this.filteredConvocations = data.convocations;
                    } catch (error) {
                        console.error('Search error:', error);
                        this.filteredConvocations = [];
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</x-brigade>
