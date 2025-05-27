<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6">
            <!-- Reports Table -->
            <div class="overflow-x-auto rounded-2xl">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Matricule
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Nom
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Prenom
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Section
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 dark:text-gray-300 uppercase">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0 divide-transparent">
                        @if ($reports->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No reports available.
                                </td>
                            </tr>
                        @else
                            <!-- New Report Button -->
                            <div class="flex justify-start mt-8">
                                <a href="{{ route('report.create', ['id' => $officer->id]) }}"
                                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition-colors shadow-md">
                                    Nouveau Rapport
                                </a>
                            </div>
                            @foreach ($reports as $report)
                                <tr onclick="window.location='{{ route('report.show', ['id' => $officer->id, 'report_id' => $report->id]) }}'"
                                    class="
                                        cursor-pointer transition-all duration-200 ease-in-out
                                        transform hover:-translate-y-px
                                        mb-3 rounded-lg shadow-sm
                                        @if ($report->refused) bg-amber-50 dark:bg-amber-900/10 hover:bg-amber-100/80 dark:hover:bg-amber-900/20
                                        @elseif ($report->status === 'DONE')
                                            bg-indigo-50 dark:bg-indigo-900/10 hover:bg-indigo-100/80 dark:hover:bg-indigo-900/20
                                        @else
                                            bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/70 @endif
                                    ">

                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100 first:rounded-l-lg">
                                        {{ $report->student->matricule }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->nom }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        prenom
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->section->code() }}
                                    </td>
                                    <td
                                        class="@if ($report->refused) text-red-500 @elseif($report->status == 'DONE') text-green-400 @else text-gray-100 @endif e px-4 py-4 text-sm last:rounded-r-lg">
                                        {{ $report->status }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>



        </div>

        {{-- todo
    @push('scripts')
        <script src="{{ asset('js/echo.js') }}"></script>
    @endpush  --}}
</x-brigade>
