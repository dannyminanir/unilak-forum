<x-layouts.app>
    <x-slot name="title">Categories</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Academic Categories</h1>
                <p class="text-sm text-gray-500 mt-1">Browse academic discussions by faculties, courses, and student interests</p>
            </div>
            <a href="{{ route('posts.create') }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Discussion
                </x-button>
            </a>
        </div>
    </x-slot>

    {{-- Category Group Template --}}
    @php
    $sections = [
        ['title' => 'Faculties & Departments', 'badge' => 'department', 'categories' => $departmentCategories, 'desc' => 'Official faculty boards and academic department forums'],
        ['title' => 'Courses & Modules', 'badge' => 'course', 'categories' => $courseCategories, 'desc' => 'Subject-specific study groups, coursework, and revision'],
        ['title' => 'Campus Life & Student Interests', 'badge' => 'interest', 'categories' => $interestCategories, 'desc' => 'Internships, student union, campus fellowship, and career opportunities'],
    ];
    @endphp

    <div class="space-y-10">
        @foreach($sections as $sec)
            <div>
                <div class="border-b border-gray-200 pb-3 mb-5">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        {{ $sec['title'] }}
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            {{ $sec['categories']->count() }}
                        </span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $sec['desc'] }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($sec['categories'] as $cat)
                        <a href="{{ route('categories.show', $cat) }}" class="group block">
                            <x-card class="h-full hover:shadow-md hover:border-primary-300 transition duration-150 border border-transparent">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                                         style="background-color: {{ $cat->color }}15;">
                                        {{ $cat->icon ?? '📁' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-gray-900 group-hover:text-primary-800 transition truncate">
                                            {{ $cat->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                            {{ $cat->description ?? 'Join student discussions in this academic category.' }}
                                        </p>
                                        <div class="flex items-center gap-4 mt-3 text-xs text-gray-400">
                                            <span class="flex items-center gap-1 font-medium text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                                </svg>
                                                {{ $cat->posts_count }} topics
                                            </span>
                                            <span class="flex items-center gap-1 font-medium text-gray-600">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                {{ $cat->resources_count }} materials
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @empty
                        <p class="text-sm text-gray-400 italic col-span-2">No categories in this group yet.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.app>

