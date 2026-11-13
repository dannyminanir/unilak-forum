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

    {{-- Centered auth card --}}
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

        {{-- Branding --}}
        <a href="{{ route('welcome') }}" class="mb-8 flex flex-col items-center gap-2">
            <span class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary-800 text-white font-black text-2xl shadow-md">
                U
            </span>
            <span class="text-xl font-bold text-primary-800 tracking-tight">UNILAK Forum</span>
            <span class="text-xs text-gray-500">University of Lay Adventists of Kigali</span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-md">
            <x-card>
                {{ $slot }}
            </x-card>
        </div>

        {{-- Footer --}}
        <p class="mt-8 text-xs text-gray-400">
            &copy; {{ date('Y') }} UNILAK &middot; Student Academic Forum
        </p>
    </div>

</body>
</html>
