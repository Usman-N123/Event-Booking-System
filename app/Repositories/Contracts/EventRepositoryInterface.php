<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Pagination\LengthAwarePaginator;
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

  public function manage(\App\DTOs\Event\ManageEventDTO $dto): Event;

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
   * Get all events belonging to a specific organizer, paginated.
   *
   * @param int $organizerId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getOrganizerEvents(int $organizerId, int $perPage = 10): LengthAwarePaginator;

  /**
   * Get all draft events pending admin approval, paginated.
   *
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getPendingEvents(int $perPage = 15): LengthAwarePaginator;

  /**
   * Get all events with their organizer, paginated.
   *
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getAllEvents(int $perPage = 15): LengthAwarePaginator;



  /**
   * Soft-delete (cancel) an event by its ID.
   *
   * @param int $id
   * @return bool
   */
  public function softDelete(int $id): bool;

  /**
   * Soft-delete an event, but ONLY if it belongs to the given organizer.
   * Returns false (without deleting) if the event is owned by someone else.
   *
   * @param int $eventId
   * @param int $organizerId
   * @return bool
   */
  public function softDeleteByOrganizer(int $eventId, int $organizerId): bool;
}