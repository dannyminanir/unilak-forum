<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'tags'        => ['nullable', 'string', 'max:255'],
        ];
    }
}

