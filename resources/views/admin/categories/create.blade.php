<x-layouts.app>
    <x-slot name="title">Add Category — Admin</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Add Academic Category</h1>
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-5">
                @csrf

                <x-input name="name" label="Category Name" placeholder="e.g. Artificial Intelligence & Machine Learning" :required="true" autofocus />

                <div class="flex flex-col gap-1">
                    <label for="type" class="text-sm font-medium text-gray-700">Category Type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required class="rounded-lg border border-gray-300 p-2.5 text-sm">
                        <option value="course" {{ old('type') === 'course' ? 'selected' : '' }}>Course / Subject</option>
                        <option value="department" {{ old('type') === 'department' ? 'selected' : '' }}>Faculty / Department</option>
                        <option value="interest" {{ old('type') === 'interest' ? 'selected' : '' }}>Student Life & Interest</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="description" class="text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="rounded-lg border border-gray-300 p-2.5 text-sm">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input name="icon" label="Emoji / Icon" placeholder="e.g. 🤖 or 📚" :value="old('icon', '📚')" />
                    <x-input name="color" label="Hex Color Badge" placeholder="#1E3A5F" :value="old('color', '#1E3A5F')" :required="true" />
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.categories.index') }}" class="text-xs text-gray-500 px-3 py-2">Cancel</a>
                    <x-button type="submit" variant="secondary">Create Category</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

