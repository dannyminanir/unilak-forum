<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'min:5', 'max:255'],
            'content'     => ['required', 'string', 'min:10'],
            'tags'        => ['nullable', 'string', 'max:255'], // comma-separated e.g. "Laravel, PHP, Exam Prep"
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please choose an appropriate category for your discussion.',
            'category_id.exists'   => 'The selected category is invalid.',
            'title.required'       => 'Please provide a clear title for your question or topic.',
            'title.min'            => 'The title must be at least 5 characters.',
            'content.required'     => 'Please write the details of your question or discussion topic.',
            'content.min'          => 'The content should be at least 10 characters long.',
        ];
    }
}

