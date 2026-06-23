<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\DTOs\Event\EventFilterDTO;

interface EventRepositoryInterface
{
  /**
   * Update the approval status of an event.
   *
   * @param int $id
   * @param string $status
   * @return Event|null
   */
  public function updateStatus(int $id, string $status): ?Event;

  /**
   * Get a paginated list of active, approved events.
   *
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getActiveEventsPaginated(int $perPage = 15): LengthAwarePaginator;

  /**
   * Find a specific event by its ID.
   *
   * @param int $id
   * @return Event|null
   */
  public function findById(int $id): ?Event;

  /**
   * Create a new event record.
   *
   * @param array $data
   * @return Event
   */
  public function create(array $data): Event;

  /**
   * Get a paginated, filtered, and cached list of public events.
   *
   * @param EventFilterDTO $filters
   * @return LengthAwarePaginator
   */
  public function getPublicEvents(EventFilterDTO $filters): LengthAwarePaginator;

  /**
   * Decrement the available seats for an event.
   *
   * @param int $eventId
   * @param int $quantity
   * @return bool
   */
  public function decrementSeats(int $eventId, int $quantity): bool;

  /**
   * Soft delete draft events that have passed their start date.
   *
   * @return int The number of deleted records
   */
  public function deleteExpiredDrafts(): int;

  /**
   * Find an event and lock the row for updating to prevent race conditions.
   *
   * @param int $id
   * @return Event|null
   */
  public function findAndLock(int $id): ?Event;

  /**
   * Get the total number of events in the system.
   *
   * @return int
   */
  public function getTotalCount(): int;

  /**
   * Get the total number of events pending approval (drafts).
   *
   * @return int
   */
  public function getPendingCount(): int;

  /**
   * Get all events belonging to a specific organizer.
   *
   * @param int $organizerId
   * @return Collection
   */
  public function getOrganizerEvents(int $organizerId): Collection;

  /**
   * Get all draft events pending admin approval, with organizer eager-loaded.
   *
   * @return Collection
   */
  public function getPendingEvents(): Collection;

  /**
   * Get all events with their organizer.
   *
   * @return Collection
   */
  public function getAllEvents(): Collection;

  /**
   * Update an event record with the given data.
   *
   * @param int $id
   * @param array $data
   * @return Event
   */
  public function update(int $id, array $data): Event;

  /**
   * Soft-delete (cancel) an event by its ID.
   *
   * @param int $id
   * @return bool
   */
  public function softDelete(int $id): bool;
}