<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Orchestration order:
     *  1. Create the single Admin.
     *  2. Create 5 Organizers.
     *  3. Create 50 Attendees.
     *  4. Create a realistic mix of Events owned by Organizers.
     *  5. Create Bookings for Attendees, enforcing:
     *       – total_amount  = event.price × quantity  (math integrity)
     *       – available_seats never goes negative      (capacity integrity)
     *       – only CONFIRMED bookings reduce available_seats
     */
    public function run(): void
    {
        // ------------------------------------------------------------------ //
        // 1. ADMIN (exactly 1)
        // ------------------------------------------------------------------ //
        User::factory()->admin()->create([
            'name'     => 'Usman Ali (Admin)',
            'email'    => 'admin@eventbooking.pk',
            'password' => Hash::make('password123'),
        ]);

        // ------------------------------------------------------------------ //
        // 2. ORGANIZERS (exactly 5)
        // ------------------------------------------------------------------ //
        $organizers = collect();

        // One pinned organizer so we always have at least one with a known login.
        $organizers->push(
            User::factory()->organizer()->create([
                'name'     => 'Zara Mirza (Organizer)',
                'email'    => 'organizer@eventbooking.pk',
                'password' => Hash::make('password123'),
            ])
        );

        // 4 more randomly generated organizers.
        $organizers = $organizers->merge(
            User::factory()->organizer()->count(4)->create()
        );

        // ------------------------------------------------------------------ //
        // 3. ATTENDEES (exactly 50)
        // ------------------------------------------------------------------ //

        // One pinned attendee so we always have at least one with a known login.
        $pinnedAttendee = User::factory()->attendee()->create([
            'name'     => 'Ali Hassan (Attendee)',
            'email'    => 'attendee@eventbooking.pk',
            'password' => Hash::make('password123'),
        ]);

        // 49 more randomly generated attendees.
        $attendees = User::factory()->attendee()->count(49)->create()->prepend($pinnedAttendee);

        // ------------------------------------------------------------------ //
        // 4. EVENTS (30 total — approved, pending/draft, rejected; past & future)
        // ------------------------------------------------------------------ //

        // 18 APPROVED events — upcoming + past (bookable)
        $approvedUpcoming = Event::factory()
            ->approved()
            ->upcoming()
            ->count(15)
            ->create(['organizer_id' => fn () => $organizers->random()->id]);

        $approvedPast = Event::factory()
            ->approved()
            ->past()
            ->count(3)
            ->create(['organizer_id' => fn () => $organizers->random()->id]);

        // 7 PENDING (draft) events — mix of dates
        Event::factory()
            ->pending()
            ->count(7)
            ->create(['organizer_id' => fn () => $organizers->random()->id]);

        // 5 REJECTED events
        Event::factory()
            ->rejected()
            ->count(5)
            ->create(['organizer_id' => fn () => $organizers->random()->id]);

        // Only APPROVED events should receive bookings.
        $bookableEvents = $approvedUpcoming->merge($approvedPast);

        // ------------------------------------------------------------------ //
        // 5. BOOKINGS — enforce math & capacity integrity
        // ------------------------------------------------------------------ //

        foreach ($bookableEvents as $event) {
            // Each approved event gets between 3 and 12 booking attempts.
            $bookingAttempts = rand(3, 12);

            // Shuffle attendees so we get a natural spread; pick a subset.
            $shuffledAttendees = $attendees->shuffle()->take($bookingAttempts);

            foreach ($shuffledAttendees as $attendee) {
                // --- Capacity guard -----------------------------------------
                // Stop booking this event if it has no seats left.
                if ($event->available_seats <= 0) {
                    break;
                }

                // Decide quantity (1–4 tickets), but never exceed remaining seats.
                $maxQuantity = min(4, $event->available_seats);
                $quantity    = rand(1, $maxQuantity);

                // 15 % chance a booking is cancelled — cancelled bookings do
                // NOT reduce available_seats (refunded / never confirmed).
                $isCancelled = (rand(1, 100) <= 15);
                $status      = $isCancelled ? BookingStatus::CANCELLED : BookingStatus::CONFIRMED;

                // --- Math integrity: total_amount = price × quantity ----------
                $totalAmount = round((float) $event->price * $quantity, 2);

                // --- Create the booking ---------------------------------------
                Booking::factory()->create([
                    'event_id'    => $event->id,
                    'attendee_id' => $attendee->id,
                    'quantity'    => $quantity,
                    'total_amount'=> $totalAmount,
                    'status'      => $status,
                ]);

                // --- Capacity integrity: only confirmed bookings deduct seats --
                if (! $isCancelled) {
                    // Decrement in-memory so the guard above stays accurate, then
                    // persist the updated count to the database.
                    $event->available_seats -= $quantity;
                    $event->saveQuietly();   // bypass events/observers
                }
            }
        }

        // ------------------------------------------------------------------ //
        // Summary output
        // ------------------------------------------------------------------ //
        $this->command->info('✅  DatabaseSeeder completed successfully.');
        $this->command->table(
            ['Entity', 'Count'],
            [
                ['Admin users',        User::where('role', UserRole::Admin->value)->count()],
                ['Organizer users',    User::where('role', UserRole::Organizer->value)->count()],
                ['Attendee users',     User::where('role', UserRole::Attendee->value)->count()],
                ['Approved events',    Event::where('approval_status', 'approved')->count()],
                ['Draft events',       Event::where('approval_status', 'draft')->count()],
                ['Rejected events',    Event::where('approval_status', 'rejected')->count()],
                ['Confirmed bookings', Booking::where('status', 'confirmed')->count()],
                ['Cancelled bookings', Booking::where('status', 'cancelled')->count()],
            ]
        );
    }
}