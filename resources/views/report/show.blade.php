<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-2">
        <!-- Search Section -->
        <div class="mb-8">
            <form action="" class="flex gap-2 max-w-md mx-auto">
                <input type="text" id="recherch" name="recherch"
                    class="w-full px-4 py-2 rounded-full border border-gray-600 bg-gray-700/50 text-gray-100 focus:ring-2 focus:ring-pink-400 focus:border-transparent placeholder-gray-400"
                    placeholder="trouvez l'étudiant">
                <button type="submit"
                    class="px-4 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition-colors shadow-md">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>

        <!-- Report Content Card -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-3xl shadow-lg p-8 mb-8">
            <!-- Header -->
            <div class="text-center mb-8 space-y-2">
                <h1 class="text-xl font-bold text-white">
                    الــجمــهــوريــــــــة الجــــزائــريـــــــة الــديــمــقـراطيـــــــة الــــشعبيــــــة
                </h1>
                <div class="flex justify-between items-start">
                    <div class="space-y-1 text-right">
                        <p class="text-gray-300">وزارة الـدفـــــــاع الوطنــــــــي</p>
                        <p class="text-gray-300">أركـــــــــــــــــــــــــــــــــــــــان</p>
                        <p class="text-gray-300">الجيـش الوطـــــني الشــــــعبـي</p>
                        <p class="text-gray-300">المدرسـة الوطنية التحــضيريـة</p>
                        <p class="text-gray-300">لــدراســــــــــات المهـنـــــــدس</p>
                        <p class="text-gray-300">بــــــــــــــــاجي مختــــــــــــــار</p>
                        <p class="text-gray-300">قـســـــــــــــــم الـتعـــليـــــــــــم</p>
                        <p class="text-gray-300">لــــــــــــــواء الـطلـبــــــــــــــة</p>
                        <p class="text-gray-300">الكتيبــــــــــــــــة <span></span></p>
                    </div>
                    <img src="/img/enpei.png" alt="ENPEI" class="w-32 h-32 object-contain">
                </div>
            </div>

            <!-- Report Number -->
            <div class="text-right mb-6 text-gray-200">
                <p>رقم : <span
                        class="font-semibold">{{ $report->id }}</span>/{{ $report->created_at->format('Y') }}/ك{{ $report->student->grade }}/
                    ل ط / ق ت / م. و. ت. د .م</p>
            </div>

            <!-- Report Info -->
            <div class="space-y-4 mb-8 text-right">
                <p class="text-gray-200">الرويبة في: <span
                        class="font-semibold text-white">{{ $report->created_at }}</span></p>
                <p class="text-gray-200">الإسم واللقـب: <span
                        class="font-semibold text-white">{{ $report->student->nom }}</span></p>
                <p class="text-gray-200">السريــة: <span
                        class="font-semibold text-white">{{ $report->student->companie() }}</span></p>
            </div>

            <!-- Report Content -->
            <div class="mb-8">
                <h2 class="text-right mb-6 text-2xl font-bold text-white border-b border-gray-700/50 pb-4">
                    {{ $report->title }}</h2>
                <p class="text-gray-200 text-right leading-relaxed px-4">{{ $report->corps }}</p>
            </div>

            <!-- Avis Section -->
            <div class="space-y-6">
                @php
                    $roles = ['Chef de compagnie', 'Chef de batallaint', 'Chef de brigade', 'Chef division', 'Medecin', 'Directeur général'];
                    $stopped = false;
                @endphp

                @foreach ($roles as $role)
                    @if (!(!$report->is_medical && $role == 'Medecin'))
                        @php
                            $avis = 'Avis' . $role;
                            if ($report->refused && $report->status === $role) {
                                $stopped = true;
                            }
                        @endphp

                        @if (!$report->refused || !$stopped)
                            <div class="border-t dark:border-gray-700 pt-4">
                                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">
                                    {{ $role }}:
                                </h3>

                                @if ($officer->role->name == $role)
                                    @if (empty($report->$avis))
                                        <form id="avisForm">
                                            <div class="space-y-2">
                                                <textarea id="avisText" name="avis{{ strtolower($role) }}"
                                                    class="w-full p-4 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-pink-400 focus:border-transparent @error('avis') border-red-500 dark:border-red-500 @enderror"
                                                    rows="4" placeholder="{{ $role == 'Directeur général' ? 'donner votre decision' : 'votre avis' }}"></textarea>
                                                @error('avis')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="flex justify-end gap-4 mt-4">
                                                <button type="button" onclick="showRefuseModal()"
                                                    class="px-6 py-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 transition-colors shadow-md">
                                                    Refuser
                                                </button>
                                                <button type="button" onclick="showConfirm()"
                                                    class="px-6 py-2 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors shadow-md">
                                                    Soumettre
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div
                                            class="p-4 rounded-xl {{ $role === 'Directeur général' ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-700' }}">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $report->$avis }}</p>
                                        </div>
                                    @endif
                                @else
                                    @if (!empty($report->$avis))
                                        <div
                                            class="p-4 {{ $role === 'Directeur général' ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-700' }} rounded-xl">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $report->$avis }}</p>
                                        </div>
                                    @else
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                            <p class="text-gray-500 dark:text-gray-400">l'avis n'existe pas</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if ($report->refused && $report->status === $role)
                            <div class="border-t dark:border-gray-700 pt-6">
                                <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-6">
                                    <div class="flex items-center mb-4">
                                        <i class="fa-solid fa-circle-xmark text-red-500 text-xl mr-3"></i>
                                        <h3 class="text-lg font-semibold text-red-700 dark:text-red-400">
                                            Rapport refusé au niveau: {{ $role }}
                                        </h3>
                                    </div>
                                    <div class="pl-8">
                                        <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">Motif du
                                            refus:
                                        </h4>
                                        <p
                                            class="text-gray-700 dark:text-gray-300 bg-white/50 dark:bg-gray-800/50 rounded-lg p-4">
                                            {{ $report->motif }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @break
                        @endif
                    @endif
                @endforeach
            </div>
        </div>

        <!-- set avis -->
        @php
            $avis = 'Avis' . $officer->role->name;
        @endphp
        @if ($report->$avis == null && $officer->role->name != 'Medecin')
            <!-- Confirm Modal -->
            <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50">
                <div class="fixed inset-0 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-xl mx-4">
                        <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">Confirmer votre avis</h2>

                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-xl mb-6">
                            <p class="text-gray-700 dark:text-gray-300" id="avisPreview"></p>
                        </div>

                        <form action="{{ route('report.avis', ['report' => $report->id, 'id' => $officer->id]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="avis" id="avisInput">

                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="closeModal()"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors">
                                    Confirmer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Refuse Modal -->
            <div id="refuseModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden z-50">
                <div class="fixed inset-0 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6 w-full max-w-xl mx-4">
                        <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-gray-100">Refuser le rapport</h2>

                        <form action="{{ route('report.refuse', ['report' => $report->id, 'id' => $officer->id]) }}"
                            method="post">
                            @csrf
                            <div class="space-y-4">
                                <label class="block">
                                    <span class="text-gray-700 dark:text-gray-300">Motif du refus</span>
                                    <textarea name="motif" rows="4" required
                                        class="mt-1 w-full p-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-white resize-none @error('motif') border-red-500 dark:border-red-500 @enderror"
                                        placeholder="Veuillez expliquer le motif du refus..."></textarea>
                                    @error('motif')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </label>

                                <div class="flex justify-end gap-4">
                                    <button type="button" onclick="closeRefuseModal()"
                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                                        Annuler
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors">
                                        Confirmer le refus
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                function showConfirm() {
                    const avisText = document.getElementById('avisText').value.trim();
                    if (!avisText) {
                        alert('Veuillez saisir votre avis avant de continuer.');
                        return;
                    }

                    document.getElementById('avisPreview').textContent = avisText;
                    document.getElementById('avisInput').value = avisText;
                    document.getElementById('confirmModal').classList.remove('hidden');
                }

                function closeModal() {
                    document.getElementById('confirmModal').classList.add('hidden');
                }

                function showRefuseModal() {
                    document.getElementById('refuseModal').classList.remove('hidden');
                }

                function closeRefuseModal() {
                    document.getElementById('refuseModal').classList.add('hidden');
                }
            </script>
        @endif
    </div>
</x-brigade>
