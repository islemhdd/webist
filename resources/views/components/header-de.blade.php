<!-- Header principal -->
<div class="flex items-center justify-between h-full px-6">
    <!-- Profil utilisateur avec contrôles -->
    <div class="flex items-center space-x-4">
        <!-- Avatar et nom -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('profile.jpg') }}" alt="Profile" class="h-8 w-8 rounded-full">
            <span class="text-gray-700 dark:text-gray-100">{{ auth()->user()->username ?? 'Directeur d\'Études' }}</span>
        </div>

        <!-- Switch thème -->
        <button @click="$store.theme.toggle()" type="button" class="text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <!-- Icône Soleil (mode clair) -->
            <i class="fas fa-sun text-xl text-yellow-500 hidden theme-toggle-light-icon" x-show="$store.theme.current === 'light'"></i>
            <!-- Icône Lune (mode sombre) -->
            <i class="fas fa-moon text-xl text-blue-500 hidden theme-toggle-dark-icon" x-show="$store.theme.current === 'dark'"></i>
        </button>
    </div>

    <!-- Notifications et contrôles droite -->
    <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="relative p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg">
                <i class="fas fa-bell text-xl"></i>
                <!-- Badge de notification -->
                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                    3
                </span>
            </button>

            <!-- Dropdown notifications -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 z-50 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="py-1" role="menu">
                    <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300 border-b dark:border-gray-700">
                        <strong>Notifications</strong>
                    </div>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                        <div class="flex items-center">
                            <i class="fas fa-user-injured text-blue-500 mr-3"></i>
                            <div>
                                <p class="font-medium">Nouvelle validation requise</p>
                                <p class="text-xs text-gray-500">Patient en attente de validation RHP</p>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                        <div class="flex items-center">
                            <i class="fas fa-door-open text-red-500 mr-3"></i>
                            <div>
                                <p class="font-medium">Nouvelle exclusion</p>
                                <p class="text-xs text-gray-500">Étudiant exclu de classe</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recherche rapide -->
        <div class="relative hidden md:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Rechercher un étudiant...">
        </div>

        <!-- Menu utilisateur -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                <img class="h-8 w-8 rounded-full" src="{{ asset('profile.jpg') }}" alt="">
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5" role="menu">
                <div class="py-1" role="none">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                        <i class="fas fa-user mr-2"></i>
                        Mon profil
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                        <i class="fas fa-cog mr-2"></i>
                        Paramètres
                    </a>
                    <div class="border-t border-gray-100 dark:border-gray-700"></div>
                    <form method="POST" action="{{ route('logout') }}" role="none">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700" role="menuitem">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
