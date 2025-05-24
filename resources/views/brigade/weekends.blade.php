<x-brigade css="weekend">
    <div class="max-w-6xl mx-auto py-8 px-2">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow p-6">
            <h2 class="text-2xl font-bold text-pink-500 mb-6">Gestion des Week-ends</h2>

            <livewire:weekendlist :lock="$lock" :bat="$officer->bat" :role="$officer->role->name" :students="$students" />
        </div>
    </div>


</x-brigade>
