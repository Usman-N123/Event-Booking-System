<?php

namespace App\Features\Event;

use App\DTOs\Admin\AdminEventFilterDTO;
use App\Models\Event;
use App\DTOs\Event\EventFilterDTO;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class UpdateEventStatusFeature
{
  public function __construct(
    protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Handle the state transition of an event's approval status.
   *
   * @param int $eventId
   * @param string $status
   * @return Event
   * @throws Exception
   */
  public function handle(int $eventId, string $status): Event
  {
    $event = $this->eventRepository->updateStatus($eventId, $status);

    if (!$event) {
      throw new Exception("Event not found.", 404);
    }

    EventFilterDTO::bustListingsCache();
    AdminEventFilterDTO::bustListingsCache();
    Cache::forget('admin.dashboard.global_statistics');

    return $event;
  }
}