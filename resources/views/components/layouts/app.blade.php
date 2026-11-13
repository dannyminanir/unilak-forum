<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ isset($title) ? $title . ' — UNILAK Forum' : 'UNILAK Forum' }}</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    {{-- Tailwind CSS CDN with UNILAK Brand Tokens --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e3a5f',
                            900: '#172554',
                            950: '#0f172a',
                        },
                        accent: {
                            50:  '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                            950: '#431407',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full bg-gray-50 antialiased font-sans">

    {{-- Top navbar --}}
    <x-navbar />

    {{-- Main layout: sidebar + content --}}
    <div class="flex min-h-[calc(100vh-4rem)]">

        {{-- Sidebar (desktop only) --}}
        <x-sidebar />

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Optional page header slot --}}
            @isset($header)
                <div class="bg-white border-b border-gray-100 px-6 py-4">
                    <div class="max-w-5xl mx-auto">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
                {{-- Flash Success / Status Messages --}}
                @if(session('status') || session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                         class="mb-6 flex items-center justify-between bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('status') ?? session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-600 hover:text-green-800 p-1">
                            &times;
                        </button>
                    </div>
                @endif

                {{-- Flash Error Messages --}}
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition
                         class="mb-6 flex items-center justify-between bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800 p-1">
                            &times;
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

</body>
</html>
