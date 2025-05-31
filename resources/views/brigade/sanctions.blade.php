<x-brigade css="list sanctions">

    <div class="max-w-7xl mx-auto py-8 px-4" x-data="{
        sanctions: [],
        loading: true,
        type: '',
        status: '',
        async fetchSanctions() {
            this.loading = true;
            try {

                const params = new URLSearchParams();
                if (this.type) params.append('type', this.type);
                if (this.status) params.append('status', this.status);

                const response = await fetch(`/{{ $id->id }}/sanctions/?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Erreur réseau');

                this.sanctions = await response.json();

            } catch (error) {
                console.error('Erreur:', error);
                this.sanctions = [];
            } finally {
                this.loading = false;
            }

        }
    }" @sanctions-updated="fetchSanctions"
        x-init="fetchSanctions">

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Header with Add Button -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Liste des Sanctions</h2>
                <button @click="$dispatch('open-modal', {type: 'create-sanction'})"
                    class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors shadow-md flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    <span>Ajouter Sanction</span>
                </button>
            </div>

            <!-- Filters -->
            <div class="mb-6 flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <label class="text-gray-700 dark:text-gray-300">Type de sanction:</label>
                    <select x-model="type" @change="fetchSanctions()"
                        class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">Tout</option>
                        <option value="consigne">Consigne</option>
                        <option value="arret">Arrêt</option>
                        <option value="blame">Blâme</option>
                        <option value="avert">Avertissement</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-gray-700 dark:text-gray-300">Status:</label>
                    <select x-model="status" @change="fetchSanctions()"
                        class="form-select rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        <option value="">Tout</option>
                        <option value="active">Active</option>
                        <option value="inactive">Terminée</option>
                    </select>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="flex justify-center items-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <!-- Students Table -->
            <div x-show="!loading" class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b dark:border-gray-700">
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Matricule
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Nom Complet
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Type
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Motif
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Période
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Status
                            </th>
                            <th
                                class="px-4 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        <template x-if="sanctions.length === 0">
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-8 text-sm text-gray-500 dark:text-gray-400 text-center">
                                    Aucune sanction trouvée
                                </td>
                            </tr>
                        </template>
                        <template x-for="sanction in sanctions" :key="sanction.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100"
                                    x-text="sanction.matricule"></td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100"
                                    x-text="sanction.full_name"></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': sanction.type === 'consigne',
                                            'bg-red-100 text-red-800': sanction.type === 'arret',
                                            'bg-purple-100 text-purple-800': sanction.type === 'blame',
                                            'bg-blue-100 text-blue-800': sanction.type === 'avert'
                                        }"
                                        x-text="sanction.type">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100" x-text="sanction.motif">
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    <span
                                        x-text="'Du ' + new Date(sanction.date_debut).toLocaleDateString('fr-FR')"></span>
                                    <span
                                        x-text="'au ' + new Date(sanction.date_fin).toLocaleDateString('fr-FR')"></span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold"
                                        :class="sanction.is_active ?
                                            'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' :
                                            'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300'"
                                        x-text="sanction.is_active ? 'Active' : 'Terminée'">
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-3">
                                    <button
                                        @click="$dispatch('open-modal', {type: 'view-sanction', sanction: sanction})"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        @click="$dispatch('open-modal', {type: 'edit-sanction', sanction: sanction})"
                                        class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button
                                        @click="$dispatch('open-modal', {type: 'delete-sanction', sanction: sanction})"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Sanction Modal -->
    <div x-data="{
        showModal: false,
        modalType: '',
        formData: {
            matricule: '',
            type: 'consigne',
            date_debut: '',
            date_fin: '',
            motif: ''
        },
        errorMessage: '',
        async submitForm() {
            try {
                const url = this.modalType === 'create' ?
                    `/sanctions/{{ auth()->user()->id }}` :
                    `/sanctions/{{ auth()->user()->id }}/` + this.formData.id;

                const method = this.modalType === 'create' ? 'POST' : 'PUT';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify(this.formData)
                });

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Une erreur est survenue');
                }

                this.showModal = false;
                this.formData = {
                    matricule: '',
                    type: 'consigne',
                    date_debut: '',
                    date_fin: '',
                    motif: ''
                };
                this.$dispatch('sanctions-updated');
            } catch (error) {
                this.errorMessage = error.message;
            }
        }
    }"
        @open-modal.window="
            if ($event.detail.type === 'create-sanction') {
                modalType = 'create';
                showModal = true;
            } else if ($event.detail.type === 'edit-sanction') {
                modalType = 'edit';
                formData = {...$event.detail.sanction};
                showModal = true;
            }
        "
        x-show="showModal" class="fixed inset-0 z-50" x-cloak>

        <!-- Modal backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showModal = false"></div>

        <!-- Modal content -->
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-md mx-4"
                @click.away="showModal = false">

                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                        x-text="modalType === 'create' ? 'Ajouter une Sanction' : 'Modifier la Sanction'">
                    </h3>
                    <button @click="showModal = false"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Error Message -->
                <div x-show="errorMessage"
                    class="mb-4 bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="text-sm font-medium" x-text="errorMessage"></span>
                </div>

                <!-- Form -->
                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Matricule de l'étudiant <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="formData.matricule" id="matricule" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Entrez le matricule">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Type de sanction <span class="text-red-500">*</span>
                        </label>
                        <select x-model="formData.type" id="type" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <option value="consigne">Consigne</option>
                            <option value="arret">Arrêt</option>
                            <option value="blame">Blâme</option>
                            <option value="avert">Avertissement</option>
                        </select>
                    </div>

                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Motif <span class="text-red-500">*</span>
                        </label>
                        <textarea x-model="formData.motif" id="motif" required rows="3"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                            placeholder="Entrez le motif de la sanction"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date_debut"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Date début <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="formData.date_debut" id="date_debut" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>
                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Date fin <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="formData.date_fin" id="date_fin" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                                   hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg shadow-sm
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            x-text="modalType === 'create' ? 'Ajouter' : 'Modifier'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{
        showDeleteModal: false,
        sanctionToDelete: null,
        async deleteSanction() {
            try {
                const response = await fetch(`/sanctions/{{ auth()->user()->id }}/${this.sanctionToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    }
                });

                if (!response.ok) throw new Error('Erreur lors de la suppression');

                this.showDeleteModal = false;
                this.sanctionToDelete = null;
                this.$dispatch('sanctions-updated');
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
    }"
        @open-modal.window="
        if ($event.detail.type === 'delete-sanction') {
            showDeleteModal = true;
            sanctionToDelete = $event.detail.sanction;
        }
    "
        x-show="showDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" x-cloak>
        <div class="fixed inset-0 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-sm mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Confirmer la suppression
                    </h3>
                    <button @click="showDeleteModal = false"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Êtes-vous sûr de vouloir supprimer cette sanction ?
                </p>

                <div class="flex justify-end space-x-3">
                    <button @click="showDeleteModal = false"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                               hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors">
                        Annuler
                    </button>
                    <button @click="deleteSanction()"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg shadow-sm
                               hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        Alpine.start();
    </script>

</x-brigade>
