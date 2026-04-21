<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('product'));
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'title'             => 'required|string|min:3|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'sku'               => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product?->id)],
            'status'            => 'required|in:active,inactive,draft',
            'categories'        => 'required|array|min:1',
            'categories.*'      => 'exists:categories,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
