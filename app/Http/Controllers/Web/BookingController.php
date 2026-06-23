<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\DTOs\Booking\BookingCreateDTO;
use App\Features\Booking\ProcessTicketBookingFeature;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    public function __construct(
        protected ProcessTicketBookingFeature $processTicketBookingFeature
    ) {}

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        try {
            $dto = BookingCreateDTO::fromRequest($request);

            $this->processTicketBookingFeature->handle($dto);

            return redirect()->route('attendee.dashboard')
                ->with('success', 'Tickets booked successfully.');

        } catch (Exception $e) {
            Log::error('Booking Failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'An error occurred while processing the booking.');
        }
    }

    public function show(Booking $booking): View
    {
        if ($booking->attendee_id !== auth()->id()) {
            abort(403, 'You do not own this booking.');
        }

        return view('bookings.show', compact('booking'));
    }
}