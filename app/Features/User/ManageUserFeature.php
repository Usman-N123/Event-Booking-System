<?php

namespace App\Features\User;

use App\DTOs\User\ManageUserDTO;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class ManageUserFeature
{
  public function __construct(
    protected UserRepositoryInterface $userRepository
  ) {}

  public function handle(ManageUserDTO $dto): User
  {
    return $this->userRepository->manage($dto);
  }
}
