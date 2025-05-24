<x-infermerie css='compt'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- En-tête -->
                <div class="text-center space-y-2 mb-8">
                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">الــجمــهــوريــــــــة الجــــزائــريـــــــة الــديــمــقـراطيـــــــة الــــشعبيــــــة</p>
                    <div class="space-y-2 text-gray-700 dark:text-gray-300">
                        <p>وزارة الـدفـــــــاع الوطنــــــــي</p>
                        <p>أركـــــــــــــــــــــــــــــــــــــــان</p>
                        <p>الجيـش الوطـــــني الشــــــعبـي</p>
                        <p>المدرسـة الوطنية التحــضيريـة</p>
                        <p>لــدراســــــــــات المهـنـــــــدس</p>
                        <p>بــــــــــــــــاجي مختــــــــــــــار</p>
                        <p>قـســـــــــــــــم الـتعـــليـــــــــــم</p>
                    </div>
                </div>

                <!-- Recherche -->
                <div class="mb-8">
                    <form action="" class="flex items-center space-x-4">
                        <input type="text"
                               id="recherch"
                               name="recherch"
                               placeholder="Rechercher un étudiant"
                               class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <!-- Compte Rendu -->
                <div class="space-y-6">
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Votre avis</h1>
                    <textarea
                        name="avismed"
                        rows="10"
                        placeholder="Entrez votre avis..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"></textarea>
                </div>
            </div>
        </div>
    </div>
</x-infermerie>
