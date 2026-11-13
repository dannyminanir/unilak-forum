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

    {{-- Vite (Tailwind CSS + JS) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-50 antialiased">

    {{-- Centered auth card --}}
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

        {{-- Branding --}}
        <a href="{{ route('login') }}" class="mb-8 flex flex-col items-center gap-2">
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

