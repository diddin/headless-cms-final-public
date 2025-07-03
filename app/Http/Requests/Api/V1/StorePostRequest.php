<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Daftar bahasa yang didukung (bisa diambil dari config)
        // $locales = ['en', 'id']; // or config('locales.supported', ['en', 'id'])
        $locales = array_keys(config('locales.supported'));

        $rules = [
            'status'         => 'required|in:draft,published',
            'published_at'   => 'nullable|date',
            'image'          => 'nullable|image|max:2048',
            'categories'     => 'nullable|array',
            'categories.*'   => 'exists:categories,id',
        ];

        foreach ($locales as $locale) {
            $rules["title.$locale"]   = 'required|string|max:255';
            $rules["content.$locale"] = 'required|string';
            $rules["excerpt.$locale"] = 'nullable|string|max:255';
        }

        return $rules;
    }
}
