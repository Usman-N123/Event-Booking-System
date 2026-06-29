<?php

namespace App\Features\Event;

use App\DTOs\Event\EventFilterDTO;
use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class OrganizerCancelEventFeature
{
    public function __construct(
        protected EventRepositoryInterface $eventRepository
    ) {}

    /**
     * Soft-delete an event, enforcing that the requesting organizer owns it.
     *
     * @param int $eventId
     * @param int $organizerId
     * @return bool
     * @throws Exception
     */
    public function handle(int $eventId, int $organizerId): bool
    {
        $result = $this->eventRepository->softDeleteByOrganizer($eventId, $organizerId);

        if (! $result) {
            throw new Exception('Event not found or you do not have permission to cancel it.', 403);
        }

        EventFilterDTO::bustListingsCache();
        Cache::forget(GetGlobalStatisticsFeature::CACHE_KEY);

        return true;
    }
}
