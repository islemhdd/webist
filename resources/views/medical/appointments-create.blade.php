<x-infermerie css='createRendezvous'>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                        Nouveau rendez-vous - {{ $specialtyName }}
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <i class="fas {{ $specialtyIcon }} w-4 h-4 mr-2"></i>
                            {{ $specialtyName }}
                        </span>
                    </div>
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('medical.appointments.store') }}" class="space-y-6">
                    @csrf

                    <!-- Matricule -->
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Matricule de l'étudiant
                        </label>
                        <input type="text"
                               id="matricule"
                               name="matricule"
                               value="{{ old('matricule') }}"
                               placeholder="Entrez le matricule de l'étudiant (7 chiffres)"
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
                            @foreach($motifs as $motif)
                                <option value="{{ $motif }}" {{ old('motif') == $motif ? 'selected' : '' }}>
                                    {{ ucfirst($motif) }}
                                </option>
                            @endforeach
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
                            @foreach($services as $service)
                                <option value="{{ $service }}" {{ old('service') == $service ? 'selected' : '' }}>
                                    {{ $service }}
                                </option>
                            @endforeach
                        </select>
                        @error('service')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date et heure -->
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

                    <!-- Type de médecin (affiché mais automatique) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Spécialité médicale
                        </label>
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                <i class="fas {{ $specialtyIcon }} w-4 h-4 mr-2"></i>
                                {{ $specialtyName }} (automatique)
                            </span>
                        </div>
                        <input type="hidden" name="type_medecin" value="{{ $allowedSpecialty }}">
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('medical.appointments') }}"
                           class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                            Annuler
                        </a>
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
