<x-infermerie css='fiche'>
    <div class="max-w-6xl mx-auto py-8 px-2">
        <!-- Header with user info -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl shadow-lg p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="/profile.jpg" alt="photo" class="w-24 h-24 rounded-full border-4 border-pink-500/20">
                    <div class="absolute -top-2 -right-2 bg-pink-500 text-white rounded-full p-2">
                        <span class="text-xs font-medium">MED</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-1">
                                <label class="text-sm text-gray-100 dark:text-gray-400">Nom:</label>
                                <p class="text-lg font-semibold text-white">{{ $Student->nom }}</p>
                            </div>
                            <div class="space-y-1 mt-4">
                                <label class="text-sm text-gray-100 dark:text-gray-400">Prénom:</label>
                                <p class="text-lg font-semibold text-white">{{ $Student->prenom }}</p>
                            </div>
                        </div>
                        <div>
                            <div class="space-y-1">
                                <label class="text-sm text-gray-100 dark:text-gray-400">Matricule:</label>
                                <p class="text-lg font-semibold text-white">{{ $Student->matricule }}</p>
                            </div>
                            <div class="space-y-1 mt-4">
                                <label class="text-sm text-gray-100 dark:text-gray-400">Section:</label>
                                <p class="text-lg font-semibold text-white">{{ $Student->section_id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluation Form -->
        <form method="POST" action="/fiche/{{ $Student->matricule }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Psychological Evaluation -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-white mb-6 border-b border-gray-700/50 pb-4">
                    Évaluation psychologique</h2>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="psy" class="block text-sm font-medium text-gray-200">
                            Psychologue
                        </label>
                        <textarea name="psy" id="psy"
                            class="w-full bg-gray-700/50 border-gray-600 text-gray-100 rounded-lg
                                   focus:ring-2 focus:ring-pink-400 focus:border-transparent
                                   placeholder-gray-400"
                            placeholder="Entrez votre diagnostic..." rows="4">{{ optional($convoncu)->psy }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label for="medGen" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Médecin générale
                        </label>
                        <textarea name="medGen" id="medGen"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                       dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                       dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            placeholder="Entrez votre diagnostic..." rows="4">{{ optional($convoncu)->medGen }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Medical Evaluation -->
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-white mb-6 border-b border-gray-700/50 pb-4">
                    Évaluation médicale</h2>
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label for="chirDent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Chirurgie dentaire
                        </label>
                        <textarea name="chirDent" id="chirDent"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                       dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                       dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            placeholder="Entrez votre diagnostic..." rows="4">{{ optional($convoncu)->chirDent }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label for="avisSpe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Avis spécialisé
                        </label>
                        <textarea name="avisSpe" id="avisSpe"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700
                                       dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500
                                       dark:focus:border-blue-400 dark:focus:ring-blue-400"
                            placeholder="Entrez votre diagnostic..." rows="4">{{ optional($convoncu)->avisSpe }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-pink-500 text-white font-semibold rounded-full hover:bg-pink-600
                           transition-colors shadow-md focus:outline-none focus:ring-2 focus:ring-pink-500
                           focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
    </div>

</x-infermerie>
