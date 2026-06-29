<?php

namespace App\Features\Booking;

use App\DTOs\Booking\ManageBookingDTO;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;

class ManageBookingFeature
{
  public function __construct(
    protected BookingRepositoryInterface $bookingRepository
  ) {}

  public function handle(ManageBookingDTO $dto): Booking
  {
    return $this->bookingRepository->manage($dto);
  }
}
