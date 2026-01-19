<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
        return [
            'ISBN' => [
                'sometimes',
                'string',
                'size:13',
                Rule::unique('books', 'ISBN')->ignore($this->book->id),
            ],
            'title' => 'sometimes|string|max:70',
            'price' => 'nullable|numeric|min:0|max:99.99',
            'mortgage' => 'sometimes|numeric|min:0|max:9999.99',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'authorship_date' => 'nullable|date',
            'category_id' => 'sometimes|exists:categories,id',
            'authors' => 'array',
            'authors.*' => 'integer|exists:authors,id'
        ];
    }
}
