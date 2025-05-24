<div id="weekend-list">
    {{-- TODO SORT BY REGION !!!! --}}
    <livewire:searchbar :students="$students" />

    <div class="flex flex-wrap gap-4 my-4">
        <button type="button" wire:click="selector('all')"
            class="choix01 notA px-4 py-2 rounded-full font-semibold text-pink-500 bg-pink-100 dark:bg-pink-900 dark:text-pink-300 hover:bg-pink-200 transition">
            liste generale
        </button>
        <button type="button" wire:click="selector('ven')"
            class="choix02 px-4 py-2 rounded-full font-semibold text-purple-500 bg-purple-100 dark:bg-purple-900 dark:text-purple-300 hover:bg-purple-200 transition">
            vendredi
        </button>
        <button type="button" wire:click="selector('48h')"
            class="choix04 px-4 py-2 rounded-full font-semibold text-emerald-500 bg-emerald-100 dark:bg-emerald-900 dark:text-emerald-300 hover:bg-emerald-200 transition">
            48 heures
        </button>
        <button type="button" wire:click="selector('sam')"
            class="choix03 px-4 py-2 rounded-full font-semibold text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 transition">
            samedi
        </button>
    </div>

    <div class="marque {{ $this->lock }}">
        <form class="print" action="" method="post">
            @csrf
            @method('PUT')
            <p id="status" @class(['block' => $lock, 'hidden' => !$lock]) class="mb-4 text-red-500 font-semibold">
                the list is locked, for any further explanation contact your major
            </p>
            <div id="printable">
                <div class="overflow-x-auto rounded-2xl shadow">
                    <table
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded-2xl">
                        <thead>
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    matricule</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    nom</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    prenom</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    section</th>
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    samedi</th>
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    vendredi</th>
                                <th
                                    class="px-4 py-2 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                    48H</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                <div @if ($lock) style="opacity:0.5;" @endif>
                                    <livewire:studentssortie :lock="$lock" :student="$student" />
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="flex justify-center mt-8">
            @if ($role == 'CBt')
                <button id="click" wire:click="lockFire()"
                    class="px-6 py-2 rounded-full bg-purple-300 text-white font-semibold hover:bg-purple-400 transition">
                    Verrouiller la liste
                </button>
            @endif
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/echo.js') }}"></script>
    @endpush
</div>
