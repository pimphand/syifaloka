<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|array',
            'sku.*.name' => 'required|string',
            'sku.*.price' => 'required|numeric',
            'sku.*.stock' => 'required|numeric',
            'sku.*.image' => 'nullable|file|image|max:1024|mimes:jpg,png,jpeg,webp',
            'sku.*.attributes' => 'nullable|array',
            'sku.*.options' => 'nullable|array',
            'sku.*.extras' => 'nullable|array',
        ];
    }
}
