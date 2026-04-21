<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => 'nullable|exists:products,id',
            'vendor_id'  => 'nullable|exists:users,id',
            'rating'     => 'required|integer|min:1|max:5',
            'title'      => 'nullable|string|max:100',
            'comment'    => 'nullable|string|min:3|max:1000',
        ];
    }
}
