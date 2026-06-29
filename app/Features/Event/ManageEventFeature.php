<?php

namespace App\Features\Event;

use App\DTOs\Event\ManageEventDTO;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;

class ManageEventFeature
{
    public function __construct(
        protected EventRepositoryInterface $eventRepository
    ) {}

    public function handle(ManageEventDTO $dto): Event
    {
        return $this->eventRepository->manage($dto);
    }
}
