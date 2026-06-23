<?php

namespace App\Features\Booking;

use App\DTOs\Booking\BookingCreateDTO;
use App\DTOs\Event\EventFilterDTO;
use App\Models\Booking;
use App\Models\User;
use App\Enums\BookingStatus;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class ProcessTicketBookingFeature
{
  public function __construct(
    protected EventRepositoryInterface $eventRepository,
    protected BookingRepositoryInterface $bookingRepository
  ) {}

  /**
   * Process a ticket booking with strict concurrency control.
   *
   * @param BookingCreateDTO $dto
   * @return Booking
   * @throws Exception
   */
  public function handle(BookingCreateDTO $dto): Booking
  {
    $booking = DB::transaction(function () use ($dto) {

      $event = $this->eventRepository->findAndLock($dto->eventId);

      if (!$event || $event->start_date < now() || $event->approval_status->value !== 'approved') {
        throw new Exception("Event is not available for booking.", 422);
      }

      if ($event->available_seats < $dto->quantity) {
        throw new Exception("Not enough available seats. Only {$event->available_seats} remaining.", 422);
      }

      $totalAmount = $event->price * $dto->quantity;

      $this->eventRepository->decrementSeats($event->id, $dto->quantity);

      $bookingReference = 'TKT-' . strtoupper(Str::random(8));

      $passPicturePath = $this->resolvePassPicture($dto);

      $bookingData = [
        'event_id' => $event->id,
        'attendee_id' => $dto->attendeeId,
        'quantity' => $dto->quantity,
        'total_amount' => $totalAmount,
        'booking_reference' => $bookingReference,
        'status' => BookingStatus::CONFIRMED->value,
        'pass_picture_path' => $passPicturePath,
      ];

      return $this->bookingRepository->create($bookingData);
    });

    EventFilterDTO::bustListingsCache();
    Cache::forget('admin.dashboard.global_statistics');

    return $booking;
  }

  /**
   * Resolve the pass picture path.
   * Priority: uploaded pass picture > attendee profile picture > null
   *
   * @param BookingCreateDTO $dto
   * @return string|null
   */
  private function resolvePassPicture(BookingCreateDTO $dto): ?string
  {
    if ($dto->passPicture !== null) {
      return $dto->passPicture->store('bookings/pass_pictures', 'public');
    }

    $attendee = User::find($dto->attendeeId);

    return $attendee?->profile_picture_path;
  }
}