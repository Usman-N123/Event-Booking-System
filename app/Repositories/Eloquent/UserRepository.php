<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  /**
   * Get the total number of registered users.
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return User::count();
  }

  /**
   * Get the total number of organizer accounts.
   *
   * @return int
   */
  public function getOrganizerCount(): int
  {
    return User::where('role', UserRole::Organizer->value)->count();
  }
}
