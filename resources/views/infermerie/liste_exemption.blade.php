<x-infermerie css='liste_exemption'>
    <div class="container mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
            <!-- Liste des exemptions -->
            
                
                    <h1 class="text-2xl font-semibold text-sky-400 dark:text-gray-200 flex justify-center mb-6">Liste des exemptions</h1>
                  
                    <form method="GET" action="{{ route('exemptions.index') }}" class="mb-6 flex items-center gap-4 w-full ">
                        <div class="space-y-10 w-[100%] mx-12">
                       <div class=" "> <label class="text-sm text-gray-700 dark:text-gray-200">
                        Date début: </label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="mx-2 rounded-full h-8 border p-1 pl-2 w-[40%]  border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                   
                    <label class="text-sm text-gray-700 dark:text-gray-200">
                        Date fin:</label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="mx-2 rounded-full border h-8 p-1 pl-2 w-[40%] border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class=" flex justify-center space-x-10">
                        <button type="submit" class="px-4 py-2 bg-sky-500 text-white mx-10 w-[12%] rounded hover:bg-sky-400">Filtrer</button>
                        <a href="{{ route('exemptions.index') }}" class="px-6 py-2 bg-gray-400 text-white w-[12%]  rounded hover:bg-gray-500">Réinitialiser</a>
                    </div>
                    </div>
                    </form>
                  

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr class="bg-sky-100">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matricule</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motif</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date début</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date fin</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-800">
                                @foreach($exemptions as $exemption)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->matricule }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->motif }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->date_debut }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $exemption->date_fin }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                
           
        </div>
    </div>
</x-infermerie>
