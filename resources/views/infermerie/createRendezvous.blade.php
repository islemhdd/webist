<x-infermerie css='createRendezvous'>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-6">Créer un rendez-vous</h2>

                <form method="POST" action="{{ route('liste_rdv.store') }}" class="space-y-6">
                    @csrf

                    <!-- Matricule -->
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Matricule
                        </label>
                        <input type="text"
                               id="matricule"
                               name="matricule"
                               value="{{ old('matricule') }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                               required>
                        @error('matricule')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Motif -->
                    <div>
                        <label for="motif" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Motif
                        </label>
                        <select name="motif"
                                id="motif"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="consultation" {{ old('motif') == 'consultation' ? 'selected' : '' }}>
                                Consultation
                            </option>
                            <option value="urgences" {{ old('motif') == 'urgences' ? 'selected' : '' }}>
                                Urgences
                            </option>
                        </select>
                        @error('motif')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service -->
                    <div>
                        <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Service
                        </label>
                        <select name="service"
                                id="service"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                                required>
                            <option value="">Sélectionnez un service</option>
                            @foreach($services as $value => $label)
                                <option value="{{ $value }}" {{ old('service') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('service')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Date et heure
                        </label>
                        <input type="datetime-local"
                               id="date"
                               name="date"
                               value="{{ old('date') }}"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300"
                               required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Créer le rendez-vous
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-infermerie>
