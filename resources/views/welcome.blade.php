<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>UNILAK Student Academic Forum — University of Lay Adventists of Kigali</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">

    {{-- Top Navigation --}}
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-2.5">
                <span class="w-9 h-9 rounded-xl bg-primary-800 text-white font-black flex items-center justify-center text-sm shadow-sm">U</span>
                <div>
                    <span class="font-bold text-base text-primary-800 tracking-tight">UNILAK Forum</span>
                    <span class="hidden sm:inline text-xs text-gray-400 ml-1">&middot; Kigali &bull; Nyanza &bull; Rwamagana</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-gray-700 hover:text-primary-800 px-3 py-2 transition">
                    Sign In
                </a>
                <a href="{{ route('register') }}" class="text-xs sm:text-sm font-semibold bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-xl transition shadow-sm">
                    Join Forum
                </a>
            </div>
        </div>
    </header>

    {{-- Hero Section --}}
    <section
        class="relative overflow-hidden bg-primary-750 bg-cover bg-center py-16 text-white sm:py-24"
        style="background-image: linear-gradient(90deg, rgba(10, 27, 45, 0.94) 0%, rgba(10, 30, 40, 0.84) 58%, rgba(6, 27, 45, 0.62) 90%), url('{{ asset('photo/images.jpg') }}');"
    >
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[minmax(0,1.2fr)_minmax(280px,0.8fr)]">
                <div class="max-w-3xl">
                    <span class="inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.16em] text-white/90 backdrop-blur-sm">
                        The UNILAK student community
                    </span>

                    <h1 class="mt-7 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                        Learn better,
                        <span class="text-accent-300">together.</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-relaxed text-white/80 sm:text-lg">
                        A shared space for UNILAK students to exchange ideas, find trusted study materials, and stay connected to what matters on campus.
                    </p>

                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-accent-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-black/20 transition hover:bg-accent-600">
                            Join the community
                        </a>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-white/30 bg-white/10 px-6 py-3.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                            Sign in to your account
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/20 bg-white/10 p-6 shadow-2xl shadow-black/20 backdrop-blur-md sm:p-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-accent-300">Made for campus life</p>
                    <h2 class="mt-3 text-2xl font-bold tracking-tight">Everything you need to move forward.</h2>
                    <ul class="mt-6 space-y-4 text-sm text-white/85">
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-accent-400/20 text-accent-300" aria-hidden="true">✓</span>
                            Get thoughtful answers from fellow students
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-accent-400/20 text-accent-300" aria-hidden="true">✓</span>
                            Find course resources in one organized place
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-accent-400/20 text-accent-300" aria-hidden="true">✓</span>
                            Keep up with official campus announcements
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-14 grid grid-cols-2 gap-3 border-t border-white/20 pt-6 sm:grid-cols-4 sm:gap-6">
                <div>
                    <p class="text-2xl font-black text-white sm:text-3xl">{{ $stats['students'] }}</p>
                    <p class="mt-1 text-xs text-white/70 sm:text-sm">Registered students</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-white sm:text-3xl">{{ $stats['discussions'] }}</p>
                    <p class="mt-1 text-xs text-white/70 sm:text-sm">Academic topics</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-white sm:text-3xl">{{ $stats['resources'] }}</p>
                    <p class="mt-1 text-xs text-white/70 sm:text-sm">Study materials</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-white sm:text-3xl">{{ $stats['categories'] }}</p>
                    <p class="mt-1 text-xs text-white/70 sm:text-sm">Faculties &amp; boards</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Official Announcements Section --}}
    @if($latestAnnouncements->isNotEmpty())
        <section class="max-w-5xl mx-auto px-4 -mt-8 relative z-10 mb-12">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <div class="flex items-center gap-2 mb-4 text-xs font-bold uppercase tracking-wider text-accent-600">
                    <span>📢</span> Official University Communications
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($latestAnnouncements as $ann)
                        <div class="border-l-2 border-primary-800 pl-3 py-1">
                            <span class="text-[10px] font-bold text-gray-400">{{ $ann->department ?? 'All Faculties' }}</span>
                            <h3 class="font-bold text-sm text-gray-900 line-clamp-1 mt-0.5">{{ $ann->title }}</h3>
                            <p class="text-xs text-gray-600 line-clamp-2 mt-1">{{ $ann->content }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Academic Faculties --}}
    <section class="max-w-5xl mx-auto px-4 py-10">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Academic Faculties & Departments</h2>
            <p class="text-xs text-gray-500 mt-1">Study boards and peer discussions tailored to your UNILAK degree</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($featuredCategories as $cat)
                <div class="bg-white rounded-xl p-5 border border-gray-200 text-center hover:border-primary-400 transition">
                    <span class="text-3xl block mb-2">{{ $cat->icon ?? '🏛️' }}</span>
                    <h3 class="font-bold text-sm text-gray-900 line-clamp-2">{{ $cat->name }}</h3>
                    <p class="text-xs text-gray-400 mt-2">{{ $cat->posts_count }} discussions</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Why UNILAK Forum --}}
    <section class="bg-white border-y border-gray-200 py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl font-bold text-gray-900">Everything UNILAK Students Need in One Place</h2>
                <p class="text-xs text-gray-500 mt-1">Built specifically for the academic workflows of University of Lay Adventists students</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-primary-800 flex items-center justify-center text-xl mb-4">
                        💡
                    </div>
                    <h3 class="font-bold text-base text-gray-900">Peer Coursework Help</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        Stuck on programming assignments, law case studies, or economics balance sheets? Ask peers and get validated "Best Answers".
                    </p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-accent-600 flex items-center justify-center text-xl mb-4">
                        📚
                    </div>
                    <h3 class="font-bold text-base text-gray-900">Curated Study Materials</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        Share and download lecture slides, revision summaries, and past exam papers sorted by faculty and course modules.
                    </p>
                </div>

                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center text-xl mb-4">
                        🏆
                    </div>
                    <h3 class="font-bold text-base text-gray-900">Student Reputation System</h3>
                    <p class="text-xs text-gray-600 mt-2 leading-relaxed">
                        Earn reputation karma and recognition badges by posting helpful replies, solving tricky questions, and sharing resources.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12 px-4 text-center text-xs text-gray-400">
        <div class="max-w-5xl mx-auto">
            <p class="font-bold text-white text-sm">University of Lay Adventists of Kigali (UNILAK)</p>
            <p class="mt-1">Student Academic Forum &middot; Kigali &bull; Nyanza &bull; Rwamagana Campuses</p>
            <p class="mt-4 text-gray-500">&copy; {{ date('Y') }} UNILAK Forum. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
