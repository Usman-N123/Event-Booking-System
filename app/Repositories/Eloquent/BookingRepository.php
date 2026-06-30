<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\DTOs\Booking\ManageBookingDTO;

class BookingRepository implements BookingRepositoryInterface
{
    public function manage(ManageBookingDTO $dto): Booking
    {
        return DB::transaction(function () use ($dto) {
            if ($dto->id) {
                $booking = Booking::find($dto->id);
                if (!$booking) {
                    throw new \Exception("Booking not found.", 404);
                }
                $event = Event::find($booking->event_id);
                
                if ($dto->quantity !== $booking->quantity) {
                    $diff = $dto->quantity - $booking->quantity;
                    if ($diff > 0 && $event->available_seats < $diff) {
                        throw new \Exception("Not enough available seats.", 422);
                    }
                    $event->decrement('available_seats', $diff);
                }
            } else {
                $booking = new Booking();
                $booking->booking_reference = 'TKT-' . strtoupper(\Illuminate\Support\Str::random(8));
                $booking->status = \App\Enums\BookingStatus::CONFIRMED->value;
                $booking->attendee_id = $dto->attendeeId;
                $booking->event_id = $dto->eventId;
                
                $event = \App\Models\Event::where('id', $dto->eventId)->lockForUpdate()->first();

                if (!$event || $event->start_date < now() || $event->approval_status->value !== 'approved') {
                    throw new \Exception("Event is not available for booking.", 422);
                }

                if ($event->available_seats < $dto->quantity) {
                    throw new \Exception("Not enough available seats. Only {$event->available_seats} remaining.", 422);
                }

                $event->decrement('available_seats', $dto->quantity);
            }

            $booking->quantity = $dto->quantity;
            $booking->total_amount = $event->price * $dto->quantity;

            if ($dto->passPicture !== null) {
                if ($booking->pass_picture_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($booking->pass_picture_path);
                }
                $booking->pass_picture_path = $dto->passPicture->store('bookings/pass_pictures', 'public');
            } elseif (!$dto->id) {
                $attendee = \App\Models\User::find($dto->attendeeId);
                $booking->pass_picture_path = $attendee?->profile_picture_path;
            }

            $booking->save();

            \App\DTOs\Event\EventFilterDTO::bustListingsCache();
            \Illuminate\Support\Facades\Cache::forget(\App\Features\Dashboard\GetGlobalStatisticsFeature::CACHE_KEY);

            return $booking;
        });
    }

  /**
   * Get all bookings for a specific attendee, paginated.
   *
   * @param int $userId
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getUserBookings(int $userId, int $perPage = 10): LengthAwarePaginator
  {
    return Booking::with('event')
      ->where('attendee_id', $userId)
      ->latest()
      ->paginate($perPage);
  }

  /**
   * Get the total number of bookings.
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return Booking::count();
  }

  /**
   * Get the total revenue from confirmed bookings.
   *
   * @return float
   */
  public function getTotalRevenue(): float
  {
    return (float) Booking::where('status', BookingStatus::CONFIRMED->value)->sum('total_amount');
  }

  /**
   * Get all bookings, paginated.
   *
   * @param int $perPage
   * @return LengthAwarePaginator
   */
  public function getAllBookings(int $perPage = 15): LengthAwarePaginator
  {
    return Booking::with(['event', 'attendee'])->latest()->paginate($perPage, ['*'], 'bookings_page')->withQueryString();
  }

  /**
   * Get aggregated stats for an organizer's events.
   *
   * @param int $organizerId
   * @return array{total_bookings: int, total_revenue: float}
   */
  public function getOrganizerStats(int $organizerId): array
  {
    $stats = Booking::join('events', 'bookings.event_id', '=', 'events.id')
      ->where('events.organizer_id', $organizerId)
      ->whereNull('bookings.deleted_at')
      ->selectRaw('COUNT(bookings.id) as total_bookings, COALESCE(SUM(bookings.total_amount), 0) as total_revenue')
      ->first();

    return [
      'total_bookings' => (int) ($stats->total_bookings ?? 0),
      'total_revenue' => (float) ($stats->total_revenue ?? 0),
    ];
  }
}
