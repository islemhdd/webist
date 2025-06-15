{{-- !machi hadi ta3k , hadi ta3 anes --}}
<!-- Header principal -->
<div class="flex items-center justify-between h-full px-6">

    <!-- Profil utilisateur avec contrôles -->
    <div  class="flex items-center space-x-4  ">
        <!-- Avatar et nom -->
        <div class="flex items-center space-x-3 space-x-reverse">
            <img src="/profile.jpg" alt="Profile" class="h-8 w-8 rounded-full">
            <span class="text-gray-700 dark:text-gray-100">{{ auth()->user()->username ?? 'Utilisateur' }}</span>
        </div>

        <!-- Switch thème -->
        <button id="theme-toggle" type="button" class="theme-toggle text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <!-- Icône Soleil (mode clair) -->
            <i class="fas fa-sun text-xl text-yellow-500 hidden theme-toggle-light-icon"></i>
            <!-- Icône Lune (mode sombre) -->
            <i class="fas fa-moon text-xl text-blue-500 hidden theme-toggle-dark-icon"></i>
        </button>

        <!-- Notifications -->
        @php
            $notifications = auth()->user()->unreadNotifications;
        @endphp
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open; $wire.markNotificationsAsRead()"
                class="relative p-2 text-gray-500 hover:text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                @if ($notifications->count() > 0)
                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span
                            class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-xs text-white justify-center items-center">
                            {{ $notifications->count() }}
                        </span>
                    </span>
                @endif
                <i class="fa-regular fa-bell text-xl"></i>
            </button>

            <!-- Menu dropdown des notifications -->
            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border dark:border-gray-700 z-50">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Notifications</h3>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse ($notifications as $notification)
                            <div class="flex p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ class_basename($notification->type) }}:{{ notification->data['title'] }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $notification->type == 'App\Notifications\ReportRefused' ? $notification->data['motif'] : $notifiction->data['Avis' . $notifiction->data['status']] }}
                                    </p>
                                    <span class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Aucune nouvelle notification</p>
                        @endforelse
                    </div>
              
                </div>
            </div>
        </div>
    </div>

    
</div>
