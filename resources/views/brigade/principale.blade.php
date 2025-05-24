<x-brigade css="principale">

    {{-- ? redondonce --}}
    @php
        $officer = (new \App\Models\Officer())->newInstance(auth()->user()->getAttributes());
    @endphp



    <div class="max-w-6xl mx-auto py-8 px-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Infirmerie Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6 flex flex-col">
                <h2 class="text-2xl font-bold text-pink-500 mb-4">infirmerie</h2>
                <div class="flex flex-col gap-2 mb-4">
                    <div class="nbinf h-8 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                    <div class="nbhos h-8 bg-gray-200 dark:bg-gray-700 rounded-xl"></div>
                </div>
                <img src="/infermerie.jpg" alt="Infirmerie"
                    class="rounded-xl w-40 h-32 object-cover self-end mb-4 border border-gray-200 dark:border-gray-700">
                <div>
                    <a href="/infermerie"
                        class="inline-block px-6 py-2 rounded-full bg-purple-300 text-white font-semibold hover:bg-purple-400 transition">
                        voire plus
                    </a>
                </div>
            </div>

            <!-- Ordres Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6 flex flex-col">
                <h2 class="text-2xl font-bold text-pink-500 mb-4">Donner des ordres</h2>
                <div class="historique h-16 bg-gray-200 dark:bg-gray-700 rounded-xl mb-4"></div>
                <div>
                    <a href="/ordres"
                        class="inline-block px-6 py-2 rounded-full bg-purple-300 text-white font-semibold hover:bg-purple-400 transition">
                        voire plus
                    </a>
                </div>
            </div>

            <!-- Consignes Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6 flex flex-col md:col-span-2">
                <h2 class="text-2xl font-bold text-pink-500 mb-4">liste consigné</h2>
                <div class="nomcons h-16 bg-gray-200 dark:bg-gray-700 rounded-xl mb-4"></div>
                <div>
                    <a href="/cons"
                        class="inline-block px-6 py-2 rounded-full bg-purple-300 text-white font-semibold hover:bg-purple-400 transition">
                        voire plus
                    </a>
                </div>
            </div>

            <!-- Illustration + Élève Search -->
            <!-- Illustration + Élève Search (Bottom Section) -->
            <div
                class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6 flex flex-col md:flex-row items-center gap-8 md:col-span-2">
                <img src="/rechercheleve.jpg" alt="Recherche Élève"
                    class="w-full md:w-1/3 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 mb-4 md:mb-0">
                <form action="/profile" method="post" class="flex-1 flex flex-col justify-center space-y-4">
                    @csrf
                    <div>
                        <label for="nom" class="block text-right text-pink-400 font-semibold mb-1">nom</label>
                        <input type="text" name="nom" id="nom"
                            class="w-full p-2 rounded-full bg-gray-200 dark:bg-gray-700 border-none text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                    </div>
                    <div>
                        <label for="prenom" class="block text-right text-pink-400 font-semibold mb-1">prenom</label>
                        <input type="text" name="prenom" id="prenom"
                            class="w-full p-2 rounded-full bg-gray-200 dark:bg-gray-700 border-none text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                    </div>
                    <div>
                        <label for="mat"
                            class="block text-right text-pink-400 font-semibold mb-1">matricule</label>
                        <input type="text" name="mat" id="mat"
                            class="w-full p-2 rounded-full bg-gray-200 dark:bg-gray-700 border-none text-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400">
                    </div>
                    <div class="flex justify-end">
                        <input type="submit" id="submit" value="Envoyer"
                            class="bg-purple-300 hover:bg-purple-400 text-white font-semibold py-2 px-8 rounded-full transition-colors duration-200 cursor-pointer">
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-brigade>
