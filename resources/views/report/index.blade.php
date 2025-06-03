<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6">
            <!-- Reports Table -->
            <div class="overflow-x-auto rounded-2xl">
                <table class="w-full shadow-md rounded-sm">
                    <thead class="bg-grey-700 ">
                        <tr>
                            <th class=" px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Matricule
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Nom
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Prenom
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Section
                            </th>
                            <th class="px-4 py-4 text-left text-xs font-bold text-white dark:text-gray-300 uppercase">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0  divide-gray-200">
                        <!-- New Report Button -->
                        <div class="flex justify-end mt-5 mb-9 mr-3 ">
                            <a href="{{ route('report.create', ['id' => $officer->id]) }}"
                                class="px-6 py-2 rounded-md font-semibold   text-blue-500 bg-blue-100 dark:bg-blue-900 dark:text-blue-300 hover:bg-blue-200 transition shadow-lg">
                                Nouveau Rapport +
                            </a>
                        </div>
                        @if ($reports->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">
                                    No reports available.
                                </td>
                            </tr>
                        @else
                            @foreach ($reports as $report)
                                <tr onclick="window.location='{{ route('report.show', ['id' => $officer->id, 'report_id' => $report->id]) }}'"
                                    class="
                                        cursor-pointer transition-all duration-200 ease-in-out
                                        transform hover:-translate-y-px
                                        mb-3 rounded-lg shadow-sm

                                            bg-grey-700">

                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white first:rounded-l-lg">
                                        {{ $report->student->matricule }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $report->student->nom }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        prenom
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $report->student->section->code() }}
                                    </td>
                                    <td
                                        class="@if ($report->refused) text-red-500 @elseif($report->status == 'DONE') text-green-500 @else text-gray-100 @endif e px-4 py-4 text-sm last:rounded-r-lg">
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
