<!-- Dropdown de notification -->
<div class="relative" x-data="{ open: false }">
    <!-- Icône de notification -->
    <button @click="open = !open" class="text-gray-500 hover:text-gray-600 dark:text-gray-300" id="notifToggle">
        <span class="relative inline-block">
            <i class="fa-regular fa-bell text-xl"></i>
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">3</span>
        </span>
    </button>

    <!-- Panneau de notifications -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="absolute right-0 w-80 mt-3 origin-top-right bg-white dark:bg-gray-800 rounded-lg shadow-lg border dark:border-gray-700"
         id="notifBox">
        <div class="p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Notifications</h3>
                <button @click="open = false" class="text-gray-500 hover:text-gray-600 dark:text-gray-400" id="closeNotif">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Liste des notifications -->
            <div class="space-y-4 max-h-96 overflow-y-auto custom-scrollbar">
                <!-- Notification non lue -->
                <div class="flex items-start p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-800">
                            <i class="fa-solid fa-user-plus text-blue-600 dark:text-blue-300"></i>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Nouveau patient</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Un nouveau patient a été ajouté à la liste d'attente</p>
                        <span class="text-xs text-gray-500 dark:text-gray-500">Il y a 5 minutes</span>
                    </div>
                </div>

                <!-- Notification lue -->
                <div class="flex items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 dark:bg-green-800">
                            <i class="fa-solid fa-check text-green-600 dark:text-green-300"></i>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Rendez-vous confirmé</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Le rendez-vous #123 a été confirmé</p>
                        <span class="text-xs text-gray-500 dark:text-gray-500">Il y a 1 heure</span>
                    </div>
                </div>

                <!-- Notification d'urgence -->
                <div class="flex items-start p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 dark:bg-red-800">
                            <i class="fa-solid fa-exclamation text-red-600 dark:text-red-300"></i>
                        </span>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Urgence médicale</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Patient nécessitant une attention immédiate</p>
                        <span class="text-xs text-gray-500 dark:text-gray-500">Il y a 30 minutes</span>
                    </div>
                </div>
            </div>

            <!-- Pied du panneau -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="#" class="block text-sm text-center text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                    Voir toutes les notifications
                </a>
            </div>
        </div>
    </div>
</div>
