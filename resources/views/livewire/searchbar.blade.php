<div class="relative">
    <!-- Search Type Selector -->
    <div class="flex gap-2 mb-3">
        <select wire:model.live="searchType"
            class="rounded-lg text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="matricule">Matricule</option>
            <option value="nom">Nom</option>
            <option value="grade">Grade</option>
            <option value="companie">Companie</option>
            <option value="section">Section</option>
        </select>
    </div>

    <!-- Search Input -->
    <div class="relative">
        <input type="text" wire:model.live="searchTerm"
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 py-2 pl-3 pr-10 focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Rechercher un étudiant...">
        @if ($searchTerm)
            <button wire:click="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>
</div>
