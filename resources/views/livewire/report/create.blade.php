<div id="report"
    class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <form
        class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-xl transform transition-all duration-300 hover:shadow-2xl"
        wire:submit="save">
        <div class="p-8 space-y-8">
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 p-4 mb-6 rounded-r">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 dark:text-red-300"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                Il y a {{ $errors->count() }} erreur(s) dans le formulaire:
                            </h3>
                            <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @enderror

            @if ($errorMessage)
                <div class="bg-yellow-50 dark:bg-yellow-900/50 border-l-4 border-yellow-500 p-4 mb-6 rounded-r">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400 dark:text-yellow-300"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">{{ $errorMessage }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Nouveau Rapport
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Veuillez remplir tous les champs nécessaires
                </p>
            </div>

            {{-- Student Information --}}
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                <div class="relative">
                    <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Matricule étudiant
                    </label>
                    <div class="relative">
                        <input type="text" id="matricule" maxlength="7"
                            class="block w-full px-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition duration-150 ease-in-out {{ $matriculeError ? 'border-red-500 focus:border-red-500 focus:ring-red-500/20' : 'focus:border-indigo-500 focus:ring-indigo-500/20' }} dark:focus:ring-indigo-500/40"
                            placeholder="Entrez le matricule" wire:model.live="mat">

                        @if ($matriculeError)
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                        @elseif ($studentName)
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        @endif
                    </div>

                    @if ($matriculeError)
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $matriculeError }}</p>
                    @endif

                    @if ($studentName)
                        <p class="mt-2 text-sm text-green-600 dark:text-green-400">{{ $studentName }}</p>
                    @endif

                    @if ($sectionWarning)
                        <div class="mt-2 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">{{ $sectionWarning }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Report Information --}}
            <div class="space-y-8">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Titre du rapport
                    </label>
                    <input type="text" id="title"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40"
                        placeholder="Entrez le titre du rapport" wire:model="title">
                </div>

                <div>
                    <label for="corps" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contenu du rapport
                    </label>
                    <textarea wire:model="corps" id="corps" rows="12"
                        class="block w-full px-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 shadow-sm transition duration-150 ease-in-out focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 dark:focus:ring-indigo-500/40 resize-y"
                        placeholder="Détaillez votre rapport ici..."></textarea>
                </div>
            </div>

            <div class="pt-6 flex justify-end space-x-4">
                <button type="reset"
                    class="px-6 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500/20 dark:focus:ring-gray-500/40">
                    Réinitialiser
                </button>
                <button type="submit" wire:click="save"
                    class="px-6 py-3 rounded-lg bg-indigo-600 text-white text-sm font-medium shadow-sm transition duration-150 ease-in-out hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 dark:focus:ring-indigo-500/70">
                    Enregistrer le rapport
                </button>
            </div>
    </div>
</form>

{{-- Messages --}}
@if (session()->has('message'))
    <div class="mt-6 max-w-4xl mx-auto">
        <div
            class="rounded-lg bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 p-4 transform transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-check-circle text-green-400 dark:text-green-300 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('error'))
    <div class="mt-6 max-w-4xl mx-auto">
        <div
            class="rounded-lg bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 p-4 transform transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-exclamation-circle text-red-400 dark:text-red-300 text-xl"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
</div>
