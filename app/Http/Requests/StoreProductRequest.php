<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->can('create', Product::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title'             => 'required|string|min:3|max:255',
            'description'       => 'required|string|min:20',
            'short_description' => 'nullable|string|max:500',
            'price'             => 'required|numeric|min:0|max:999999',
            'sale_price'        => 'nullable|numeric|min:0|lt:price',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|unique:products,sku|max:100',
            'status'            => 'required|in:active,inactive,draft',
            'is_featured'       => 'nullable|boolean',
            'categories'        => 'required|array|min:1',
            'categories.*'      => 'exists:categories,id',
            'image'             => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'additional_images' => 'nullable|array|max:5',
            'additional_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Product title is required.',
            'image.required'       => 'Please upload a product image.',
            'image.max'            => 'Image must not exceed 2MB.',
            'categories.required'  => 'Please select at least one category.',
            'sale_price.lt'        => 'Sale price must be lower than the regular price.',
        ];
    }
}
