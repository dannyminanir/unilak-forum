<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'min:3'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Your reply cannot be empty.',
            'content.min'      => 'Your reply must be at least 3 characters long.',
        ];
    }
}

