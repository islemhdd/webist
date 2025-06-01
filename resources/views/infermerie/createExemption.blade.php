<x-infermerie css='liste_exemption'>
    <div class="container mx-auto p py-6">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold text-sky-400 dark:text-gray-200 mb-6 flex justify-center">Nouvelle exemption</h2>
                    <form action="{{ route('exemptions.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Matricule</label>
                            <input type="text"
                                   id="matricule"
                                   name="matricule"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100"
                                   required>
                        </div>

                        <div>
                            <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Motif</label>
                            <select name="motif"
                                    id="motif"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100"
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
                            <label for="date_debut" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de début</label>
                            <input type="date"
                                   id="date_debut"
                                   name="date_debut"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100"
                                   required>
                        </div>

                        <div>
                            <label for="date_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date de fin</label>
                            <input type="date"
                                   id="date_fin"
                                   name="date_fin"
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 hover:bg-gray-100"
                                   required>
                        </div>

                        <div class="pt-4 flex justify-center">
                            <button type="submit" class="px-6 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-md hover:shadow-lg transition-all duration-200 ">
                                Ajouter l'exemption
                            </button>
                        </div>
                    </form>
                </div>
            

        
    </div>
</x-infermerie>
