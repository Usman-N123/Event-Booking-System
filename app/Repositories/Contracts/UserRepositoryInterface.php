<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
  public function manage(\App\DTOs\User\ManageUserDTO $dto): User;

  /**
   * Get the total number of registered users.
   */
  public function getTotalCount(): int;

  /**
   * Get the total number of organizer accounts.
   */
  public function getOrganizerCount(): int;

  /**
   * Get all users paginated (for admin view).
   */
  public function getAllUsersPaginated(int $perPage = 15): LengthAwarePaginator;

  /**
   * Get all Organizers whose is_approved = false.
   */
  public function getPendingOrganizers(int $perPage = 15): LengthAwarePaginator;

  /**
   * Find a user by ID.
   */
  public function findById(int $id): ?User;

  /**
   * Set is_approved = true for the given user.
   */
  public function approve(int $id): bool;

  /**
   * Set is_approved = false for the given user.
   */
  public function reject(int $id): bool;

  /**
   * Soft-delete a user account.
   */
  public function softDelete(int $id): bool;
}
