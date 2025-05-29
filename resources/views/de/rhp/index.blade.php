<x-de>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-shield-alt mr-3"></i>Gestion RHP
                </h1>
                <button onclick="showAddModal()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    Nouvelle assignation
                </button>
            </div>

            <!-- Calendar View -->
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">


                <!-- Current Week RHP Table -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 text-center dark:text-white mb-4">Planning RHP de la semaine</h3>

                    @php
                        $daysNames = [
                            0 => 'Dimanche',
                            1 => 'Lundi',
                            2 => 'Mardi',
                            3 => 'Mercredi',
                            4 => 'Jeudi',
                            5 => 'Vendredi',
                            6 => 'Samedi'
                        ];
                    @endphp

                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jour</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matin (8:00-12:20)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Après-midi (13:30-16:20)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                    @foreach($currentWeekDays as $day)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 {{ $day->isToday() ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $day->isToday() ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-900 dark:text-white' }}">
                                                {{ $daysNames[$day->dayOfWeek] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                {{ $day->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                                    <span class="text-blue-600 dark:text-blue-400 font-bold">{{ strtoupper(substr($morningRHP->officer->username, 0, 2)) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $morningRHP->officer->username }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
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
                                                    <button onclick="assignRHP('{{ $day->format('Y-m-d') }}', 'matin')"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-800/50 focus:outline-none">
                                                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        Assigner
                                                    </button>
                                                @else
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Non assigné</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
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
                                                                    <span class="text-orange-600 dark:text-orange-400 font-bold">{{ strtoupper(substr($afternoonRHP->officer->username, 0, 2)) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                    {{ $afternoonRHP->officer->username }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 dark:text-gray-400">
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
                                                    <button onclick="assignRHP('{{ $day->format('Y-m-d') }}', 'apres_midi')"
                                                            class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-orange-700 bg-orange-100 hover:bg-orange-200 dark:bg-orange-900/30 dark:text-orange-300 dark:hover:bg-orange-800/50 focus:outline-none">
                                                        <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                        </svg>
                                                        Assigner
                                                    </button>
                                                @else
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Non assigné</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Vue par période - Semaine courante</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    @php
                        // Filter RHPs to only show current week (Sunday to Thursday)
                        $currentWeekStart = now()->startOfWeek(Carbon\Carbon::SUNDAY); // Sunday
                        $currentWeekEnd = now()->startOfWeek(Carbon\Carbon::SUNDAY)->addDays(4); // Thursday

                        $currentWeekRHPs = $rhps->filter(function($rhp) use ($currentWeekStart, $currentWeekEnd) {
                            return $rhp->date_assignation->between($currentWeekStart, $currentWeekEnd);
                        });
                    @endphp

                    <!-- Period 1: 8:00-12:20 -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-4">
                            <i class="fas fa-sun mr-2"></i>Période Matin (8:00-12:20)
                        </h3>
                        <div class="space-y-3">
                            @forelse($currentWeekRHPs->where('periode', 'matin')->sortBy('date_assignation') as $rhp)
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-blue-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $rhp->officer->username }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $rhp->date_assignation->format('l d/m/Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="editRHP({{ $rhp->id }})"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteRHP({{ $rhp->id }})"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                    <p>Aucune assignation matin pour cette semaine</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Period 2: 13:30-16:20 -->
                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-300 mb-4">
                            <i class="fas fa-sunset mr-2"></i>Période Après-midi (13:30-16:20)
                        </h3>
                        <div class="space-y-3">
                            @forelse($currentWeekRHPs->where('periode', 'apres_midi')->sortBy('date_assignation') as $rhp)
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm border-l-4 border-orange-500">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $rhp->officer->username }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $rhp->date_assignation->format('l d/m/Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button onclick="editRHP({{ $rhp->id }})"
                                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteRHP({{ $rhp->id }})"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                    <p>Aucune assignation après-midi pour cette semaine</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="rhpModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-md">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modalTitle">
                        Nouvelle assignation RHP
                    </h3>
                    <button onclick="hideModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="rhpForm" method="POST" action="{{ route('de.rhp.store') }}">
                    @csrf
                    <input type="hidden" id="rhpId" name="id">
                    <input type="hidden" id="method" name="_method">

                    <div class="space-y-4">
                        <div>
                            <label for="officer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Officier RHP
                            </label>
                            <select name="officer_id" id="officer_id" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <option value="">Sélectionner un officier</option>
                                @foreach($officers as $officer)
                                    <option value="{{ $officer->id }}">
                                        {{ $officer->username }} - {{ $officer->role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="officer_id_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Date
                            </label>
                            <input type="date" name="date" id="date" required
                                   class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                   min="{{ now()->startOfWeek(Carbon\Carbon::SUNDAY)->format('Y-m-d') }}"
                                   max="{{ now()->startOfWeek(Carbon\Carbon::SUNDAY)->addDays(4)->format('Y-m-d') }}">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Vous pouvez assigner des officiers RHP uniquement pour la semaine en cours (Dimanche à Jeudi)</p>
                            <div id="date_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Période
                            </label>
                            <select name="period" id="period" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <option value="">Sélectionner une période</option>
                                <option value="matin">Matin (8:00-12:20)</option>
                                <option value="apres_midi">Après-midi (13:30-16:20)</option>
                            </select>
                            <div id="period_error" class="text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes (optionnel)
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    placeholder="Informations supplémentaires"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideModal()"
                                class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500">
                            Annuler
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup form submission handling
            const form = document.getElementById('rhpForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm();
            });

            // Set default date to today if it's a weekday (Sun-Thu)
            const today = new Date();
            const dayOfWeek = today.getDay();
            if (dayOfWeek <= 4) { // 0 = Sunday, 4 = Thursday
                const dateInput = document.getElementById('date');
                dateInput.value = today.toISOString().split('T')[0];
            }
        });

        // Function to assign RHP directly from the table
        function assignRHP(date, period) {
            // Clear previous form data
            document.getElementById('rhpForm').reset();
            clearErrors();

            // Set form values
            document.getElementById('modalTitle').textContent = 'Nouvelle assignation RHP';
            document.getElementById('rhpForm').action = '{{ route("de.rhp.store") }}';
            document.getElementById('method').value = '';
            document.getElementById('rhpId').value = '';
            document.getElementById('date').value = date;
            document.getElementById('period').value = period;

            // Show modal
            document.getElementById('rhpModal').classList.remove('hidden');
        }

        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Nouvelle assignation RHP';
            document.getElementById('rhpForm').action = '{{ route("de.rhp.store") }}';
            document.getElementById('method').value = '';
            document.getElementById('rhpId').value = '';
            document.getElementById('rhpForm').reset();

            // Hide all error messages
            clearErrors();

            // Set date to today if it's a weekday
            const today = new Date();
            const dayOfWeek = today.getDay();
            if (dayOfWeek <= 4) { // 0 = Sunday, 4 = Thursday
                document.getElementById('date').value = today.toISOString().split('T')[0];
            }

            document.getElementById('rhpModal').classList.remove('hidden');
        }

        function editRHP(id) {
            // Clear previous errors
            clearErrors();

            // Fetch RHP data and populate modal
            fetch(`/de/rhp/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Modifier assignation RHP';
                    document.getElementById('rhpForm').action = `/de/rhp/${id}`;
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

            // Add the _method field for Laravel method spoofing if it's a PUT request
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            // Clear previous errors
            clearErrors();

            fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    hideModal();
                    // Show success message
                    alert(data.message || 'Assignation enregistrée avec succès');
                    // Reload page to see changes
                    window.location.reload();
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
                    alert(data.message || 'Une erreur est survenue');
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
                fetch(`/de/rhp/${id}`, {
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

        function filterByMonth() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            window.location.href = `{{ route('de.rhp.index') }}?month=${month}&year=${year}`;
        }

        function filterByYear() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            window.location.href = `{{ route('de.rhp.index') }}?month=${month}&year=${year}`;
        }
    </script>
</x-de>
