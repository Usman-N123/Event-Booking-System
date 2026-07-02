<?php

namespace App\Features\Event;

use App\DTOs\Admin\AdminEventFilterDTO;
use App\DTOs\Event\EventFilterDTO;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class CancelEventFeature
{
  public function __construct(
    protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Soft-delete an event (admin cancel), then invalidate all relevant caches.
   *
   * @param int $eventId
   * @return bool
   * @throws Exception
   */
  public function handle(int $eventId): bool
  {
    $result = $this->eventRepository->softDelete($eventId);

    if (!$result) {
      throw new Exception("Event not found or could not be cancelled.", 404);
    }

    EventFilterDTO::bustListingsCache();
    AdminEventFilterDTO::bustListingsCache();
    Cache::forget('admin.dashboard.global_statistics');

    return true;
  }
}
