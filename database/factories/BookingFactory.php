<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 *
 * NOTE: This factory intentionally leaves total_amount = 0 and quantity = 1
 * as safe defaults. The DatabaseSeeder ALWAYS overrides these values to
 * enforce math integrity (total_amount = event.price * quantity) and
 * capacity integrity (available_seats never goes negative).
 *
 * Do NOT use BookingFactory::create() stand-alone in tests without
 * providing the correct event_id, attendee_id, quantity, and total_amount.
 */
class BookingFactory extends Factory
{
    /**
     * The model the factory corresponds to.
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id'         => Event::factory(),              // overridden in seeder
            'attendee_id'      => User::factory()->attendee(),   // overridden in seeder
            'quantity'         => 1,                             // overridden in seeder
            'total_amount'     => 0.00,                          // overridden in seeder
            'booking_reference'=> strtoupper('BK-' . Str::random(3) . '-' . $this->faker->unique()->numberBetween(10000, 99999)),
            'status'           => BookingStatus::CONFIRMED,
            'pass_picture_path'=> null,
        ];
    }

    // -------------------------------------------------------------------------
    // Status State Methods
    // -------------------------------------------------------------------------

    /**
     * Mark the booking as confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::CONFIRMED,
        ]);
    }

    /**
     * Mark the booking as cancelled.
     * Cancelled bookings do NOT deduct available_seats in the seeder.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::CANCELLED,
        ]);
    }
}
