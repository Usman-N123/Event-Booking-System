<?php

namespace App\Features\Auth;

use App\DTOs\Auth\UserCreateDTO;
use App\Models\User;

class RegisterUserFeature
{
  public function handle(UserCreateDTO $dto): User
  {
    $profilePicturePath = null;

    if ($dto->profilePicture !== null) {
      $profilePicturePath = $dto->profilePicture->store('users/profile_pictures', 'public');
    }

    return User::create([
      'name' => $dto->name,
      'email' => $dto->email,
      'password' => $dto->password,
      'role' => $dto->role->value,
      'profile_picture_path' => $profilePicturePath,
    ]);
  }
}