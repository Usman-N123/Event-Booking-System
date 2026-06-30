<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageBookingRequest;
use App\DTOs\Booking\ManageBookingDTO;
use App\Features\Booking\ManageBookingFeature;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    public function __construct(
        protected ManageBookingFeature $manageBookingFeature
    ) {}

    public function store(ManageBookingRequest $request): RedirectResponse
    {
        try {
            $dto = ManageBookingDTO::fromRequest($request);

            $this->manageBookingFeature->handle($dto);

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
        return view('bookings.show', compact('booking'));
    }
}