<div id="weekend-list">
    {{-- TODO SORT BY REGION !!!! --}}
    <livewire:searchbar :students="$students" />

    <div class="flex flex-wrap gap-4 my-4 justify-center">
        <button type="button" wire:click="selector('all')"
            class="choix01 notA px-4 py-2 rounded-full font-semibold text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 transition shadow-lg">
            liste generale
        </button>
        <button type="button" wire:click="selector('ven')"
            class="choix02 px-4 py-2 rounded-full font-semibold  text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200transition shadow-lg">
            vendredi
        </button>
        <button type="button" wire:click="selector('48h')"
            class="choix04 px-4 py-2 rounded-full font-semibold   text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 transition shadow-lg"">
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
                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800 rounded-2xl shadow_sm ">
                        <thead class="bg-sky-200">
                            <tr>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    matricule</th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    nom</th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    prenom</th>
                                <th
                                    class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    section</th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    samedi</th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    vendredi</th>
                                <th
                                    class="px-4 py-4 text-center text-xs font-bold text-white dark:text-gray-300 uppercase">
                                    48H</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            @foreach ($students as $student)
                                <tr
                                    class="@if ($lock) opacity-50 @endif odd:bg-gray-50 even:bg-white dark:odd:bg-gray-700 dark:even:bg-gray-800">
                                    <livewire:studentssortie :wire:key="'student-'.$student->matricule"
                                        :lock="$lock" :student="$student" />
                                </tr>
                            @endforeach
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="flex justify-center mt-8">
            @if ($role == 'CBt')
                <button id="click" wire:click="lockFire()"
                    class="px-6 py-2 rounded-md bg-gray-500 text-white font-semibold hover:bg-gray-400 transition">
                    Verrouiller la liste
                </button>
            @endif
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('js/echo.js') }}"></script>
    @endpush
</div>
