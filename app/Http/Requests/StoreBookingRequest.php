<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->check();
  }

  public function rules(): array
  {
    return [
      'event_id' => [
        'required',
        'integer',
        Rule::exists('events', 'id')->where(function ($query) {
            $query->where('start_date', '>=', now())
                  ->where('approval_status', 'approved');
        })
      ],
      'quantity' => ['required', 'integer', 'min:1'],
      'pass_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ];
  }
}