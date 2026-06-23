<?php

namespace App\DTOs\Auth;

use App\Http\Requests\Auth\UserCreateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class UserCreateDTO
{
  public function __construct(
    public string $name,
    public string $email,
    public string $password,
    public UserRole $role,
    public ?UploadedFile $profilePicture = null,
  ) {}

  public static function fromRequest(UserCreateRequest $request): self
  {
    return new self(
      name: $request->validated('name'),
      email: $request->validated('email'),
      password: Hash::make($request->validated('password')),
      role: UserRole::from($request->validated('role')),
      profilePicture: $request->hasFile('profile_picture') ? $request->file('profile_picture') : null,
    );
  }
}