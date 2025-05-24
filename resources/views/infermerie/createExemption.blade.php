<x-infermerie css='liste_exemption'>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Créer un rendez-vous</h2>
 <!-- Formulaire d'ajout -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Nouvelle exemption</h2>
                    <form action="{{ route('exemptions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule</label>
                            <input type="text"
                                   id="matricule"
                                   name="matricule"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                   required>
                        </div>

                        <div>
                            <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motif</label>
                            <select name="motif"
                                    id="motif"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                    required>
                                <option value="">Sélectionner un motif</option>
                                <option value="exemption effort physique">Une exemption d'effort physique</option>
                                <option value="exemption rasage barbe">Une exemption de rasage de barbe</option>
                                <option value="exemption rangers">Une exemption du port de rangers</option>
                                <option value="prolongation arret">Une prolongation d'arrêt</option>
                                <option value="reprise travail">Une reprise de travail</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                            <input type="date"
                                   id="date_debut"
                                   name="date_debut"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                   required>
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                            <input type="date"
                                   id="date_fin"
                                   name="date_fin"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                   required>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                                Ajouter l'exemption
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-infermerie>
