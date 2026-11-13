<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>404 — Topic Not Found | UNILAK Forum</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 flex items-center justify-center p-4 text-center font-sans">
    <div class="max-w-md">
        <div class="w-16 h-16 rounded-2xl bg-primary-50 text-primary-800 flex items-center justify-center text-3xl font-black mx-auto mb-4">
            ?
        </div>
        <h1 class="text-4xl font-extrabold text-primary-800 tracking-tight">404</h1>
        <h2 class="text-lg font-bold text-gray-900 mt-2">Page or Discussion Not Found</h2>
        <p class="text-xs text-gray-500 mt-2 leading-relaxed">
            The question, category, or academic document you are looking for might have been removed, closed, or moved to another faculty board.
        </p>
        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('welcome') }}"
               class="px-4 py-2.5 rounded-lg bg-primary-800 text-white font-semibold text-xs hover:bg-primary-700 transition">
                Return to Campus Home
            </a>
            <a href="{{ route('posts.index') }}"
               class="px-4 py-2.5 rounded-lg bg-accent-500 text-white font-semibold text-xs hover:bg-accent-600 transition">
                Browse Discussions
            </a>
        </div>
    </div>
</body>
</html>

