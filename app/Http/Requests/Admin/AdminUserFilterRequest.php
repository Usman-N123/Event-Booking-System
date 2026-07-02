<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'    => ['nullable', 'string', 'max:100'],
            'role'      => ['nullable', 'string', 'in:admin,organizer,attendee'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
