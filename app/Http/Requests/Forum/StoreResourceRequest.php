<?php

namespace App\Http\Requests\Forum;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title'       => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file'        => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,ppt,pptx,png,jpg,jpeg,zip,txt'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select the relevant course or department category.',
            'title.required'       => 'Please provide a descriptive title for this academic material.',
            'file.required'        => 'Please choose a document or file to upload.',
            'file.max'             => 'File size cannot exceed 10MB.',
            'file.mimes'           => 'Allowed file types are PDF, DOC, DOCX, PPT, PPTX, Images (PNG, JPG, JPEG), ZIP, and TXT.',
        ];
    }
}
