<x-layouts.app>
    <x-slot name="title">Upload Academic Resource</x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Upload Academic Material</h1>
        <p class="text-sm text-gray-500 mt-1">Share lecture notes, slides, past papers, or assignments with fellow UNILAK students</p>
    </x-slot>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Category --}}
                <div class="flex flex-col gap-1">
                    <label for="category_id" class="text-sm font-medium text-gray-700">
                        Category / Course <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800 {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : '' }}">
                        <option value="" disabled {{ old('category_id', $defaultCategoryId) ? '' : 'selected' }}>
                            Select category…
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
                    label="Material Title"
                    placeholder="e.g. Chapter 4: Database Normalization Lecture Slides"
                    :required="true"
                    autofocus
                />

                {{-- Description --}}
                <div class="flex flex-col gap-1">
                    <label for="description" class="text-sm font-medium text-gray-700">Description (Optional)</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        placeholder="Brief summary of the document, professor name, semester, or topics covered..."
                        class="block w-full rounded-lg border border-gray-300 bg-white p-3 text-sm text-gray-900 shadow-sm focus:border-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-800"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- File Picker --}}
                <div class="flex flex-col gap-1">
                    <label for="file" class="text-sm font-medium text-gray-700">
                        Document / Archive <span class="text-red-500 ml-0.5">*</span>
                    </label>
                    <input
                        type="file"
                        id="file"
                        name="file"
                        required
                        class="block w-full text-xs text-gray-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-800 hover:file:bg-primary-100 cursor-pointer border border-gray-300 rounded-lg p-1.5 focus:outline-none"
                    />
                    <p class="text-[11px] text-gray-400 mt-1">
                        Allowed: PDF, DOC, DOCX, PPT, PPTX, Images (PNG, JPG), ZIP. Max file size: <strong>10MB</strong>.
                    </p>
                    @error('file')
                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('resources.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
                    <x-button type="submit" variant="secondary">Upload Document</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-layouts.app>

