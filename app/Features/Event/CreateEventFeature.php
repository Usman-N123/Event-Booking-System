<?php

namespace App\Features\Event;

use App\Enums\EventApprovalStatus;
use App\DTOs\Event\EventCreateDTO;
use App\DTOs\Event\EventFilterDTO;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CreateEventFeature
{
  public function __construct(
    protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Handle the creation of a new event, including file uploads.
   *
   * @param EventCreateDTO $dto
   * @return Event
   */
  public function handle(EventCreateDTO $dto): Event
  {
    $bannerPath = $dto->banner->store('events/banners', 'public');
    $nocPath = $dto->nocDocument->store('events/noc_documents', 'local');

    $eventData = [
      'organizer_id' => $dto->organizerId,
      'title' => $dto->title,
      'slug' => Str::slug($dto->title . '-' . time()),
      'description' => $dto->description,
      'category' => $dto->category,
      'city' => $dto->city,
      'start_date' => $dto->startDate,
      'end_date' => $dto->endDate,
      'price' => $dto->price,
      'total_seats' => $dto->totalSeats,
      'available_seats' => $dto->totalSeats,
      'banner_path' => $bannerPath,
      'noc_document_path' => $nocPath,
      'approval_status' => EventApprovalStatus::DRAFT->value,
    ];

    $event = $this->eventRepository->create($eventData);

    EventFilterDTO::bustListingsCache();

    return $event;
  }
}