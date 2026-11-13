<x-layouts.app>
    <x-slot name="title">{{ $user->name }} — Profile</x-slot>

    {{-- Profile Banner Card --}}
    <x-card class="mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                {{-- Avatar --}}
                <div class="relative">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}"
                             class="w-20 h-20 rounded-2xl object-cover border-2 border-primary-800 shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-2xl bg-primary-800 text-white flex items-center justify-center font-bold text-2xl uppercase shadow-sm">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-accent-100 text-accent-700' }}">
                            {{ $user->role }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $user->department ?? 'Department Unspecified' }}
                        @if($user->year_of_study)
                            &middot; Year {{ $user->year_of_study }}
                        @endif
                        @if($user->student_id)
                            &middot; <span class="font-mono text-gray-600">{{ $user->student_id }}</span>
                        @endif
                    </p>

                    @if($user->bio)
                        <p class="text-sm text-gray-700 mt-2 max-w-xl leading-relaxed">
                            {{ $user->bio }}
                        </p>
                    @endif
                </div>
            </div>

            @if($isOwn)
                <div x-data="{ openModal: false }">
                    <x-button @click="openModal = true" variant="primary" class="!text-xs">
                        Edit Profile
                    </x-button>

                    {{-- Edit Profile Modal --}}
                    <div x-show="openModal" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                        <div @click.outside="openModal = false"
                             class="bg-white rounded-2xl shadow-xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Your Profile</h3>

                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                @method('PATCH')

                                <x-input
                                    name="name"
                                    label="Full Name"
                                    :value="$user->name"
                                    :required="true"
                                />

                                <div class="flex flex-col gap-1">
                                    <label for="department" class="text-sm font-medium text-gray-700">Department / Faculty</label>
                                    <select id="department" name="department"
                                            class="rounded-lg border border-gray-300 p-2.5 text-sm">
                                        <option value="">Select Faculty…</option>
                                        @foreach([
                                            'Faculty of Computing & Information Sciences',
                                            'Faculty of Economic Sciences & Management',
                                            'Faculty of Law',
                                            'Faculty of Environmental Studies',
                                            'Faculty of Education',
                                        ] as $dept)
                                            <option value="{{ $dept }}" {{ old('department', $user->department) === $dept ? 'selected' : '' }}>
                                                {{ $dept }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="year_of_study" class="text-sm font-medium text-gray-700">Year of Study</label>
                                    <select id="year_of_study" name="year_of_study" class="rounded-lg border border-gray-300 p-2.5 text-sm">
                                        <option value="">Select Year…</option>
                                        @foreach([1, 2, 3, 4, 5] as $yr)
                                            <option value="{{ $yr }}" {{ old('year_of_study', $user->year_of_study) == $yr ? 'selected' : '' }}>
                                                Year {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="bio" class="text-sm font-medium text-gray-700">Bio</label>
                                    <textarea id="bio" name="bio" rows="3" placeholder="Tell fellow students about your academic interests..."
                                              class="rounded-lg border border-gray-300 p-2.5 text-sm">{{ old('bio', $user->bio) }}</textarea>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <label for="photo" class="text-sm font-medium text-gray-700">Profile Photo</label>
                                    <input type="file" id="photo" name="photo" accept="image/*"
                                           class="text-xs text-gray-700 file:py-2 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 cursor-pointer" />
                                </div>

                                <div class="flex items-center justify-end gap-2 pt-4 border-t border-gray-100">
                                    <button type="button" @click="openModal = false" class="text-xs text-gray-500 hover:text-gray-700 px-3 py-2">
                                        Cancel
                                    </button>
                                    <x-button type="submit" variant="primary">
                                        Save Changes
                                    </x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-card>

    {{-- Tabs (My Posts, My Resources, Saved Posts) --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3 mb-6 text-sm">
        <a href="{{ route('profile.show', array_filter(['user' => $isOwn ? null : $user->id, 'tab' => 'posts'])) }}"
           class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'posts' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $isOwn ? 'My Discussions' : 'Discussions' }} ({{ $myPosts->total() }})
        </a>
        <a href="{{ route('profile.show', array_filter(['user' => $isOwn ? null : $user->id, 'tab' => 'resources'])) }}"
           class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'resources' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            {{ $isOwn ? 'My Resources' : 'Uploaded Resources' }} ({{ $myResources->total() }})
        </a>
        @if($isOwn)
            <a href="{{ route('profile.show', ['tab' => 'saved']) }}"
               class="px-3 py-1.5 rounded-lg font-semibold transition {{ $tab === 'saved' ? 'bg-primary-800 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Saved Discussions ({{ $savedPosts->total() }})
            </a>
        @endif
    </div>

    {{-- Tab Contents --}}
    @if($tab === 'posts')
        <div class="space-y-3">
            @forelse($myPosts as $post)
                <x-card class="hover:border-primary-200 border border-transparent">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <x-tag :color="$post->category->color" size="sm">{{ $post->category->name }}</x-tag>
                                <span class="text-xs text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="font-bold text-gray-900 hover:text-primary-800 text-sm block truncate">
                                {{ $post->title }}
                            </a>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-primary-50 text-primary-800 flex-shrink-0">
                            {{ $post->replies_count }} replies
                        </span>
                    </div>
                </x-card>
            @empty
                <x-card class="text-center py-8">
                    <p class="text-xs text-gray-500">No discussions posted yet.</p>
                </x-card>
            @endforelse
            {{ $myPosts->links() }}
        </div>
    @elseif($tab === 'resources')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($myResources as $res)
                <x-card class="hover:border-primary-200 border border-transparent">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">
                                {{ $res->file_type }}
                            </span>
                            <a href="{{ route('resources.show', $res) }}" class="font-bold text-sm text-gray-900 hover:text-primary-800 block truncate mt-1">
                                {{ $res->title }}
                            </a>
                            <p class="text-xs text-gray-400 mt-1">{{ $res->downloads }} downloads &middot; {{ $res->file_size_human }}</p>
                        </div>
                        <a href="{{ route('resources.download', $res) }}" class="p-2 text-primary-800 hover:bg-primary-50 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </a>
                    </div>
                </x-card>
            @empty
                <div class="col-span-full">
                    <x-card class="text-center py-8">
                        <p class="text-xs text-gray-500">No study materials uploaded yet.</p>
                    </x-card>
                </div>
            @endforelse
            <div class="col-span-full">{{ $myResources->links() }}</div>
        </div>
    @elseif($tab === 'saved' && $isOwn)
        <div class="space-y-3">
            @forelse($savedPosts as $spost)
                <x-card class="hover:border-primary-200 border border-transparent">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <x-tag :color="$spost->category->color" size="sm">{{ $spost->category->name }}</x-tag>
                                <span class="text-xs text-gray-400">by {{ $spost->user->name }} &middot; {{ $spost->created_at->format('M d, Y') }}</span>
                            </div>
                            <a href="{{ route('posts.show', $spost) }}" class="font-bold text-gray-900 hover:text-primary-800 text-sm block truncate">
                                {{ $spost->title }}
                            </a>
                        </div>
                        <form method="POST" action="{{ route('posts.save', $spost) }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-red-600 hover:underline">
                                Remove
                            </button>
                        </form>
                    </div>
                </x-card>
            @empty
                <x-card class="text-center py-8">
                    <p class="text-xs text-gray-500">No bookmarked discussions yet.</p>
                </x-card>
            @endforelse
            {{ $savedPosts->links() }}
        </div>
    @endif
</x-layouts.app>

