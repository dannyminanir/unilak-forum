{{-- Navbar component --}}
<nav class="bg-primary-800 text-white shadow-md sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
               class="flex items-center gap-2 font-bold text-lg tracking-tight">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-accent-500 text-white font-black text-sm">U</span>
                <span class="hidden sm:inline">UNILAK Forum</span>
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('search.index') }}" class="text-sm font-medium hover:text-accent-300 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </a>

                @auth
                    {{-- Notifications Bell --}}
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    <a href="{{ route('notifications.index') }}" class="relative p-1.5 rounded-lg text-primary-200 hover:text-white hover:bg-primary-700 transition" title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/3 -translate-y-1/3 bg-accent-500 rounded-full">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ dropOpen: false }">
                        <button @click="dropOpen = !dropOpen"
                                class="flex items-center gap-2 text-sm font-medium hover:text-accent-300 transition focus:outline-none">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-accent-500 text-white font-bold uppercase text-xs">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform" :class="dropOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="dropOpen" x-cloak @click.outside="dropOpen = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg ring-1 ring-gray-100 py-1 text-gray-700 text-sm">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 hover:bg-gray-50">My Profile</a>
                            <a href="{{ route('posts.saved') }}" class="block px-4 py-2 hover:bg-gray-50">Saved Discussions</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-50 text-primary-800 font-semibold">Admin Suite</a>
                                <a href="{{ route('announcements.create') }}" class="block px-4 py-2 hover:bg-gray-50 text-accent-600 font-medium">+ New Announcement</a>
                            @endif
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 transition">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-medium hover:text-accent-300 transition">Sign In</a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold bg-accent-500 hover:bg-accent-600 text-white px-4 py-2 rounded-lg transition">
                        Register
                    </a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open = !open"
                    class="md:hidden p-2 rounded-lg hover:bg-primary-700 transition focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open"  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak class="md:hidden border-t border-primary-700 pb-3">
        @auth
            <div class="px-4 py-3 border-b border-primary-700">
                <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                <p class="text-xs text-primary-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm hover:bg-primary-700 transition">Dashboard</a>
            <a href="#" class="block px-4 py-2.5 text-sm hover:bg-primary-700 transition">My Profile</a>
            <div class="border-t border-primary-700 mt-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-300 hover:bg-primary-700 transition">
                        Sign Out
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}"   class="block px-4 py-2.5 text-sm hover:bg-primary-700 transition">Sign In</a>
            <a href="{{ route('register') }}" class="block px-4 py-2.5 text-sm hover:bg-primary-700 transition">Register</a>
        @endauth
    </div>
</nav>

