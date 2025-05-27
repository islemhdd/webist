<x-infermerie css='showRdv'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Détails du rendez-vous</h1>
                    <div class="flex items-center space-x-4">
                        <!-- Switch Theme -->
                        <button id="theme-toggle" type="button"
                            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z">
                                </path>
                            </svg>
                        </button>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="relative p-2 text-gray-500 hover:text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-xs text-white justify-center items-center">3</span>
                                </span>
                                <i class="fa-regular fa-bell text-xl"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border dark:border-gray-700">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                        Notifications</h3>
                                    <div class="space-y-4 max-h-96 overflow-y-auto">
                                        <!-- Notifications items -->
                                        <div class="flex p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    Nouveau rendez-vous
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Un nouveau rendez-vous a été créé
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('liste_rdv.index') }}"
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations de l'étudiant -->
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Informations de
                                l'étudiant</h2>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Matricule:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ $rdv->matricule }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Nom:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ $rdv->nom }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Prénom:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ $rdv->prenom }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Section:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ $rdv->section_id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Détails du rendez-vous -->
                    <div class="space-y-4">
                        <div>
                            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Détails du rendez-vous
                            </h2>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Service:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ $rdv->service }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Motif:</span>
                                    <p class="text-gray-900 dark:text-gray-300">{{ ucfirst($rdv->motif) }}</p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Date:</span>
                                    <p class="text-gray-900 dark:text-gray-300">
                                        {{ Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-4 mt-6">
                            <form action="{{ route('liste_rdv.destroy') }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $rdv->id }}">
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Theme toggler
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Change the icons inside the button based on previous settings
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }

            const themeToggleBtn = document.getElementById('theme-toggle');
            themeToggleBtn.addEventListener('click', function() {
                // Toggle icons
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                // If is set in localStorage
                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        </script>
    @endpush
</x-infermerie>
