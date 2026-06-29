<?php

namespace App\DTOs\User;

use App\Http\Requests\User\ManageUserRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class ManageUserDTO
{
  public function __construct(
    public string $name,
    public string $email,
    public UserRole $role,
    public ?int $id = null,
    public ?string $password = null,
    public ?UploadedFile $profilePicture = null,
  ) {}

  public static function fromRequest(ManageUserRequest $request, ?int $userId = null): self
  {
    return new self(
      name: $request->validated('name'),
      email: $request->validated('email'),
      role: UserRole::from($request->validated('role')),
      id: $userId,
      password: $request->filled('password') ? Hash::make($request->validated('password')) : null,
      profilePicture: $request->hasFile('profile_picture') ? $request->file('profile_picture') : null,
    );
  }
}
