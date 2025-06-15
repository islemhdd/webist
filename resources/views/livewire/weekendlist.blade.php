<div id="weekend-list">
    <div class="relative">
        @if ($role == 'Chef de batallaint')
            <div class="flex justify-center mt-8">
                <button wire:click="lockFire"
                    class="px-6 py-2 rounded-md bg-gray-500 text-white font-semibold hover:bg-gray-400 transition">
                    {{ $lock ? 'Déverrouiller' : 'Verrouiller' }} la liste
                </button>
            </div>
        @endif
        <!-- Search type selector -->
        <div class="flex gap-2 mb-3">
            <select wire:model="searchType"
                class="rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="matricule">Matricule</option>
                <option value="nom">Nom</option>
                <option value="grade">Grade</option>
                <option value="companie">Compagnie</option>
                <option value="section">Section</option>
            </select>
        </div>

        <!-- Search field -->
        <div class="relative">
            <input type="text" wire:model.live.debounce.500ms="searchTerm"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 py-2 pl-3 pr-10 focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Rechercher un étudiant...">

            @if ($searchTerm)
                <button wire:click="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            @endif
        </div>
    </div>

    <!-- Filter buttons -->
    <div class="flex flex-wrap gap-4 my-4 justify-center">
        <button type="button" wire:click="selector('all')" @class([
            'px-4 py-2 rounded-full font-semibold transition shadow-lg',
            'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200' =>
                $choice !== 'all',
            'text-white bg-blue-500 dark:bg-blue-700' => $choice === 'all',
        ])>
            Liste générale
        </button>
        <button type="button" wire:click="selector('ven')" @class([
            'px-4 py-2 rounded-full font-semibold transition shadow-lg',
            'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200' =>
                $choice !== 'ven',
            'text-white bg-blue-500 dark:bg-blue-700' => $choice === 'ven',
        ])>
            Vendredi
        </button>
        <button type="button" wire:click="selector('48h')" @class([
            'px-4 py-2 rounded-full font-semibold transition shadow-lg',
            'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200' =>
                $choice !== '48h',
            'text-white bg-blue-500 dark:bg-blue-700' => $choice === '48h',
        ])>
            48 heures
        </button>
        <button type="button" wire:click="selector('sam')" @class([
            'px-4 py-2 rounded-full font-semibold transition shadow-lg',
            'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200' =>
                $choice !== 'sam',
            'text-white bg-blue-500 dark:bg-blue-700' => $choice === 'sam',
        ])>
            Samedi
        </button>
        <button type="button" wire:click="selector('pasMarquer')" @class([
            'px-4 py-2 rounded-full font-semibold transition shadow-lg',
            'text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200' =>
                $choice !== 'pasMarquer',
            'text-white bg-blue-500 dark:bg-blue-700' => $choice === 'pasMarquer',
        ])>
            Non marqué
        </button>
    </div>

    <!-- Student list -->
    <div class="marque">
        <div class="print">
            <p @class([
                'mb-4 text-red-500 font-semibold',
                'block' => $lock,
                'hidden' => !$lock,
            ])>
                La liste est verrouillée, pour toute explication supplémentaire contactez votre major
            </p>

            <div id="printable">
                <div class="overflow-x-auto rounded-2xl shadow">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded-2xl">
                        <thead class="bg-sky-200 dark:bg-sky-800">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase">Matricule</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase">Nom</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase">Prénom</th>
                                <th class="px-4 py-4 text-left text-xs font-bold text-white uppercase">Section</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase">Samedi</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase">Vendredi</th>
                                <th class="px-4 py-4 text-center text-xs font-bold text-white uppercase">48H</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($students as $student)
                                <tr class="odd:bg-gray-50 even:bg-white dark:odd:bg-gray-700 dark:even:bg-gray-800">
                                    <livewire:studentssortie :wire:key="'student-'.$student->matricule"
                                        :lock="$lock" :student="$student" />
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                        Aucun étudiant trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>

    @push('scripts')
        <script src="{{ asset('js/echo.js') }}"></script>
        <script>
            document.addEventListener('livewire:init', () => {
                Echo.channel('sortie-locked.{{ $bat }}')
                    .listen('SortieLocked', (e) => {
                        Livewire.dispatch('lockUpdated', {
                            lock: e.lockStatus
                        });
                    });
            });
        </script>
    @endpush
</div>
