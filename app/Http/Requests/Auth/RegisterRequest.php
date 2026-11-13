<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:50', 'unique:users,student_id'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'department' => ['required', 'string', 'max:150'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.unique' => 'This student ID is already registered.',
            'email.unique'      => 'This email is already registered.',
            'password.confirmed' => 'The passwords do not match.',
        ];
    }
}

