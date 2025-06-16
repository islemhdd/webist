@props(['officer'])


<!-- Header principal -->
<div class="flex items-center justify-between h-full px-6 ">
    <!-- Profil utilisateur avec contrôles -->
    <div class="flex items-center   space-x-4 space-x-reverse">
        <!-- Avatar et nom -->
        <div class="flex items-center space-x-3 space-x-reverse">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button"
                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    id="user-menu-button">
                    <img class="h-8 w-8 rounded-full" src="{{ asset('profile.jpg') }}" alt="">
                </button>

                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 z-50 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                    role="menu">
                    <div class="py-1" role="none">

                        <a href="#"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            role="menuitem">
                            <i class="fas fa-cog mr-2"></i>
                            Paramètres
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-700"></div>
                        <form method="POST" action="{{ route('logout') }}" role="none">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <span class="text-gray-700 dark:text-gray-100">{{ $officer->username }}</span>
        </div>

        <!-- Switch thème -->
        <button id="theme-toggle-brigade" type="button"
            class="theme-toggle text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
            <!-- Icône Soleil (mode clair) -->
            <i class="fas fa-sun text-xl text-yellow-500 hidden theme-toggle-light-icon"></i>
            <!-- Icône Lune (mode sombre) -->
            <i class="fas fa-moon text-xl text-blue-500 hidden theme-toggle-dark-icon"></i>
        </button>


        <!-- Notifications et contrôles droite -->
        <div class="flex items-center space-x-4">
            <!-- Notifications -->
            <div x-data="{ open: false, notificationCount: {{ $officer->unreadNotifications->count() }} }" class="relative">
                <button @click="open = !open"
                    class="relative p-2 text-gray-500 hover:text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    <template x-if="notificationCount > 0">
                        <span class="absolute -top-1 -right-1 flex h-5 w-5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span
                                class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-xs text-white justify-center items-center font-medium shadow-md"
                                x-text="notificationCount"></span>
                        </span>
                    </template>
                    <i class="fa-regular fa-bell text-xl"></i>
                </button>

                <!-- Menu dropdown des notifications -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">
                    <div class="p-4">
                        <div
                            class="flex items-center justify-between mb-3 pb-2 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                <i class="far fa-bell mr-2 text-indigo-500"></i> Notifications
                            </h3>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full"
                                x-text="notificationCount + ' nouveau' + (notificationCount > 1 ? 'x' : '')"></span>
                        </div>
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @forelse ($officer->unreadNotifications as $notification)
                                <div id="notification-{{ $notification->id }}"
                                    class="flex justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 transform hover:translate-x-1 shadow-sm hover:shadow">
                                    <a href="{{ route('report.unsetNotificationAndShowReport', ['id' => $officer->id, 'report_id' => $notification->data['report_id']]) }}"
                                        class="flex-grow">
                                        <div class="ml-3">
                                            @php
                                                $type = class_basename($notification->type);
                                                $friendlyType = match ($type) {
                                                    'ReportArival' => 'Rapport reçu',
                                                    'ReportRefused' => 'Rapport refusé',
                                                    'DesitionMade' => 'Décision sur le rapport',
                                                    'ReportPassed' => 'Rapport transmis',
                                                    default => $type,
                                                };

                                                // Get student name if available
                                                $studentName = '';
                                                if (isset($notification->data['report_id'])) {
                                                    $report = \App\Models\Report::find(
                                                        $notification->data['report_id'],
                                                    );
                                                    if ($report && $report->student) {
                                                        $studentName =
                                                            $report->student->nom . ' ' . $report->student->prenom;
                                                    }
                                                }

                                                // Determine notification color based on type
                                                $notifColorClass = match ($type) {
                                                    'ReportArival'
                                                        => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
                                                    'ReportRefused'
                                                        => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
                                                    'DesitionMade'
                                                        => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
                                                    'ReportPassed'
                                                        => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
                                                    default
                                                        => 'bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-200',
                                                };
                                            @endphp
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $notifColorClass }} mr-2">
                                                    {{ $friendlyType }}
                                                </span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            @if (!empty($studentName))
                                                <p
                                                    class="text-sm font-medium text-gray-800 dark:text-gray-200 mt-1 truncate">
                                                    {{ $studentName }}
                                                </p>
                                            @endif
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5 truncate">
                                                {{ isset($notification->data['title']) ? $notification->data['title'] : $notification->data['message'] ?? 'Pas de contenu.' }}
                                            </p>
                                        </div>
                                    </a>
                                    <button onclick="markAsRead('{{ $notification->id }}', {{ $officer->id }})"
                                        class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 p-2 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-6">
                                    <i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-600 mb-2"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Aucune nouvelle notification</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>



            <!-- Menu utilisateur -->

        </div>
    </div>

</div>

<script>
    function markAsRead(notificationId, officerId) {
        // For Brigade header, we'll use the actual backend functionality
        // You can modify this to match your actual notification handling

        // Remove the notification from the DOM with enhanced animation
        const notificationElement = document.querySelector(
                `[onclick="markAsRead('${notificationId}', '${officerId}')"]`)
            .closest('.flex');
        if (notificationElement) {
            // First, scale down slightly and start fade
            notificationElement.style.transition = 'all 200ms ease-in-out';
            notificationElement.style.transform = 'scale(0.98)';
            notificationElement.style.opacity = '0.7';

            // After a short delay, slide out to the right with fade out
            setTimeout(() => {
                notificationElement.style.transform = 'translateX(100%)';
                notificationElement.style.opacity = '0';
                notificationElement.style.maxHeight = '0';
                notificationElement.style.margin = '0';
                notificationElement.style.padding = '0';

                // Wait for animation to complete before removing
                setTimeout(() => {
                    notificationElement.remove();

                    // Update the notification count
                    const countElement = document.querySelector('[x-data*="notificationCount"]').__x
                        .$data;
                    countElement.notificationCount = Math.max(0, countElement.notificationCount - 1);

                    // If no more notifications, update the empty state
                    const notificationsContainer = document.querySelector('.space-y-4');
                    if (notificationsContainer && !notificationsContainer.querySelector('.flex')) {
                        notificationsContainer.innerHTML =
                            '<div class="text-center py-6">' +
                            '<i class="fas fa-bell-slash text-2xl text-gray-400 dark:text-gray-600 mb-2"></i>' +
                            '<p class="text-sm text-gray-500 dark:text-gray-400">Aucune nouvelle notification</p>' +
                            '</div>';
                    }
                }, 300);
            }, 100);
        }

        // Also make the actual backend call
        fetch(`/notifications/${officerId}/mark-as-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }
</script>
