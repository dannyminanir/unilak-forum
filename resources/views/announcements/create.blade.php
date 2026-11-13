<x-layouts.app>
    <x-slot name="title">Post Announcement</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Create Official Announcement</h1>
        <p class="text-sm text-gray-500 mt-1">Broadcast important campus or departmental news to UNILAK students</p>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('announcements.store') }}" class="space-y-6">
                @csrf

                <x-input
                    name="title"
                    label="Announcement Title"
                    placeholder="e.g. End of Semester Examination Timetable & Guidelines"
                    :required="true"
                    autofocus
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
                            <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>
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
                        placeholder="Write the full announcement text, instructions, and dates..."
                        class="block w-full rounded-lg border border-gray-300 bg-white p-3.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800"
                    >{{ old('content') }}</textarea>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-accent-600 focus:ring-accent-500">
                    <label for="is_pinned" class="text-sm text-gray-700 font-medium">Pin to the top of announcements and student home page</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('announcements.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-button type="submit" variant="secondary">Publish Announcement</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

