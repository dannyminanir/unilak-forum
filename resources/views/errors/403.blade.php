<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>403 — Access Denied | UNILAK Forum</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 flex items-center justify-center p-4 text-center font-sans">
    <div class="max-w-md">
        <div class="w-16 h-16 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-3xl font-black mx-auto mb-4">
            🛡️
        </div>
        <h1 class="text-4xl font-extrabold text-red-600 tracking-tight">403</h1>
        <h2 class="text-lg font-bold text-gray-900 mt-2">Access Restricted</h2>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
            You do not have administrative or moderation permissions to access this campus area or perform this action.
        </p>
        <div class="mt-6">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2.5 rounded-lg bg-primary-800 text-white font-semibold text-xs hover:bg-primary-700 transition">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>

