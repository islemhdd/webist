@props(['css'])
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/logo.png">
    <title>wibist:{{ $css }}</title>

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="/css/{{ $css }}.css">

    <!-- Scripts -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <script src="{{ asset('js/theme.js') }}" defer></script>
    <script src="{{ asset('js/dark-mode.js') }}" defer></script>
    @livewireStyles
</head>

<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">



    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            class="w-64  bg-white dark:bg-gray-800 border-r dark:border-gray-700 fixed h-full shadow-lg transition-colors duration-200 overflow-scroll">
            <div class="flex items-center justify-center p-4  ">
                <img src="/logo.png" alt="Logo" class=" w-[50%] rounded-full">
            </div>

            <nav class="p-4 space-y-2">
                @include('components.sidebar-menu')
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="ml-64 flex-1">
            <!-- Top Navigation -->
            <header dir="rtl"
                class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 h-16 fixed z-20 transition-colors duration-200 rounded-3xl shadow-xl  w-[77%] mx-12 ">
                @include('components.header')
            </header>

            <!-- Main Content Area -->
            <main class="pt-24 p-6 mx-6">
                <!-- Flash Messages -->
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    @if (session('success'))
                        <div class= " mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/20 dark:text-green-300 transition-colors duration-200"
                            role="alert">
                            <div class="flex items-center">
                                <div class="py-1">
                                    <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/20 dark:text-red-300 transition-colors duration-200"
                                role="alert">
                                <div class="flex items-center">
                                    <div class="py-1">
                                        <svg class="w-6 h-6 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>{{ $error }}</div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        let lastClicked = {};

        function toggleRadio(radio) {
            const name = radio.name;
            if (lastClicked[name] == radio) {
                radio.checked = false;
                lastClicked[name] = null;
            } else {
                lastClicked[name] = radio;
            }
        }
        //the pop up div to write an order
        document.addEventListener("DOMContentLoaded", function() {





            let button = document.getElementById("toggleButton");
            let hiddenDiv = document.getElementById("hidden-div");
            let closeButton = document.getElementById("closeButton");

            button.addEventListener("click", function(event) {
                event.preventDefault();
                hiddenDiv.style.display = "block";
                button.classList.add("active");
            });

            closeButton.addEventListener("click", function(event) {
                event.preventDefault();
                hiddenDiv.style.display = "none";
                button.classList.remove("active");
            });
        });
    </script>
    {{--
    @push('scripts')
        <script src="{{ asset('js/echo.js') }}"></script>
    @endpush --}}
    @stack('scripts')
    @livewireScripts
</body>

</html>
