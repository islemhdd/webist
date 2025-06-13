<x-brigade css="report">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-6">
            <!-- Reports Table -->
            <div class="overflow-hidden rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Matricule</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Nom</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Prenom</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Section</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">
                                Temps</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if ($reports->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Aucun rapport reçu.
                                </td>
                            </tr>
                        @else
                            @foreach ($reports as $report)
                                <tr onclick="window.location='{{ route('report.show', ['id' => $officer->id, 'report_id' => $report->id]) }}'"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->matricule }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        prenom
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                        {{ $report->student->section->code() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="status-badge status-{{ strtolower($report->status) }} {{ $report->refused ? 'status-refused' : '' }}">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $report->updated_at }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .status-badge {
            @apply px-3 py-1 text-xs font-medium rounded-full;
        }

        .status-done {
            @apply bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-100;
        }

        .status-refused {
            @apply bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-100;
        }

        .status-pending {
            @apply bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-100;
        }
    </style>
</x-brigade>
