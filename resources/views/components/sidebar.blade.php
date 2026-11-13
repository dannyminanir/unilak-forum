{{-- Left sidebar for authenticated layout --}}
<aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-100 min-h-screen pt-6 pb-10 px-3 gap-1">

    {{-- Section label --}}
    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Navigation</p>

    @php
    $navItems = [
        ['route' => 'dashboard',            'label' => 'Dashboard',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
        ['route' => 'posts.index',          'label' => 'Discussions',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>'],
        ['route' => 'categories.index',     'label' => 'Categories',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'],
        ['route' => 'resources.index',      'label' => 'Resources',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>'],
        ['route' => 'announcements.index',  'label' => 'Announcements', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>'],
        ['route' => 'posts.saved',          'label' => 'Bookmarks',     'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>'],
        ['route' => 'profile.show',         'label' => 'My Profile',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
    ];

    $sidebarCategories = \App\Models\Category::ordered()->get();

    if (auth()->user()?->role === 'student' && auth()->user()->department) {
        $sidebarCategories = $sidebarCategories->filter(function ($category) {
            return $category->type !== 'department' || auth()->user()->canAccessDepartment($category->name);
        });
    }

    $sidebarCategories = $sidebarCategories->take(6);
    @endphp

    @foreach($navItems as $item)
        @php
            $isActive = $item['route'] !== '#' && request()->routeIs($item['route']);
            $linkClass = 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition duration-150 '
                . ($isActive
                    ? 'bg-primary-50 text-primary-800 font-semibold'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-primary-800');
        @endphp
        <a href="{{ $item['route'] !== '#' ? route($item['route']) : '#' }}" class="{{ $linkClass }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $item['icon'] !!}
            </svg>
            {{ $item['label'] }}
            @if($isActive)
                <span class="ml-auto w-1.5 h-1.5 rounded-full bg-accent-500"></span>
            @endif
        </a>
    @endforeach

    {{-- Top Categories Quicklist --}}
    @if($sidebarCategories->isNotEmpty())
        <div class="mt-6 pt-4 border-t border-gray-100">
            <div class="flex items-center justify-between px-3 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Categories</p>
                <a href="{{ route('categories.index') }}" class="text-[10px] text-primary-800 hover:underline">All &rarr;</a>
            </div>
            <div class="space-y-0.5">
                @foreach($sidebarCategories as $scat)
                    <a href="{{ route('categories.show', $scat) }}"
                       class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs text-gray-600 hover:bg-gray-50 hover:text-primary-800 transition truncate">
                        <span class="text-sm">{{ $scat->icon ?? '📁' }}</span>
                        <span class="truncate">{{ $scat->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Admin section --}}
    @if(auth()->user()->isAdmin())
        <div class="mt-auto pt-4 border-t border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2">Administration</p>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-primary-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Admin Suite
            </a>
        </div>
    @endif
</aside>
