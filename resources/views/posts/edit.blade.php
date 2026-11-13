<x-layouts.app>
    <x-slot name="title">Edit Discussion</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Edit Discussion</h1>
        <p class="text-sm text-gray-500 mt-1">Update your question or topic details</p>
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('posts.update', $post) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Category --}}
                <div class="flex flex-col gap-1">
                    <label for="category_id" class="text-sm font-medium text-gray-700">
                        Category <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : '' }}">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (string)old('category_id', $post->category_id) === (string)$category->id ? 'selected' : '' }}>
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
                    :value="$post->title"
                    :required="true"
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
                        class="block w-full rounded-lg border border-gray-300 bg-white p-3.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 {{ $errors->has('content') ? 'border-red-400 bg-red-50' : '' }}"
                    >{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <x-input
                    name="tags"
                    label="Tags (Comma separated)"
                    :value="$existingTags"
                />

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <x-button type="submit" variant="primary">
                        Save Changes
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

