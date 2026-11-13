<x-layouts.app>
    <x-slot name="title">Academic Resources</x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Academic Resources & Study Materials</h1>
                <p class="text-sm text-gray-500 mt-1">Download lecture notes, past exam papers, assignments, and revision materials</p>
            </div>
            <a href="{{ route('resources.create') }}">
                <x-button variant="secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload Material
                </x-button>
            </a>
        </div>
    </x-slot>

    {{-- Search & Filtering Bar --}}
    <div class="space-y-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 border-b border-gray-200 pb-4">
            {{-- Search Input --}}
            <form method="GET" action="{{ route('resources.index') }}" class="relative w-full md:w-80">
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                <input type="hidden" name="type" value="{{ $fileType }}">
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Search lecture notes, past papers..."
                       class="w-full text-xs rounded-lg border-gray-300 pl-9 pr-3 py-2 shadow-sm focus:border-primary-800 focus:ring-primary-800" />
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            {{-- File Type and Category Filters --}}
            <div class="flex items-center gap-2 flex-wrap">
                <div class="flex items-center gap-1 text-xs">
                    <a href="{{ route('resources.index', array_filter(['category' => $selectedCategory, 'q' => $search])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ empty($fileType) ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        All
                    </a>
                    <a href="{{ route('resources.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'type' => 'pdf'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $fileType === 'pdf' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        PDF
                    </a>
                    <a href="{{ route('resources.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'type' => 'docx'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ in_array($fileType, ['doc', 'docx']) ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Word
                    </a>
                    <a href="{{ route('resources.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'type' => 'pptx'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ in_array($fileType, ['ppt', 'pptx']) ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        Slides
                    </a>
                    <a href="{{ route('resources.index', array_filter(['category' => $selectedCategory, 'q' => $search, 'type' => 'zip'])) }}"
                       class="px-2.5 py-1.5 rounded-lg font-medium transition {{ $fileType === 'zip' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                        ZIP
                    </a>
                </div>

                {{-- Category Selector --}}
                <form method="GET" action="{{ route('resources.index') }}">
                    <input type="hidden" name="type" value="{{ $fileType }}">
                    <input type="hidden" name="q" value="{{ $search }}">
                    <select name="category" onchange="this.form.submit()"
                            class="text-xs rounded-lg border-gray-300 bg-white py-1.5 pl-3 pr-8 text-gray-700 shadow-sm focus:border-primary-800 focus:ring-primary-800">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $selectedCategory === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- Academic Resources Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($resources as $item)
            @php
            $typeIcons = [
                'pdf'  => ['color' => 'bg-red-50 text-red-600 border-red-200', 'label' => 'PDF'],
                'doc'  => ['color' => 'bg-blue-50 text-blue-600 border-blue-200', 'label' => 'DOC'],
                'docx' => ['color' => 'bg-blue-50 text-blue-600 border-blue-200', 'label' => 'DOCX'],
                'ppt'  => ['color' => 'bg-amber-50 text-amber-600 border-amber-200', 'label' => 'PPT'],
                'pptx' => ['color' => 'bg-amber-50 text-amber-600 border-amber-200', 'label' => 'PPTX'],
                'xls'  => ['color' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 'label' => 'XLS'],
                'xlsx' => ['color' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 'label' => 'XLSX'],
                'zip'  => ['color' => 'bg-purple-50 text-purple-600 border-purple-200', 'label' => 'ZIP'],
            ];
            $fileMeta = $typeIcons[$item->file_type] ?? ['color' => 'bg-gray-50 text-gray-600 border-gray-200', 'label' => strtoupper($item->file_type)];
            @endphp

            <x-card class="flex flex-col justify-between hover:border-primary-300 transition duration-150 border border-transparent">
                <div>
                    {{-- Top category & file badge --}}
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <x-tag :color="$item->category->color" size="sm">
                            {{ $item->category->name }}
                        </x-tag>

                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded border {{ $fileMeta['color'] }}">
                            {{ $fileMeta['label'] }}
                        </span>
                    </div>

                    {{-- Resource Title --}}
                    <a href="{{ route('resources.show', $item) }}" class="block">
                        <h2 class="text-sm font-bold text-gray-900 hover:text-primary-800 transition line-clamp-2">
                            {{ $item->title }}
                        </h2>
                    </a>

                    @if($item->description)
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $item->description }}
                        </p>
                    @endif
                </div>

                {{-- Footer info & Download button --}}
                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                    <div class="text-gray-400">
                        <p class="truncate max-w-[120px] font-medium text-gray-600">{{ $item->user->name }}</p>
                        <p class="text-[10px]">{{ $item->file_size_human }} &middot; {{ $item->downloads }} downloads</p>
                    </div>

                    <a href="{{ route('resources.download', $item) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary-50 text-primary-800 hover:bg-primary-800 hover:text-white font-semibold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Get
                    </a>
                </div>
            </x-card>
        @empty
            <div class="col-span-full">
                <x-card class="text-center py-12">
                    <div class="text-3xl mb-2">📚</div>
                    <h3 class="font-semibold text-gray-900">No study materials uploaded yet</h3>
                    <p class="text-xs text-gray-500 mt-1">Be the first student to upload lecture notes or revision papers!</p>
                    <div class="mt-4">
                        <a href="{{ route('resources.create') }}">
                            <x-button variant="secondary">Upload Material</x-button>
                        </a>
                    </div>
                </x-card>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $resources->links() }}
    </div>
</x-layouts.app>

