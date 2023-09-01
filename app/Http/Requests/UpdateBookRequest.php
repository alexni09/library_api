<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array {
        return [
            'name' => ['nullable', 'required_without_all:rating,category_id', 'string', 'max:255'],
            'rating' => ['nullable', 'required_without_all:name,category_id', 'integer', 'min:1', 'max:5'],
            'category_id' => ['nullable', 'required_without_all:name,rating', 'integer', 'min:1', 'exists:categories,id']
        ];
    }
}