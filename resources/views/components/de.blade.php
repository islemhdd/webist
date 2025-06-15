@props(['css' => ''])

<!DOCTYPE html>
<html lang="fr" x-data="{ theme: localStorage.getItem('color-theme') === 'dark' || (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light' }"
      :class="{ 'dark': theme === 'dark' }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'DE - Direction d\'Études' }}</title>
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/css/all.min.css') }}">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    @if($css)
        <link rel="stylesheet" href="{{ asset('css/' . $css . '.css') }}">
    @endif

    @livewireStyles
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 border-r dark:border-gray-700 fixed h-full shadow-lg transition-colors duration-200 overflow-scroll">
            <div class="flex items-center justify-center p-4  ">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-[50%] rounded-full">
                
            </div>

            <nav class="p-4 space-y-2">
                @include('components.sidebar-menu')
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <!-- Top Navigation -->
            <header dir="rtl" class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 h-16 fixed z-20 transition-colors duration-200 rounded-3xl shadow-xl  w-[77%] mx-12 space-x-reverse  ">
                @include('components.header-de')
            </header>

            <!-- Main Content Area -->
            <main class="pt-16 p-6  " >
                <!-- Flash Messages -->
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/20 dark:text-green-300 transition-colors duration-200" role="alert">
                            <div class="flex items-center">
                                <div class="py-1">
                                    <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Succès</p>
                                    <p class="text-sm">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/20 dark:text-red-300 transition-colors duration-200" role="alert">
                            <div class="flex items-center">
                                <div class="py-1">
                                    <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Erreur</p>
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/20 dark:text-red-300 transition-colors duration-200" role="alert">
                            <div class="flex items-center">
                                <div class="py-1">
                                    <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Erreurs de validation</p>
                                    <ul class="mt-1 text-sm list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts

    <!-- Theme Toggle Script -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                current: localStorage.getItem('color-theme') || 'light',
                toggle() {
                    this.current = this.current === 'light' ? 'dark' : 'light';
                    localStorage.setItem('color-theme', this.current);
                    document.documentElement.classList.toggle('dark', this.current === 'dark');
                }
            });
        });
    </script>

    {{ $script ?? '' }}
</body>
</html>
