<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
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
        $book = $this->route('book');
        return [
            'ISBN' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string',
                'size:13',
                Rule::unique('books', 'ISBN')->ignore($book?->id),
            ],
            'title' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'string',
                'max:70'
            ],
            'price' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'numeric',
                'min:0',
                'max:99.99'
            ],
            'mortgage' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'numeric',
                'min:0',
                'max:9999.99'
            ],
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'authorship_date' => 'nullable|date',
            'category_id' => [
                $this->isMethod('post') ? 'required' : 'sometimes',
                'exists:categories,id'
            ],
            'authors' => 'sometimes|array',
            'authors.*' => 'integer|exists:authors,id',
        ];
    }
}
