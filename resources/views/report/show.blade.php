<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-2 transition-all duration-300">
        <!-- Search Section -->

        <!-- Report Content Card -->
        <div
            class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-3xl shadow-xl p-8 mb-8 border border-gray-100 dark:border-gray-700/30 hover:shadow-2xl transition-all duration-300">
            <!-- Header -->
            <div class="text-center mb-8 space-y-2">
                <h1
                    class="text-2xl font-bold text-gray-900 dark:text-white transition-colors duration-300 mb-4 tracking-wider">
                    الــجمــهــوريــــــــة الجــــزائــريـــــــة الــديــمــقـراطيـــــــة الــــشعبيــــــة
                </h1>
                <div class="flex justify-between items-start">
                    <div class="space-y-1 text-right">
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">وزارة الـدفـــــــاع
                            الوطنــــــــي</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">
                            أركـــــــــــــــــــــــــــــــــــــــان</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">الجيـش الوطـــــني
                            الشــــــعبـي</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">المدرسـة الوطنية
                            التحــضيريـة</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">لــدراســــــــــات
                            المهـنـــــــدس</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">بــــــــــــــــاجي
                            مختــــــــــــــار</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">قـســـــــــــــــم
                            الـتعـــليـــــــــــم</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">لــــــــــــــواء
                            الـطلـبــــــــــــــة</p>
                        <p class="text-gray-700 dark:text-gray-300 transition-colors duration-300">
                            الكتيبــــــــــــــــة <span></span></p>
                    </div>
                    <img src="/img/enpei.png" alt="ENPEI"
                        class="w-32 h-32 object-contain hover:scale-105 transition-transform duration-300">
                </div>
            </div>

            <!-- Report Number -->
            <div class="text-right mb-6 bg-gray-50 dark:bg-gray-800/80 p-4 rounded-xl transition-colors duration-300">
                <p class="text-gray-800 dark:text-gray-200">رقم : <span
                        class="font-semibold text-indigo-700 dark:text-indigo-300">{{ $report->id }}</span>/{{ $report->created_at->format('Y') }}/ك{{ $report->student->grade }}/
                    ل ط / ق ت / م. و. ت. د .م</p>
            </div>

            <!-- Report Info -->
            <div
                class="space-y-4 mb-8 text-right bg-gray-50 dark:bg-gray-800/80 p-4 rounded-xl transition-colors duration-300">
                <p class="text-gray-700 dark:text-gray-300">الرويبة في: <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $report->created_at }}</span></p>
                <p class="text-gray-700 dark:text-gray-300">الإسم واللقـب: <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $report->student->nom }}</span></p>
                <p class="text-gray-700 dark:text-gray-300">السريــة: <span
                        class="font-semibold text-gray-900 dark:text-white">{{ $report->student->companie() }}</span>
                </p>
            </div>

            <!-- Report Content -->
            <div
                class="mb-8 bg-white dark:bg-gray-800/80 p-6 rounded-xl shadow-sm transition-colors duration-300 hover:shadow-md">
                <h2
                    class="text-right mb-6 text-2xl font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700/50 pb-4">
                    {{ $report->title }}</h2>
                <p class="text-gray-700 dark:text-gray-200 text-right leading-relaxed px-4">{{ $report->corps }}</p>
            </div>

            <!-- Avis Section -->
            <div class="space-y-6">
                @php
                    $roles = [
                        'Chef de compagnie',
                        'Chef de batallaint',
                        'Chef de brigade',
                        'Chef division',
                        'Medecin',
                        'Directeur général',
                    ];
                    $stopped = false;

                    // Define role colors
                    $roleColors = [
                        'Chef de compagnie' => 'border-blue-200 dark:border-blue-800/50',
                        'Chef de batallaint' => 'border-indigo-200 dark:border-indigo-800/50',
                        'Chef de brigade' => 'border-purple-200 dark:border-purple-800/50',
                        'Chef division' => 'border-pink-200 dark:border-pink-800/50',
                        'Medecin' => 'border-green-200 dark:border-green-800/50',
                        'Directeur général' => 'border-amber-200 dark:border-amber-800/50',
                    ];

                    // Define role icon classes
                    $roleIcons = [
                        'Chef de compagnie' => 'fa-user-tie text-blue-600 dark:text-blue-400',
                        'Chef de batallaint' => 'fa-user-shield text-indigo-600 dark:text-indigo-400',
                        'Chef de brigade' => 'fa-user-tag text-purple-600 dark:text-purple-400',
                        'Chef division' => 'fa-user-check text-pink-600 dark:text-pink-400',
                        'Medecin' => 'fa-user-md text-green-600 dark:text-green-400',
                        'Directeur général' => 'fa-user-crown text-amber-600 dark:text-amber-400',
                    ];
                @endphp

                @foreach ($roles as $roleName)

                    @if (!(!$report->is_medical && $roleName == 'Medecin'))
                        @php
                            $avis = 'Avis' . str_replace(' ', '_', $roleName);
                            if ($report->refused && $report->status === $roleName) {
                                $stopped = true;
                            }

                            $roleColor = $roleColors[$roleName] ?? 'border-gray-200 dark:border-gray-700/50';
                            $roleIcon = $roleIcons[$roleName] ?? 'fa-user text-gray-600 dark:text-gray-400';
                        @endphp

                        @if (!$report->refused || !$stopped)
                            <div
                                class="border-t {{ $roleColor }} pt-4 transition-all duration-300 hover:shadow-md rounded-lg p-3 -mx-3">
                                <h3
                                    class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100 flex items-center">
                                    <i class="fas {{ $roleIcon }} mr-2"></i>
                                    {{ $roleName }}:
                                </h3>


                                @if ($officer->role->name == $roleName)
                                    @if (empty($report->$avis))
                                        <form id="avisForm" class="animate-fade-in">
                                            <div class="space-y-2">
                                                <textarea id="avisText" name="avis{{ strtolower($roleName) }}"
                                                    class="w-full p-4 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800/90 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-300 resize-none shadow-sm hover:shadow @error('avis') border-red-500 dark:border-red-500 @enderror"
                                                    rows="4"
                                                    placeholder="{{ $roleName == 'Directeur général' ? 'Donner votre décision...' : 'Écrire votre avis...' }}"></textarea>
                                                @error('avis')
                                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="flex justify-end gap-4 mt-4">
                                                <button type="button" onclick="showRefuseModal()"
                                                    class="px-6 py-2 bg-red-600 text-white font-medium rounded-full hover:bg-red-700 hover:scale-105 transition-all duration-300 shadow-md flex items-center">
                                                    <i class="fas fa-times-circle mr-2"></i> Refuser
                                                </button>
                                                <button type="button" onclick="showConfirm()"
                                                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-full hover:bg-indigo-500 hover:scale-105 transition-all duration-300 shadow-md flex items-center">
                                                    <i class="fas fa-check-circle mr-2"></i> Soumettre
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div
                                            class="p-4 rounded-xl {{ $roleName === 'Directeur général' ? 'bg-green-50 dark:bg-green-900/30' : 'bg-gray-50 dark:bg-gray-700/80' }} shadow-sm transition-all duration-300 border border-gray-100 dark:border-gray-600/20">
                                            <div class="flex">
                                                <i
                                                    class="fas fa-quote-left text-gray-400 dark:text-gray-500 mr-2 mt-1"></i>
                                                <p class="text-gray-700 dark:text-gray-300 flex-1">{{ $report->$avis }}
                                                </p>
                                                <i
                                                    class="fas fa-quote-right text-gray-400 dark:text-gray-500 ml-2 self-end"></i>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    @if (!empty($report->$avis))
                                        <div
                                            class="p-4 {{ $roleName == 'Directeur général' ? 'bg-green-50 dark:bg-green-900/30' : 'bg-gray-50 dark:bg-gray-700/80' }} rounded-xl shadow-sm hover:shadow transition-all duration-300 border border-gray-100 dark:border-gray-600/20">
                                            <div class="flex">
                                                <i
                                                    class="fas fa-quote-left text-gray-400 dark:text-gray-500 mr-2 mt-1"></i>
                                                <p class="text-gray-700 dark:text-gray-300 flex-1">{{ $report->$avis }}
                                                </p>
                                                <i
                                                    class="fas fa-quote-right text-gray-400 dark:text-gray-500 ml-2 self-end"></i>
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-600/30 transition-all duration-300">
                                            <p
                                                class="text-gray-500 dark:text-gray-400 flex items-center justify-center py-2">
                                                <i class="fas fa-hourglass-half mr-2 opacity-70"></i>
                                                {{ $roleName == 'Directeur général' ? 'Décision pas encore prise' : "Avis n'existe pas encore" }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if ($report->refused && $report->status === $roleName)
                            <div class="border-t border-red-300 dark:border-red-700 pt-6 mt-8">
                                <div
                                    class="bg-red-50 dark:bg-red-900/30 rounded-xl p-6 shadow-md border border-red-100 dark:border-red-800/30 transform transition-all duration-300 hover:shadow-lg">
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-10 h-10 bg-red-100 dark:bg-red-800/30 rounded-full flex items-center justify-center mr-4">
                                            <i
                                                class="fa-solid fa-circle-xmark text-red-500 dark:text-red-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-red-700 dark:text-red-400">
                                            Rapport refusé au niveau: {{ $roleName }}
                                        </h3>
                                    </div>
                                    <div class="pl-8">
                                        <h4
                                            class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center">
                                            <i
                                                class="fas fa-exclamation-circle mr-2 text-red-500 dark:text-red-400"></i>
                                            Motif du refus:
                                        </h4>
                                        <div
                                            class="bg-white/70 dark:bg-gray-800/70 rounded-lg p-4 border border-red-100 dark:border-red-800/20 shadow-inner">
                                            <p class="text-gray-700 dark:text-gray-300">
                                                {{ $report->motif }}
                                            </p>
                                        </div>
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
            $avis = 'Avis ' . $officer->role->name;
        @endphp
        @if ($report->$avis == null && $officer->role->name != 'Medecin')
            <!-- Confirm Modal -->
            <div id="confirmModal"
                class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-md hidden z-50 transition-opacity duration-300">
                <div class="fixed inset-0 flex items-center justify-center">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 w-full max-w-xl mx-4 border border-gray-100 dark:border-gray-700 transform transition-all duration-300 scale-100">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-check-circle text-indigo-600 dark:text-indigo-400 text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Confirmer votre avis</h2>
                        </div>

                        <div
                            class="p-6 bg-gray-50 dark:bg-gray-700/80 rounded-xl mb-6 border border-gray-100 dark:border-gray-600/30 shadow-inner">
                            <div class="flex">
                                <i class="fas fa-quote-left text-gray-400 dark:text-gray-500 mr-2 mt-1"></i>
                                <p class="text-gray-700 dark:text-gray-300 flex-1" id="avisPreview"></p>
                                <i class="fas fa-quote-right text-gray-400 dark:text-gray-500 ml-2 self-end"></i>
                            </div>
                        </div>

                        <form action="{{ route('report.avis', ['report' => $report->id, 'id' => $officer->id]) }}"
                            method="post">
                            @csrf
                            <input type="hidden" name="avis" id="avisInput">

                            <div class="flex justify-end gap-4">
                                <button type="button" onclick="closeModal()"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 flex items-center shadow-sm hover:shadow">
                                    <i class="fas fa-times mr-2"></i> Annuler
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-500 hover:scale-105 transition-all duration-300 flex items-center shadow-md hover:shadow-lg">
                                    <i class="fas fa-paper-plane mr-2"></i> Confirmer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Refuse Modal -->
            <div id="refuseModal"
                class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-md hidden z-50 transition-opacity duration-300">
                <div class="fixed inset-0 flex items-center justify-center">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-6 w-full max-w-xl mx-4 border border-gray-100 dark:border-gray-700 transform transition-all duration-300 scale-100">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Refuser le rapport</h2>
                        </div>

                        <form action="{{ route('report.refuse', ['report' => $report->id, 'id' => $officer->id]) }}"
                            method="post">
                            @csrf
                            <div class="space-y-4">
                                <label class="block">
                                    <span class="text-gray-700 dark:text-gray-300 flex items-center mb-2">
                                        <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                                        Motif du refus
                                    </span>
                                    <textarea name="motif" rows="4" required
                                        class="mt-1 w-full p-4 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-700 dark:text-white resize-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-300 shadow-inner @error('motif') border-red-500 dark:border-red-500 @enderror"
                                        placeholder="Veuillez expliquer le motif du refus..."></textarea>
                                    @error('motif')
                                        <p class="text-red-500 text-sm mt-1 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </label>

                                <div class="flex justify-end gap-4 pt-2">
                                    <button type="button" onclick="closeRefuseModal()"
                                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-full hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 flex items-center shadow-sm hover:shadow">
                                        <i class="fas fa-arrow-left mr-2"></i> Annuler
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 hover:scale-105 transition-all duration-300 flex items-center shadow-md hover:shadow-lg">
                                        <i class="fas fa-ban mr-2"></i> Confirmer le refus
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
                        // Enhanced alert with animation
                        const textarea = document.getElementById('avisText');
                        textarea.classList.add('ring-2', 'ring-red-500', 'animate-pulse');

                        // Create a floating message
                        const alertMsg = document.createElement('div');
                        alertMsg.className = 'text-sm text-red-500 font-medium flex items-center animate-fade-in mt-1';
                        alertMsg.innerHTML =
                            '<i class="fas fa-exclamation-circle mr-1"></i> Veuillez saisir votre avis avant de continuer.';

                        const container = textarea.parentNode;
                        container.appendChild(alertMsg);

                        setTimeout(() => {
                            textarea.classList.remove('ring-2', 'ring-red-500', 'animate-pulse');
                            alertMsg.remove();
                        }, 3000);
                        return;
                    }

                    document.getElementById('avisPreview').textContent = avisText;
                    document.getElementById('avisInput').value = avisText;

                    // Show modal with animation
                    const modal = document.getElementById('confirmModal');
                    modal.classList.remove('hidden');
                    modal.style.opacity = "0";
                    setTimeout(() => {
                        modal.style.opacity = "1";
                        const modalContent = modal.querySelector('.rounded-3xl');
                        modalContent.classList.add('scale-100');
                    }, 10);
                }

                function closeModal() {
                    const modal = document.getElementById('confirmModal');
                    const modalContent = modal.querySelector('.rounded-3xl');
                    modalContent.classList.add('scale-95');
                    modal.style.opacity = "0";
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modalContent.classList.remove('scale-95');
                    }, 300);
                }

                function showRefuseModal() {
                    // Show modal with animation
                    const modal = document.getElementById('refuseModal');
                    modal.classList.remove('hidden');
                    modal.style.opacity = "0";
                    setTimeout(() => {
                        modal.style.opacity = "1";
                        const modalContent = modal.querySelector('.rounded-3xl');
                        modalContent.classList.add('scale-100');
                    }, 10);
                }

                function closeRefuseModal() {
                    const modal = document.getElementById('refuseModal');
                    const modalContent = modal.querySelector('.rounded-3xl');
                    modalContent.classList.add('scale-95');
                    modal.style.opacity = "0";
                    setTimeout(() => {
                        modal.classList.add('hidden');
                        modalContent.classList.remove('scale-95');
                    }, 300);
                }

                // Add this to make text areas auto-resize
                document.addEventListener('DOMContentLoaded', () => {
                    const textAreas = document.querySelectorAll('textarea');
                    textAreas.forEach(area => {
                        area.style.overflow = 'hidden';
                        area.addEventListener('input', function() {
                            this.style.height = 'auto';
                            this.style.height = (this.scrollHeight) + 'px';
                        });

                        // Initial resize
                        setTimeout(() => {
                            area.style.height = 'auto';
                            area.style.height = (area.scrollHeight) + 'px';
                        }, 100);
                    });
                });
            </script>

            <style>
                @keyframes fade-in {
                    0% {
                        opacity: 0;
                    }

                    100% {
                        opacity: 1;
                    }
                }

                .animate-fade-in {
                    animation: fade-in 0.3s ease-in-out forwards;
                }

                @keyframes pulse {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    50% {
                        opacity: 0.5;
                    }
                }

                .animate-pulse {
                    animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }
            </style>
        @endif
    </div>
</x-brigade>
