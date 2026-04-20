<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,processing,validated,shipped,delivered,cancelled'],
            'payment_status' => ['nullable', 'in:pending,paid,failed,refunded'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
