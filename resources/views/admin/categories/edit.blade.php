<x-layouts.app>
    <x-slot name="title">Edit Category — Admin</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Edit Category</h1>
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <x-input name="name" label="Category Name" :value="$category->name" :required="true" />

                <div class="flex flex-col gap-1">
                    <label for="type" class="text-sm font-medium text-gray-700">Category Type</label>
                    <select id="type" name="type" required class="rounded-lg border border-gray-300 p-2.5 text-sm">
                        <option value="course" {{ old('type', $category->type) === 'course' ? 'selected' : '' }}>Course / Subject</option>
                        <option value="department" {{ old('type', $category->type) === 'department' ? 'selected' : '' }}>Faculty / Department</option>
                        <option value="interest" {{ old('type', $category->type) === 'interest' ? 'selected' : '' }}>Student Life & Interest</option>
                    </select>
                </div>

                <div class="flex flex-col gap-1">
                    <label for="description" class="text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="3" class="rounded-lg border border-gray-300 p-2.5 text-sm">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input name="icon" label="Emoji / Icon" :value="$category->icon" />
                    <x-input name="color" label="Hex Color Badge" :value="$category->color" :required="true" />
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.categories.index') }}" class="text-xs text-gray-500 px-3 py-2">Cancel</a>
                    <x-button type="submit" variant="primary">Update Category</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

