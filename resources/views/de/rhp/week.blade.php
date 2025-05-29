<x-de>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Planning Hebdomadaire RHP</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Assignations des Responsables Halles Pédagogiques pour la semaine en cours</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('rhp.index') }}"
                           class="border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-white px-6 py-3 rounded-lg shadow-sm transition-colors flex items-center space-x-2">
                            <i class="fas fa-calendar"></i>
                            <span>Vue complète</span>
                        </a>
                        <button onclick="showAddModal()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md transition-colors flex items-center space-x-2">
                            <i class="fas fa-plus"></i>
                            <span>Nouvelle assignation</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Week View Table -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Semaine du {{ $startDate->format('d/m/Y') }} au {{ $endDate->format('d/m/Y') }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Planning des RHP de la semaine en cours</p>
                    </div>
                </div>

                @php
                    $daysNames = [
                        0 => 'Dimanche',
                        1 => 'Lundi',
                        2 => 'Mardi',
                        3 => 'Mercredi',
                        4 => 'Jeudi'
                    ];
                    $daysBgColors = [
                        0 => 'bg-purple-50 dark:bg-purple-900/20',
                        1 => 'bg-blue-50 dark:bg-blue-900/20',
                        2 => 'bg-green-50 dark:bg-green-900/20',
                        3 => 'bg-yellow-50 dark:bg-yellow-900/20',
                        4 => 'bg-orange-50 dark:bg-orange-900/20'
                    ];
                    $daysBorderColors = [
                        0 => 'border-purple-200 dark:border-purple-700',
                        1 => 'border-blue-200 dark:border-blue-700',
                        2 => 'border-green-200 dark:border-green-700',
                        3 => 'border-yellow-200 dark:border-yellow-700',
                        4 => 'border-orange-200 dark:border-orange-700'
                    ];
                    $currentWeekDays = [];

                    for ($day = $startDate; $day->lte($endDate); $day = $day->copy()->addDay()) {
                        $currentWeekDays[] = $day->copy();
                    }
                @endphp

                <!-- Day Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    @foreach($currentWeekDays as $day)
                        <div class="border {{ $daysBorderColors[$day->dayOfWeek] }} rounded-lg overflow-hidden {{ $daysBgColors[$day->dayOfWeek] }} {{ $day->isToday() ? 'ring-2 ring-blue-500' : '' }}">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold {{ $day->isToday() ? 'text-blue-700 dark:text-blue-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ $daysNames[$day->dayOfWeek] }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $day->format('d/m/Y') }}</p>
                            </div>

                            <!-- Morning Section -->
                            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        <i class="fas fa-sun mr-1"></i> Matin (8:00-12:20)
                                    </h4>
                                </div>

                                @php
                                    $morningRHP = $rhps->first(function($rhp) use ($day) {
                                        return $rhp->date_assignation->isSameDay($day) && $rhp->periode === 'matin';
                                    });
                                @endphp

                                @if($morningRHP)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ strtoupper(substr($morningRHP->officer->prenom, 0, 1) . substr($morningRHP->officer->nom, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $morningRHP->officer->nom }} {{ $morningRHP->officer->prenom }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $morningRHP->officer->role->name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editRHP({{ $morningRHP->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteRHP({{ $morningRHP->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @elseif($day->isFuture() || $day->isToday())
                                    <button onclick="assignRHP('{{ $day->format('Y-m-d') }}', 'morning')"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-800/50 focus:outline-none">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Assigner
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block text-center py-2">Non assigné</span>
                                @endif
                            </div>

                            <!-- Afternoon Section -->
                            <div class="p-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-orange-600 dark:text-orange-400">
                                        <i class="fas fa-sunset mr-1"></i> Après-midi (13:30-16:20)
                                    </h4>
                                </div>

                                @php
                                    $afternoonRHP = $rhps->first(function($rhp) use ($day) {
                                        return $rhp->date_assignation->isSameDay($day) && $rhp->periode === 'apres_midi';
                                    });
                                @endphp

                                @if($afternoonRHP)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                                                    <span class="text-orange-600 dark:text-orange-400 font-bold">{{ strtoupper(substr($afternoonRHP->officer->prenom, 0, 1) . substr($afternoonRHP->officer->nom, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $afternoonRHP->officer->nom }} {{ $afternoonRHP->officer->prenom }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $afternoonRHP->officer->role->name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editRHP({{ $afternoonRHP->id }})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button onclick="deleteRHP({{ $afternoonRHP->id }})" class="text-red-600 hover:text-red-800 dark:text-red-400">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @elseif($day->isFuture() || $day->isToday())
                                    <button onclick="assignRHP('{{ $day->format('Y-m-d') }}', 'afternoon')"
                                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-orange-700 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-800/50 focus:outline-none">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Assigner
                                    </button>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block text-center py-2">Non assigné</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- RHP Assignment Modal -->
    <div id="rhpModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Nouvelle assignation RHP</h3>
                    <button onclick="hideModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="rhpForm" method="POST" action="{{ route('rhp.store') }}" onsubmit="event.preventDefault(); submitForm();">
                    @csrf
                    <input type="hidden" id="method" name="_method" value="POST">
                    <input type="hidden" id="rhpId" name="rhp_id" value="">

                    <div class="mb-4">
                        <label for="officer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Officier responsable</label>
                        <select id="officer_id" name="officer_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Sélectionnez un officier</option>
                            @foreach($officers as $officer)
                                <option value="{{ $officer->id }}">{{ $officer->nom }} {{ $officer->prenom }} ({{ $officer->role->name }})</option>
                            @endforeach
                        </select>
                        <div id="officer_id_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="mb-4">
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date d'assignation</label>
                        <input type="date" id="date" name="date" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            min="{{ now()->startOfWeek()->format('Y-m-d') }}"
                            max="{{ now()->startOfWeek()->addDays(4)->format('Y-m-d') }}"
                            value="{{ now()->format('Y-m-d') }}">
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">Uniquement du dimanche au jeudi de la semaine en cours</div>
                        <div id="date_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="mb-4">
                        <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Période</label>
                        <select id="period" name="period" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="morning">Matin (8:00-12:20)</option>
                            <option value="afternoon">Après-midi (13:30-16:20)</option>
                        </select>
                        <div id="period_error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (optionnel)</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="hideModal()"
                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set min/max date for the date input to be the current week (Sunday to Thursday)
            const dateInput = document.getElementById('date');
            const startOfWeek = new Date('{{ now()->startOfWeek()->format("Y-m-d") }}');
            const endOfWeek = new Date('{{ now()->startOfWeek()->addDays(4)->format("Y-m-d") }}');

            dateInput.min = startOfWeek.toISOString().split('T')[0];
            dateInput.max = endOfWeek.toISOString().split('T')[0];

            // Set default date to today if it's between Sunday and Thursday
            const today = new Date();
            if (today >= startOfWeek && today <= endOfWeek) {
                dateInput.value = today.toISOString().split('T')[0];
            } else {
                dateInput.value = startOfWeek.toISOString().split('T')[0];
            }
        });

        function showAddModal() {
            // Reset form
            document.getElementById('rhpForm').reset();
            document.getElementById('modalTitle').textContent = 'Nouvelle assignation RHP';
            document.getElementById('rhpForm').action = '{{ route('rhp.store') }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('rhpId').value = '';

            // Set default date to today if it's between Sunday and Thursday
            const today = new Date();
            const startOfWeek = new Date('{{ now()->startOfWeek()->format("Y-m-d") }}');
            const endOfWeek = new Date('{{ now()->startOfWeek()->addDays(4)->format("Y-m-d") }}');

            if (today >= startOfWeek && today <= endOfWeek) {
                document.getElementById('date').value = today.toISOString().split('T')[0];
            } else {
                document.getElementById('date').value = startOfWeek.toISOString().split('T')[0];
            }

            // Clear previous errors
            clearErrors();

            // Show modal
            document.getElementById('rhpModal').classList.remove('hidden');
        }

        function assignRHP(date, period) {
            // Reset form
            document.getElementById('rhpForm').reset();
            document.getElementById('modalTitle').textContent = 'Nouvelle assignation RHP';
            document.getElementById('rhpForm').action = '{{ route('rhp.store') }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('rhpId').value = '';

            // Set the date and period
            document.getElementById('date').value = date;
            document.getElementById('period').value = period;

            // Clear previous errors
            clearErrors();

            // Show modal
            document.getElementById('rhpModal').classList.remove('hidden');
        }

        function editRHP(id) {
            // Clear previous errors
            clearErrors();

            // Fetch RHP data and populate modal
            fetch(`/rhp/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Modifier assignation RHP';
                    document.getElementById('rhpForm').action = `/rhp/${id}`;
                    document.getElementById('method').value = 'PUT';
                    document.getElementById('rhpId').value = id;
                    document.getElementById('officer_id').value = data.officer_id;
                    document.getElementById('date').value = data.date;
                    document.getElementById('period').value = data.period;
                    if (data.notes) {
                        document.getElementById('notes').value = data.notes;
                    }
                    document.getElementById('rhpModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error fetching RHP data:', error);
                    alert('Une erreur est survenue lors de la récupération des données');
                });
        }

        function submitForm() {
            const form = document.getElementById('rhpForm');
            const formData = new FormData(form);
            const action = form.action;
            const method = document.getElementById('method').value || 'POST';

            // Clear previous errors
            clearErrors();

            fetch(action, {
                method: method === 'PUT' ? 'POST' : 'POST', // Laravel requires POST for PUT
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal();
                    // Show success message
                    alert(data.message);
                    // Reload page to see changes
                    location.reload();
                } else if (data.errors) {
                    // Display validation errors
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}_error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                } else {
                    alert('Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                alert('Une erreur est survenue lors de l\'envoi du formulaire');
            });
        }

        function clearErrors() {
            const errorElements = document.querySelectorAll('[id$="_error"]');
            errorElements.forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
        }

        function deleteRHP(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette assignation ?')) {
                fetch(`/rhp/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Assignation supprimée avec succès');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            }
        }

        function hideModal() {
            document.getElementById('rhpModal').classList.add('hidden');
        }
    </script>
</x-de>
