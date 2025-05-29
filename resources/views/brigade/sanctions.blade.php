<x-brigade css="sanctions">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg">
            <div class="p-6">
                <!-- Header with Add and Filter -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex items-center">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Liste des Sanctions</h2>
                    </div>
                    <div>
                        <button onclick="document.getElementById('add-sanction-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Nouvelle Sanction
                        </button>
                    </div>
                </div>

                <!-- Filter -->
                <div class="mt-4 sm:mt-0">
                    <form method="GET" action="{{ route('sanctions.index', ['id' => $id]) }}"
                        class="flex items-center">
                        <div class="relative w-72">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-filter text-gray-400"></i>
                            </div>
                            <select name="type" id="type"
                                class="block w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                onchange="this.form.submit()">
                                <option value="" {{ !$selectedType ? 'selected' : '' }}>Toutes les sanctions
                                </option>
                                <option value="consigne" {{ $selectedType === 'consigne' ? 'selected' : '' }}>
                                    Consignes weekend</option>
                                <option value="arret" {{ $selectedType === 'arret' ? 'selected' : '' }}>Arrêts
                                </option>
                                <option value="blame" {{ $selectedType === 'blame' ? 'selected' : '' }}>Blâmes
                                </option>
                                <option value="avert" {{ $selectedType === 'avert' ? 'selected' : '' }}>
                                    Avertissements</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (session('success-primary'))
            <div id="success-primary-message"
                class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/20 dark:text-green-300 dark:border-green-500 transition-colors duration-200">
                {{ session('success-primary') }}
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('success-primary-message').style.display = 'none';
                }, 2000);
            </script>
        @endif

        @if (session('success-secondary'))
            <div id="success-secondary-message"
                class="mb-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-500 transition-colors duration-200">
                {{ session('success-secondary') }}
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('success-secondary-message').style.display = 'none';
                }, 2000);
            </script>
        @endif

        @if (session('error'))
            <div id="error-message"
                class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/20 dark:text-red-300 dark:border-red-500 transition-colors duration-200">
                {{ session('error') }}
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('error-message').style.display = 'none';
                }, 2000);
            </script>
        @endif

        <!-- Sanctions Table -->
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div
                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg dark:ring-opacity-5 dark:ring-white">
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Matricule
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Nom
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Prénom
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Section
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Type
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Période
                                    </th>
                                    <th scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Motif
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                                @foreach ($sanctions as $sanction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 group">
                                        <td
                                            class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sanction->matricule }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sanction->student->nom }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sanction->student->prenom }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sanction->student->section->id }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span
                                                class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $sanction->type === 'arret'
                                                    ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300'
                                                    : ($sanction->type === 'consigne'
                                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300'
                                                        : ($sanction->type === 'blame'
                                                            ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300'
                                                            : 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300')) }}">
                                                {{ ucfirst($sanction->type) }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            @if ($sanction->type === 'arret')
                                                {{ \Carbon\Carbon::parse($sanction->date_debut)->format('d/m/Y') }}
                                                - {{ \Carbon\Carbon::parse($sanction->date_fin)->format('d/m/Y') }}
                                            @elseif($sanction->type === 'consigne')
                                                Weekend
                                                {{ \Carbon\Carbon::parse($sanction->date_debut)->format('d/m/Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($sanction->date_debut)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $sanction->motif }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 px-6">
            {{ $sanctions->links() }}
        </div>
    </div>
    </div>

    {{-- Modal d'ajout de sanction --}}
    <div id="add-sanction-modal" class="fixed inset-0 bg-black/50 hidden z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button type="button"
                        onclick="document.getElementById('add-sanction-modal').classList.add('hidden')"
                        class="rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="sr-only">Fermer</span>
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                            Nouvelle Sanction
                        </h3>

                        <form action="{{ route('sanctions.store', compact('id')) }}" method="POST"
                            class="mt-6 space-y-4">
                            @csrf

                            <div>
                                <label for="matricule"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Matricule de l'étudiant
                                </label>
                                <input type="text" name="matricule" id="matricule" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label for="sanctionType"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Type de sanction
                                </label>
                                <select name="type" id="sanctionType" onchange="handleSanctionTypeChange()" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="consigne">Consigne</option>
                                    <option value="arret">Arrêt</option>
                                    <option value="blame">Blâme</option>
                                    <option value="avert">Avertissement</option>
                                </select>
                            </div>

                            <div id="dateFields" class="hidden space-y-4">
                                <div>
                                    <label for="from"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Date de début
                                    </label>
                                    <input type="date" name="from" id="from"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                                <div>
                                    <label for="to"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Date de fin
                                    </label>
                                    <input type="date" name="to" id="to"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                </div>
                            </div>

                            <div>
                                <label for="motif"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Motif
                                </label>
                                <textarea name="motif" id="motif" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Décrivez le motif de la sanction..."></textarea>
                            </div>

                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                                    Ajouter la sanction
                                </button>
                                <button type="button"
                                    onclick="document.getElementById('add-sanction-modal').classList.add('hidden')"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleSanctionTypeChange() {
            const type = document.getElementById('sanctionType').value;
            const dateFields = document.getElementById('dateFields');
            const fromInput = document.getElementById('from');
            const toInput = document.getElementById('to');

            if (type === 'arret') {
                dateFields.classList.remove('hidden');
                fromInput.required = true;
                toInput.required = true;
            } else if (type === 'consigne') {
                dateFields.classList.add('hidden');
                // Set the date to next weekend automatically
                const nextWeekend = getNextWeekend();
                fromInput.value = nextWeekend;
                toInput.value = nextWeekend;
                fromInput.required = false;
                toInput.required = false;
            } else {
                dateFields.classList.add('hidden');
                // For other types, set both dates to today
                const today = new Date().toISOString().split('T')[0];
                fromInput.value = today;
                toInput.value = today;
                fromInput.required = false;
                toInput.required = false;
            }
        }

        function getNextWeekend() {
            const today = new Date();
            const nextSaturday = new Date();
            nextSaturday.setDate(today.getDate() + (6 - today.getDay()));
            return nextSaturday.toISOString().split('T')[0];
        }

        // Initialize date fields on page load
        document.addEventListener('DOMContentLoaded', handleSanctionTypeChange);
    </script>
    </div>
</x-brigade>
