<?php

namespace App\Features\Event;

use App\DTOs\Event\EventUpdateDTO;
use App\DTOs\Event\EventFilterDTO;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Exception;

class UpdateEventFeature
{
  public function __construct(
    protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Update an event's details, replacing files only when new ones are uploaded.
   *
   * @param EventUpdateDTO $dto
   * @return Event
   * @throws Exception
   */
  public function handle(EventUpdateDTO $dto): Event
  {
    $existing = $this->eventRepository->findById($dto->eventId);

    if (!$existing) {
      throw new Exception("Event not found.", 404);
    }

    $data = [
      'title' => $dto->title,
      'description' => $dto->description,
      'category' => $dto->category,
      'city' => $dto->city,
      'start_date' => $dto->startDate,
      'end_date' => $dto->endDate,
      'price' => $dto->price,
    ];

    if ($dto->banner !== null) {
      Storage::disk('public')->delete($existing->banner_path);
      $data['banner_path'] = $dto->banner->store('events/banners', 'public');
    }

    if ($dto->nocDocument !== null) {
      Storage::disk('local')->delete($existing->noc_document_path);
      $data['noc_document_path'] = $dto->nocDocument->store('events/noc_documents', 'local');
    }

    $event = $this->eventRepository->update($dto->eventId, $data);

    EventFilterDTO::bustListingsCache();

    return $event;
  }
}
