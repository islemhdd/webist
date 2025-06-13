<x-infermerie css='medical_dashboard'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">
                <i class="{{ $specialtyIcon }} text-blue-600 dark:text-blue-400 mr-3"></i>
                @if($userRole === 'Medecin')
                    Tableau de Bord - Médecin Chef
                @else
                    Dashboard - {{ $specialtyName }}
                @endif
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                @if($userRole === 'Medecin')
                    Vue d'ensemble complète de l'activité médicale - {{ date('d/m/Y') }}
                @else
                    Gestion de votre spécialité médicale - {{ date('d/m/Y') }}
                @endif
            </p>
        </div>

        @if($userRole === 'Medecin')
        <!-- Section Résumé du jour (médecin chef uniquement) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg mb-8 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <i class="fas fa-calendar-day text-blue-600 mr-3"></i>
                    Résumé du jour
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="daily-summary">
                    <!-- Statistiques dynamiques du jour -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">Patients du jour</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100" id="today-patients">{{ $stats['total'] }}</p>
                            </div>
                            <i class="fas fa-user-injured text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 border border-green-200 dark:border-green-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-600 dark:text-green-400 text-sm font-medium">Validés</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100" id="today-validated">{{ $stats['validated'] }}</p>
                            </div>
                            <i class="fas fa-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">En attente</p>
                                <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100" id="today-pending">{{ $stats['pending'] }}</p>
                            </div>
                            <i class="fas fa-clock text-2xl text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-600 dark:text-purple-400 text-sm font-medium">RDV du jour</p>
                                <p class="text-2xl font-bold text-purple-900 dark:text-purple-100" id="today-appointments">0</p>
                            </div>
                            <i class="fas fa-calendar-check text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Statistiques pour médecins spécialisés -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">Total Patients</h3>
                        <p class="text-3xl font-bold mt-2">{{ $stats['total'] }}</p>
                    </div>
                    <i class="fas fa-users text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">Validés</h3>
                        <p class="text-3xl font-bold mt-2">{{ $stats['validated'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">En attente</h3>
                        <p class="text-3xl font-bold mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <i class="fas fa-clock text-4xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg p-6 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">Rejetés</h3>
                        <p class="text-3xl font-bold mt-2">{{ $stats['rejected'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-4xl opacity-80"></i>
                </div>
            </div>
        </div>
        @endif



        <!-- Actions rapides -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <a href="{{ route('medical.patients') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <i class="fas fa-users mr-2"></i>
                @if($userRole === 'Medecin')
                    Gérer Tous les Patients
                @else
                    Gérer Mes Patients
                @endif
            </a>
            <a href="{{ route('medical.appointments') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <i class="fas fa-calendar mr-2"></i>Gérer les RDV
            </a>
            @if($userRole === 'Medecin')
                <a href="{{ route('statistics.index') }}"
                   class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-chart-pie mr-2"></i>Statistiques Globales
                </a>
            @endif
            <button onclick="refreshDashboard()"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                <i class="fas fa-sync mr-2"></i>Actualiser
            </button>
        </div>

        <!-- Informations supplémentaires -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Rendez-vous du jour -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                        Rendez-vous du jour
                    </h2>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full" id="appointmentsCount">
                        Chargement...
                    </span>
                </div>
                <div id="todayAppointments">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                </div>
            </div>

            <!-- Informations spécialité / Chef médical -->
            @if($userRole !== 'Medecin')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Informations Spécialité
                </h2>

                @switch($allowedSpecialty)
                    @case('psycho')
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                            <h3 class="text-purple-800 font-medium mb-2">
                                <i class="fas fa-brain mr-2"></i>
                                Psychologie
                            </h3>
                            <p class="text-purple-700 text-sm">Suivi psychologique et accompagnement des étudiants en difficulté.</p>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Consultation psychologique</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Thérapie individuelle</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Accompagnement stress</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Soutien académique</li>
                        </ul>
                        @break

                    @case('dentiste')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <h3 class="text-green-800 font-medium mb-2">
                                <i class="fas fa-tooth mr-2"></i>
                                Dentiste
                            </h3>
                            <p class="text-green-700 text-sm">Soins dentaires et hygiène bucco-dentaire.</p>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Consultation dentaire</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Soins préventifs</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Détartrage</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Urgences dentaires</li>
                        </ul>
                        @break

                    @case('médecin générale')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h3 class="text-blue-800 font-medium mb-2">
                                <i class="fas fa-stethoscope mr-2"></i>
                                Médecine Générale
                            </h3>
                            <p class="text-blue-700 text-sm">Consultations générales et suivi médical global.</p>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Consultation générale</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Examens médicaux</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Vaccination</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Certificats médicaux</li>
                        </ul>
                        @break
                @endswitch
            </div>
            @else
            <!-- Statistiques par spécialité pour médecin chef -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fas fa-chart-bar text-yellow-500 mr-2"></i>
                    Répartition par Spécialité
                </h2>
                <div class="space-y-4" id="specialty-stats">
                    <!-- Statistiques par spécialité chargées dynamiquement -->
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-sm text-gray-500">Chargement...</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="mt-8">

            <!-- Aide pour médecin chef -->
            @if($userRole === 'Medecin')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fas fa-crown text-yellow-500 mr-2"></i>
                    Privilèges Médecin Chef
                </h2>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-yellow-800 text-sm">En tant que médecin chef, vous avez un accès complet à toutes les spécialités.</p>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Voir tous les patients</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Assigner des spécialités</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Statistiques globales</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Supervision complète</li>
                </ul>
            </div>
            @endif
        </div>

        <!-- Aide et Conseils -->
        <div class="mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    <i class="fas fa-question-circle text-yellow-500 mr-2"></i>
                    Aide et Conseils
                </h2>
                <div class="space-y-4">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                                onclick="toggleHelp('help1')">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">Comment valider un patient ?</span>
                                <i class="fas fa-chevron-down transition-transform" id="help1-icon"></i>
                            </div>
                        </button>
                        <div id="help1" class="hidden p-4 pt-0">
                            <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600">
                                <li>Allez dans "Patients" dans le menu</li>
                                <li>Cliquez sur "Valider" pour le patient concerné</li>
                                <li>Rédigez votre avis médical</li>
                                <li>Confirmez la validation</li>
                            </ol>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                                onclick="toggleHelp('help2')">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">Comment créer un rendez-vous ?</span>
                                <i class="fas fa-chevron-down transition-transform" id="help2-icon"></i>
                            </div>
                        </button>
                        <div id="help2" class="hidden p-4 pt-0">
                            <ol class="list-decimal list-inside space-y-1 text-sm text-gray-600">
                                <li>Allez dans "Rendez-vous" dans le menu</li>
                                <li>Cliquez sur "Nouveau RDV"</li>
                                <li>Remplissez le formulaire avec les informations du patient</li>
                                <li>Sélectionnez la date et l'heure</li>
                                <li>Validez la création</li>
                            </ol>
                        </div>
                    </div>

                    @if($allowedSpecialty === 'all')
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-4 focus:outline-none focus:bg-gray-50"
                                onclick="toggleHelp('help3')">
                            <div class="flex items-center justify-between">
                                <span class="font-medium">Gestion des spécialités (Médecin Chef)</span>
                                <i class="fas fa-chevron-down transition-transform" id="help3-icon"></i>
                            </div>
                        </button>
                        <div id="help3" class="hidden p-4 pt-0">
                            <div class="text-sm text-gray-600">
                                En tant que médecin chef, vous pouvez :
                                <ul class="list-disc list-inside space-y-1 mt-2">
                                    <li>Voir tous les patients de toutes spécialités</li>
                                    <li>Assigner des patients à des spécialistes</li>
                                    <li>Accéder aux statistiques globales</li>
                                    <li>Superviser l'activité médicale</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let currentPage = 1;
        let currentFilters = {
            search: '',
            specialty: '',
            status: ''
        };
        const userRole = '{{ $userRole }}';
        const isChief = userRole === 'Medecin';

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadTodayAppointments();
            if (isChief) {
                loadDailySummary();
                loadSpecialtyStats();
            }
        });

        // Configuration des écouteurs d'événements
        function setupEventListeners() {
            // Cette fonction n'est plus nécessaire car la liste des patients a été supprimée
        }

        // Charger le résumé du jour (médecin chef)
        function loadDailySummary() {
            if (!isChief) return;

            fetch('{{ route("medical.dashboard.stats") }}?type=daily')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('today-patients').textContent = data.patients || 0;
                    document.getElementById('today-validated').textContent = data.validated || 0;
                    document.getElementById('today-pending').textContent = data.pending || 0;
                    document.getElementById('today-appointments').textContent = data.appointments || 0;
                })
                .catch(error => {
                    console.error('Erreur lors du chargement du résumé:', error);
                });
        }

        // Charger les statistiques par spécialité (médecin chef)
        function loadSpecialtyStats() {
            if (!isChief) return;

            fetch('{{ route("medical.dashboard.stats") }}?type=specialty')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('specialty-stats');
                    if (data && data.length > 0) {
                        container.innerHTML = data.map(specialty => `
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full mr-3" style="background-color: ${getSpecialtyColor(specialty.type)}"></div>
                                    <span class="font-medium text-gray-900 dark:text-white">${getSpecialtyName(specialty.type)}</span>
                                </div>
                                <div class="flex space-x-4 text-sm">
                                    <span class="text-green-600">${specialty.validated} validés</span>
                                    <span class="text-yellow-600">${specialty.pending} en attente</span>
                                    <span class="text-gray-600">${specialty.total} total</span>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = `
                            <div class="text-center text-gray-500">
                                <i class="fas fa-chart-bar text-2xl mb-2 opacity-50"></i>
                                <p>Aucune donnée disponible</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des statistiques:', error);
                    document.getElementById('specialty-stats').innerHTML = `
                        <div class="text-center text-red-500">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>Erreur de chargement</p>
                        </div>
                    `;
                });
        }

        // Charger le tableau des patients (médecin chef)
        function loadPatientsTable() {
            if (!isChief) return;

            const params = new URLSearchParams({
                page: currentPage,
                search: currentFilters.search,
                specialty: currentFilters.specialty,
                status: currentFilters.status,
                per_page: 15
            });

            // Afficher l'indicateur de chargement
            const tbody = document.getElementById('patients-tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center">
                        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-gray-500">Chargement...</p>
                    </td>
                </tr>
            `;

            fetch(`{{ route("medical.patients.api") }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    renderPatientsTable(data.data);
                    updatePagination(data);
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des patients:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-red-500">
                                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                                <p>Erreur lors du chargement des patients</p>
                            </td>
                        </tr>
                    `;
                });
        }

        // Rendu du tableau des patients
        function renderPatientsTable(patients) {
            const tbody = document.getElementById('patients-tbody');

            if (patients.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-2 opacity-50"></i>
                            <p>Aucun patient trouvé</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = patients.map(patient => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${patient.student ? `${patient.student.prenom} ${patient.student.nom}` : 'N/A'}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    ${patient.matricule}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getSpecialtyBadgeClass(patient.type_medecin)}">
                            ${getSpecialtyName(patient.type_medecin)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(patient.valider)}">
                            ${getStatusText(patient.valider)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        ${formatDate(patient.created_at)}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2">
                            ${patient.valider === 0 ? `
                                <button onclick="validatePatient(${patient.id})"
                                        class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-check"></i>
                                </button>
                            ` : ''}
                            <button onclick="viewPatient(${patient.id})"
                                    class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Mise à jour de la pagination
        function updatePagination(data) {
            document.getElementById('showing-from').textContent = data.from || 0;
            document.getElementById('showing-to').textContent = data.to || 0;
            document.getElementById('total-patients').textContent = data.total || 0;

            const paginationNav = document.getElementById('pagination-nav');
            const totalPages = data.last_page || 1;

            let paginationHTML = '';

            // Bouton précédent
            paginationHTML += `
                <button onclick="changePage(${currentPage - 1})"
                        ${currentPage <= 1 ? 'disabled' : ''}
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage <= 1 ? 'cursor-not-allowed opacity-50' : ''}">
                    <i class="fas fa-chevron-left"></i>
                </button>
            `;

            // Pages
            for (let i = Math.max(1, currentPage - 2); i <= Math.min(totalPages, currentPage + 2); i++) {
                paginationHTML += `
                    <button onclick="changePage(${i})"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium ${i === currentPage ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50'}">
                        ${i}
                    </button>
                `;
            }

            // Bouton suivant
            paginationHTML += `
                <button onclick="changePage(${currentPage + 1})"
                        ${currentPage >= totalPages ? 'disabled' : ''}
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage >= totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                    <i class="fas fa-chevron-right"></i>
                </button>
            `;

            paginationNav.innerHTML = paginationHTML;
        }

        // Changer de page
        function changePage(page) {
            if (page < 1) return;
            currentPage = page;
            loadPatientsTable();
        }

        // Charger les rendez-vous du jour
        function loadTodayAppointments() {
            const route = isChief ? '{{ route("medical.appointments.stats") }}' : '{{ route("medical.appointments.stats") }}';
            fetch(`${route}?date_start={{ date("Y-m-d") }}&date_end={{ date("Y-m-d") }}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('appointmentsCount').textContent = data.today + ' RDV';

                const container = document.getElementById('todayAppointments');
                if (data.today > 0) {
                    container.innerHTML = `
                        <div class="flex justify-between items-center mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Aujourd'hui</span>
                            <span class="bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded-full">${data.today} rendez-vous</span>
                        </div>
                        <div class="flex justify-between items-center mb-3 p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <span class="text-gray-600 dark:text-gray-400">Cette semaine</span>
                            <span class="bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-200 text-xs font-medium px-2.5 py-0.5 rounded-full">${data.week || 0} rendez-vous</span>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('medical.appointments') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-eye mr-1"></i>Voir tous les RDV
                            </a>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <div class="text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                            <p class="mb-3">Aucun rendez-vous aujourd'hui</p>
                            <a href="{{ route('medical.appointments') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Créer un RDV
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                document.getElementById('todayAppointments').innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="text-red-800 text-sm">Erreur lors du chargement des rendez-vous</div>
                    </div>
                `;
            });
        }

        // Fonctions utilitaires
        function getSpecialtyColor(type) {
            const colors = {
                'psycho': '#8B5CF6',
                'dentiste': '#10B981',
                'médecin générale': '#3B82F6',
                'chef_médecin': '#F59E0B'
            };
            return colors[type] || '#6B7280';
        }

        function getSpecialtyName(type) {
            const names = {
                'psycho': 'Psychologie',
                'dentiste': 'Dentiste',
                'médecin générale': 'Médecin Général',
                'chef_médecin': 'Chef Médecin'
            };
            return names[type] || type || 'Non défini';
        }

        function getSpecialtyBadgeClass(type) {
            const classes = {
                'psycho': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                'dentiste': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'médecin générale': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'chef_médecin': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
            };
            return classes[type] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
        }

        function getStatusText(status) {
            const texts = {
                0: 'En attente',
                1: 'Validé',
                2: 'Rejeté'
            };
            return texts[status] || 'Inconnu';
        }

        function getStatusBadgeClass(status) {
            const classes = {
                0: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                1: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                2: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
            };
            return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Actions sur les patients
        function validatePatient(id) {
            const avis = prompt('Veuillez saisir votre avis médical :');
            if (!avis) return;

            fetch(`{{ route('medical.patients.validate', '') }}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ avis_medecin: avis })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Patient validé avec succès');
                    loadPatientsTable();
                    loadDailySummary();
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la validation');
            });
        }

        function viewPatient(id) {
            window.location.href = `{{ route('medical.patients') }}?patient=${id}`;
        }

        // Actualiser le dashboard
        function refreshDashboard() {
            location.reload();
        }

        // Fonction pour toggler les sections d'aide
        function toggleHelp(helpId) {
            const helpElement = document.getElementById(helpId);
            const iconElement = document.getElementById(helpId + '-icon');

            if (helpElement && iconElement) {
                if (helpElement.classList.contains('hidden')) {
                    helpElement.classList.remove('hidden');
                    iconElement.classList.add('rotate-180');
                } else {
                    helpElement.classList.add('hidden');
                    iconElement.classList.remove('rotate-180');
                }
            }
        }

        // Fonction pour afficher les statistiques globales (médecin chef seulement)
        function showStatsModal() {
            if (!isChief) return;
            // Rediriger vers la page des statistiques détaillées
            window.location.href = '{{ route("medical.statistics") }}';
        }

        // Gestion des filtres de recherche
        function applyFilters() {
            const searchInput = document.getElementById('search-input');
            const specialtyFilter = document.getElementById('specialty-filter');
            const statusFilter = document.getElementById('status-filter');

            if (searchInput) currentFilters.search = searchInput.value;
            if (specialtyFilter) currentFilters.specialty = specialtyFilter.value;
            if (statusFilter) currentFilters.status = statusFilter.value;

            currentPage = 1; // Reset pagination
            loadPatientsTable();
        }

        // Réinitialiser les filtres
        function resetFilters() {
            currentFilters = { search: '', specialty: '', status: '' };
            currentPage = 1;

            const searchInput = document.getElementById('search-input');
            const specialtyFilter = document.getElementById('specialty-filter');
            const statusFilter = document.getElementById('status-filter');

            if (searchInput) searchInput.value = '';
            if (specialtyFilter) specialtyFilter.value = '';
            if (statusFilter) statusFilter.value = '';

            loadPatientsTable();
        }

        // Gestionnaire d'événements pour la recherche en temps réel
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        applyFilters();
                    }, 500); // Délai de 500ms pour éviter trop de requêtes
                });
            }
        });
    </script>
</x-infermerie>
