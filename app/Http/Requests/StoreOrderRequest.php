<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shipping_address' => ['required', 'string', 'min:10', 'max:1000'],
            'payment_method' => ['required', 'in:cash_on_delivery,credit_card'],
            'notes' => ['nullable', 'string', 'max:500'],
            'items' => ['nullable', 'array', 'min:1'],
            'items.*.product_id' => ['required_with:items', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required_with:items', 'integer', 'min:1', 'max:100'],
        ];
    }
}
