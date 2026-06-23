<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
  /**
   * Get the total number of registered users.
   *
   * @return int
   */
  public function getTotalCount(): int;

  /**
   * Get the total number of organizer accounts.
   *
   * @return int
   */
  public function getOrganizerCount(): int;
}
