<?php

namespace App\Http\Requests\User;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ManageUserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

    $rules = [
      'name' => ['required', 'string', 'max:255'],
      'role' => ['required', Rule::enum(UserRole::class), Rule::notIn([UserRole::Admin->value])],
      'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
    ];

    if ($isUpdate) {
      // Allow the user to keep their current email, but enforce unique against others.
      // Assuming route model binding or getting ID from auth()->id(). Let's use auth()->id() as fallback.
      $userId = $this->user ? $this->user->id : auth()->id();
      $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $userId];
      $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
    } else {
      $rules['email'] = ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'];
      $rules['password'] = ['required', 'confirmed', Password::defaults()];
    }

    return $rules;
  }
}
