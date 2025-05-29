<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6">
            <!-- Reports Table -->
            <div class="overflow-x-auto rounded-2xll">
                <table class="w-full shadow-md rounded-sm">
                    <thead class="bg-sky-200">
                        <tr>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Matricule
                            </th>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Nom
                            </th>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Prenom
                            </th>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Section
                            </th>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                status
                            </th>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                temps
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @if ($reports->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                    Aucun rapport reçu.
                                </td>
                            </tr>
                        @else
                            @foreach ($reports as $report)
                                <tr onclick="window.location='{{ route('report.show', ['id' => $officer->id, 'report_id' => $report->id]) }}'"
                                    class="
                                        cursor-pointer transition-all duration-200 ease-in-out
                                        transform hover:-translate-y-px
                                        mb-3 rounded-lg shadow-sm
                                        @if ($report->refused) bg-white dark:bg-amber-900/10 hover:bg-sky-50 dark:hover:bg-amber-900/20
                                        @elseif ($report->status === 'DONE')
                                            bg-white dark:bg-indigo-900/10 hover:bg-sky-50 dark:hover:bg-indigo-900/20
                                        @else
                                            bg-white dark:bg-gray-800 hover:bg-sky-50 dark:hover:bg-gray-700/70 @endif
                                    ">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->matricule }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->nom }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        prenom
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->section->code() }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->status }}
                                    </td>
                                    <td class=" px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->updated_at }}
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>




</x-brigade>
