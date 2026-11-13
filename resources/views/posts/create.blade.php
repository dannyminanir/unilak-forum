<x-layouts.app>
    <x-slot name="title">Start a Discussion</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Start a Discussion</h1>
        <p class="text-sm text-gray-500 mt-1">Ask an academic question or share course insights with UNILAK students</p>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
                @csrf

                {{-- Category --}}
                <div class="flex flex-col gap-1">
                    <label for="category_id" class="text-sm font-medium text-gray-700">
                        Category <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : '' }}">
                        <option value="" disabled {{ old('category_id', $defaultCategoryId) ? '' : 'selected' }}>
                            Select the appropriate faculty, course, or interest category…
                        </option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)old('category_id', $defaultCategoryId) === (string)$category->id ? 'selected' : '' }}>
                                [{{ ucfirst($category->type) }}] {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <x-input
                    name="title"
                    label="Topic Title"
                    placeholder="e.g. How do we configure MySQL relationships in Laravel 11?"
                    :required="true"
                    autofocus
                />

                {{-- Content / Body --}}
                <div class="flex flex-col gap-1">
                    <label for="content" class="text-sm font-medium text-gray-700">
                        Discussion Content <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="8"
                        required
                        placeholder="Provide details about your question, assignment context, error logs, or discussion topic..."
                        class="block w-full rounded-lg border border-gray-300 bg-white p-3.5 text-sm text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 {{ $errors->has('content') ? 'border-red-400 bg-red-50' : '' }}"
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-0.5">Be specific and descriptive so fellow students and lecturers can assist.</p>
                </div>

                {{-- Tags --}}
                <x-input
                    name="tags"
                    label="Tags (Optional)"
                    placeholder="e.g. Laravel, Database, Exam Prep (separated by commas)"
                />

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <x-button type="submit" variant="secondary">
                        Publish Discussion
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

