<x-infermerie css='appointments_list'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                            Gestion des Rendez-vous
                        </h1>
                        <div class="flex items-center space-x-2">
                            @if($allowedSpecialty !== 'all')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $allowedSpecialty === 'psycho' ? 'Psychologie' :
                                       ($allowedSpecialty === 'dentiste' ? 'Dentiste' : 'Médecine Générale') }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Médecin Chef - Toutes spécialités
                                </span>
                            @endif
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $userRole }}</span>
                        </div>
                    </div>
                    <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center transition duration-200" onclick="openCreateModal()">
                        <i class="fas fa-plus mr-2"></i>Nouveau RDV
                    </button>
                </div>

                <!-- Filtres -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <form method="GET" action="{{ route('medical.appointments') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div>
                            <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   id="service" name="service" placeholder="Rechercher un service..." value="{{ request('service') }}">
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   id="search" name="search" placeholder="Matricule, nom, prénom..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <label for="per_page" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Affichage</label>
                            <div class="flex gap-2">
                                <select class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" name="per_page">
                                    <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition duration-200">
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('medical.appointments') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-md transition duration-200">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Liste des rendez-vous -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            <i class="fas fa-list mr-2"></i>
                            Liste des Rendez-vous ({{ $appointments->total() }})
                        </h2>
                        <div class="flex gap-2">
                            <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 text-sm rounded transition duration-200" onclick="refreshStats()">
                                <i class="fas fa-sync mr-1"></i>Actualiser
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        @if($appointments->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matricule</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date RDV</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motif</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Spécialité</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Section</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($appointments as $appointment)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $appointment->matricule }}</span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $appointment->nom ?? 'N/A' }} {{ $appointment->prenom ?? '' }}</div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $appointment->service }}</div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $appointment->motif }}">
                                                        {{ $appointment->motif }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    @if($appointment->type_medecin)
                                                        @php
                                                            $specialtyBadge = [
                                                                'psycho' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Psychologie'],
                                                                'dentiste' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Dentiste'],
                                                                'médecin générale' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Médecine Générale']
                                                            ];
                                                            $badge = $specialtyBadge[$appointment->type_medecin] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Non défini'];
                                                        @endphp
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['class'] }}">{{ $badge['text'] }}</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Non assigné</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    @if($appointment->bat !== null)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Section {{ $appointment->bat }}</span>
                                                    @else
                                                        <span class="text-gray-400">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2">
                                                        @if($allowedSpecialty === 'all' || (isset($appointment->type_medecin) && $appointment->type_medecin === $allowedSpecialty))
                                                            <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 text-sm rounded transition duration-200"
                                                                    onclick="deleteAppointment('{{ $appointment->matricule }}', '{{ $appointment->date }}')"
                                                                    title="Supprimer">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @else
                                                            <span class="text-gray-400 text-sm">Lecture seule</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="flex justify-center mt-4">
                                {{ $appointments->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Aucun rendez-vous trouvé</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    @if(request()->hasAny(['date', 'service', 'search']))
                                        Aucun rendez-vous ne correspond à vos critères de recherche.
                                    @else
                                        Aucun rendez-vous n'est enregistré pour votre spécialité.
                                    @endif
                                </p>
                                <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200" onclick="openCreateModal()">
                                    <i class="fas fa-plus mr-2"></i>Créer le premier rendez-vous
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Création RDV -->
    <div id="createAppointmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Nouveau Rendez-vous</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeCreateModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="createAppointmentForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Matricule <span class="text-red-500">*</span></label>
                        <input type="number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="matricule" name="matricule" required>
                        <div class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Motif <span class="text-red-500">*</span></label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="motif" name="motif" rows="3" required></textarea>
                        <div class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Service <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="service" name="service" required>
                        <div class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date RDV <span class="text-red-500">*</span></label>
                        <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="date" name="date"
                               min="{{ date('Y-m-d') }}" required>
                        <div class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="type_medecin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Spécialité <span class="text-red-500">*</span></label>
                        <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" id="type_medecin" name="type_medecin" required>
                            <option value="">Sélectionner une spécialité</option>
                            @if($allowedSpecialty === 'all')
                                <option value="psycho">Psychologie</option>
                                <option value="dentiste">Dentiste</option>
                                <option value="médecin générale">Médecine Générale</option>
                            @else
                                <option value="{{ $allowedSpecialty }}">
                                    {{ $allowedSpecialty === 'psycho' ? 'Psychologie' :
                                       ($allowedSpecialty === 'dentiste' ? 'Dentiste' : 'Médecine Générale') }}
                                </option>
                            @endif
                        </select>
                        <div class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition duration-200" onclick="closeCreateModal()">Annuler</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Créer le RDV
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Functions pour gérer les modals Tailwind
    function openCreateModal() {
        document.getElementById('createAppointmentModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createAppointmentModal').classList.add('hidden');
    }

    // Création d'un rendez-vous
    document.getElementById('createAppointmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création...';

        fetch('{{ route("medical.appointments.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Succès
                closeCreateModal();
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                // Erreur
                showAlert('danger', data.message);
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = document.getElementById(field);
                        const feedback = input.parentNode.querySelector('.text-red-500');
                        input.classList.add('border-red-500');
                        if (feedback) {
                            feedback.textContent = data.errors[field][0];
                            feedback.classList.remove('hidden');
                        }
                    });
                }
            }
        })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('danger', 'Une erreur inattendue s\'est produite.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    // Suppression d'un rendez-vous
    function deleteAppointment(matricule, date) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')) {
            return;
        }

        const url = `{{ route('medical.appointments.delete', ['matricule' => '__MATRICULE__', 'date' => '__DATE__']) }}`
            .replace('__MATRICULE__', matricule)
            .replace('__DATE__', date);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('danger', 'Une erreur inattendue s\'est produite.');
        });
    }

    // Fonction d'affichage des alertes (style Tailwind)
    function showAlert(type, message) {
        const typeColors = {
            'success': 'bg-green-100 border-green-400 text-green-700',
            'danger': 'bg-red-100 border-red-400 text-red-700',
            'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700'
        };

        const alertHtml = `
            <div class="mb-4 p-4 ${typeColors[type]} border rounded" role="alert">
                ${message}
            </div>
        `;

        const container = document.querySelector('.container');
        container.insertAdjacentHTML('afterbegin', alertHtml);

        // Auto-masquer après 5 secondes
        setTimeout(() => {
            const alert = container.querySelector('.mb-4');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }

    // Actualisation des statistiques
    function refreshStats() {
        location.reload();
    }
    </script>
</x-infermerie>
