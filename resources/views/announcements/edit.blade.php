<x-layouts.app>
    <x-slot name="title">Edit Announcement</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Edit Announcement</h1>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('announcements.update', $announcement) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-input
                    name="title"
                    label="Announcement Title"
                    :value="$announcement->title"
                    :required="true"
                />

                <div class="flex flex-col gap-1">
                    <label for="department" class="text-sm font-medium text-gray-700">Target Department</label>
                    <select id="department" name="department"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800">
                        <option value="">University-Wide (All Students)</option>
                        @foreach([
                            'Faculty of Computing & Information Sciences',
                            'Faculty of Economic Sciences & Management',
                            'Faculty of Law',
                            'Faculty of Environmental Studies',
                            'Faculty of Education',
                        ] as $dept)
                            <option value="{{ $dept }}" {{ old('department', $announcement->department) === $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="content" class="text-sm font-medium text-gray-700">
                        Announcement Body <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="8"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white p-3.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800"
                    >{{ old('content', $announcement->content) }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                    <label for="is_pinned" class="text-sm text-gray-700 font-medium">Pin to top</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-button type="submit" variant="primary">Update Announcement</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

