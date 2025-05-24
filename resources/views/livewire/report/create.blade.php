<div id="report" class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4 sm:px-6 lg:px-8">
    <form class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg" wire:submit="save">
        <div class="p-6 space-y-6">
            @error('matricule')
                <p class="text-red-600 dark:text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror
            <h2
                class="text-2xl font-bold text-gray-900 dark:text-white pb-4 border-b border-gray-200 dark:border-gray-700">
                Rapport</h2>

            {{-- Student Information --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Matricule
                        étudiant :</label>
                    <input type="text" id="matricule"
                        class=" indent-2  mt-1 h-8 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Entrez le matricule" wire:model="mat">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom de
                        l'étudiant :</label>
                    <input type="text" id="name"
                        class=" indent-2 mt-1 h-8 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300 shadow-sm sm:text-sm cursor-not-allowed"
                        placeholder="Nom complet" wire:model="studentName" readonly>
                </div>
            </div>

            {{-- Report Information --}}
            <div class="space-y-6 pt-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Titre du
                        rapport:</label>
                    <input type="text" id="title"
                        class=" indent-2 mt-1 h-8 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Entrez le titre du rapport" wire:model="title">
                </div>

                {{-- Medical Checkbox --}}
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="isMedical"
                        class=" indent-2 h-8 w-4 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-500 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                        wire:model.live="isMedical">
                    <label for="isMedical" class="text-sm font-medium text-gray-700 dark:text-gray-300">Rapport
                        médical</label>
                </div>

                {{-- Medical Speciality (Only shown if isMedical is true) --}}
                @if ($isMedical)
                    <div class="mt-4">
                        <label for="speciality"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Spécialité
                            médicale:</label>
                        <select id="speciality"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            wire:model="destination">
                            <option value="">Sélectionnez une spécialité</option>
                            <option value="dentiste">Dentiste</option>
                            <option value="medecin">Médecin Principal</option>
                        </select>
                    </div>
                @endif

                <div>
                    <label for="corps" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contenu du
                        rapport:</label>
                    <textarea wire:model="corps" id="corps" rows="5"
                        class="indent-4  mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        placeholder="Détails du rapport..."></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="reset"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Réinitialiser
                </button>
                <button type="submit" wire:click="save"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                    Enregistrer le rapport
                </button>
            </div>
        </div>
    </form>

    {{-- Messages --}}
    @if (session()->has('message'))
        <div class="mt-4 max-w-3xl mx-auto">
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 max-w-3xl mx-auto">
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
