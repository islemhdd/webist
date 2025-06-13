<x-infermerie css='liste_rendezvous'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            Mes rendez-vous - {{ $specialtyName }}
                        </h1>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <i class="fas {{ $specialtyIcon }} w-4 h-4 mr-2"></i>
                                {{ $specialtyName }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('medical.appointments.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Nouveau rendez-vous
                    </a>
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Note d'information -->
                <div class="mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 text-lg mt-0.5"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">
                                    Rendez-vous de spécialité
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <p>• Vous ne voyez que les rendez-vous de votre spécialité : <strong>{{ $specialtyName }}</strong></p>
                                    <p>• Les nouveaux rendez-vous seront automatiquement assignés à votre spécialité</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filtres -->
                <div class="mb-6">
                    <form method="GET" action="{{ route('medical.appointments') }}" class="flex gap-4">
                        <div class="flex-1">
                            <select name="motif"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">Tous les motifs</option>
                                @foreach($motifs as $motif)
                                    <option value="{{ $motif }}" {{ request('motif') == $motif ? 'selected' : '' }}>
                                        {{ $motif }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input type="date" name="date" value="{{ request('date') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Filtrer
                        </button>
                    </form>
                </div>

                <!-- Statistiques rapides -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <i class="fas fa-calendar text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $totalAppointments }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                                <i class="fas fa-calendar-check text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Aujourd'hui</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $todayAppointments }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cette semaine</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $weekAppointments }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                <i class="fas {{ $specialtyIcon }} text-purple-600 dark:text-purple-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Spécialité</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $specialtyName }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table des rendez-vous -->
                @if($appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Patient
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date & Heure
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Motif
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Type médecin
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($appointments as $appointment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $appointment->nom_patient }} {{ $appointment->prenom_patient }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $appointment->matricule_patient }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div class="flex flex-col">
                                                <span class="font-medium">
                                                    {{ \Carbon\Carbon::parse($appointment->date_rdv)->format('d/m/Y') }}
                                                </span>
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($appointment->heure_rdv)->format('H:i') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                {{ $appointment->motif }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                {{ $appointment->type_medecin }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $isToday = \Carbon\Carbon::parse($appointment->date_rdv)->isToday();
                                                $isPast = \Carbon\Carbon::parse($appointment->date_rdv)->isPast();
                                            @endphp

                                            @if($isToday)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fas fa-calendar-day w-3 h-3 mr-1"></i>
                                                    Aujourd'hui
                                                </span>
                                            @elseif($isPast)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                                    <i class="fas fa-history w-3 h-3 mr-1"></i>
                                                    Passé
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <i class="fas fa-calendar-alt w-3 h-3 mr-1"></i>
                                                    À venir
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Voir
                                            </a>
                                            <button onclick="deleteAppointment({{ $appointment->id }})"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($appointments->hasPages())
                        <div class="mt-6">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-calendar-times text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                Aucun rendez-vous trouvé
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                Vous n'avez encore aucun rendez-vous pour votre spécialité.
                            </p>
                            <a href="{{ route('medical.appointments.create') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                Créer un rendez-vous
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function deleteAppointment(appointmentId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
                // Créer un formulaire temporaire pour la suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/medical/appointments/' + appointmentId;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-infermerie>
