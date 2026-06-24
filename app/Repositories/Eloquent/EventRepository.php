<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Enums\EventApprovalStatus;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\DTOs\Event\EventFilterDTO;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class EventRepository implements EventRepositoryInterface
{
    /**
     * Update the approval status of an event.
     *
     * @param int $id
     * @param string $status
     * @return Event|null
     */
    public function updateStatus(int $id, string $status): ?Event
    {
        $event = $this->findById($id);
        
        if ($event) {
            $event->update(['approval_status' => $status]);
        }
        
        return $event;
    }

    /**
     * Get a paginated list of active, approved events.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getActiveEventsPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Event::where('approval_status', EventApprovalStatus::APPROVED->value)
            ->upcoming()
            ->orderBy('start_date', 'asc')
            ->paginate($perPage);
    }

    /**
     * Find a specific event by its ID.
     *
     * @param int $id
     * @return Event|null
     */
    public function findById(int $id): ?Event
    {
        return Event::find($id);
    }

    /**
     * Create a new event record.
     *
     * @param array $data
     * @return Event
     */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /**
     * Get a paginated, filtered, and cached list of public events.
     *
     * @param EventFilterDTO $filters
     * @return LengthAwarePaginator
     */
    public function getPublicEvents(EventFilterDTO $filters): LengthAwarePaginator
    {
        $cacheKey = $filters->generateCacheKey();
        $cacheDuration = 60 * 15; // Cache for 15 minutes

        return Cache::remember($cacheKey, $cacheDuration, function () use ($filters) {
            
            $query = Event::query()
                ->where('approval_status', EventApprovalStatus::APPROVED->value)
                ->upcoming(); // Only future events

            // 1. Search (Title or Description)
            if ($filters->search) {
                $query->where(function($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters->search . '%')
                      ->orWhere('description', 'like', '%' . $filters->search . '%');
                });
            }

            // 2. Exact Match Filters
            if ($filters->category) {
                $query->where('category', $filters->category);
            }
            if ($filters->city) {
                $query->where('city', $filters->city);
            }

            // 3. Date Range
            if ($filters->dateFrom) {
                $query->whereDate('start_date', '>=', $filters->dateFrom);
            }
            if ($filters->dateTo) {
                $query->whereDate('start_date', '<=', $filters->dateTo);
            }

            // 4. Sorting
            if ($filters->sortPrice) {
                $query->orderBy('price', $filters->sortPrice);
            } else {
                $query->orderBy('start_date', 'asc'); // Default sort
            }

            return $query->paginate($filters->perPage);
        });
    }

    /**
     * Decrement the available seats for an event safely.
     *
     * @param int $eventId
     * @param int $quantity
     * @return bool
     */
    public function decrementSeats(int $eventId, int $quantity): bool
    {
        $event = $this->findById($eventId);
        
        if (!$event || $event->available_seats < $quantity) {
            return false;
        }

        return $event->decrement('available_seats', $quantity);
    }

    /**
     * Soft delete draft events that have passed their start date.
     *
     * @return int
     */
    public function deleteExpiredDrafts(): int
    {
        return Event::where('approval_status', EventApprovalStatus::DRAFT->value)
            ->where('start_date', '<', now())
            ->delete();
    }

    /**
     * Find an event and lock the row for updating.
     *
     * @param int $id
     * @return \App\Models\Event|null
     */
    public function findAndLock(int $id): ?Event
    {
        // lockForUpdate() prevents any other database connections from modifying 
        // or even reading this row using another lock until our transaction finishes.
        return Event::where('id', $id)->lockForUpdate()->first();
    }

    /**
     * Get the total number of events in the system.
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return Event::count();
    }

    /**
     * Get the total number of events pending approval (drafts).
     *
     * @return int
     */
    public function getPendingCount(): int{
        return Event::where('approval_status', EventApprovalStatus::DRAFT->value)->count();
    }

    /**
     * Get all events belonging to a specific organizer, paginated.
     *
     * @param int $organizerId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getOrganizerEvents(int $organizerId, int $perPage = 10): LengthAwarePaginator
    {
        return Event::where('organizer_id', $organizerId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all draft events pending admin approval, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPendingEvents(int $perPage = 15): LengthAwarePaginator
    {
        return Event::with('organizer')
            ->where('approval_status', EventApprovalStatus::DRAFT->value)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all events with their organizer, paginated.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllEvents(int $perPage = 15): LengthAwarePaginator
    {
        return Event::with('organizer')->latest()->paginate($perPage);
    }

    /**
     * Update an event record with the given data.
     *
     * @param int $id
     * @param array $data
     * @return Event
     */
    public function update(int $id, array $data): Event
    {
        $event = $this->findById($id);
        $event->fill($data)->save();
        return $event;
    }

    /**
     * Soft-delete (cancel) an event by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id): bool
    {
        $event = $this->findById($id);
        return (bool) $event?->delete();
    }
}