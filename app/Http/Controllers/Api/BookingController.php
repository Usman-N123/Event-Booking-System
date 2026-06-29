<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageBookingRequest;
use App\DTOs\Booking\ManageBookingDTO;
use App\Features\Booking\ManageBookingFeature;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    public function __construct(
        protected ManageBookingFeature $manageBookingFeature
    ) {}

    public function store(ManageBookingRequest $request): JsonResponse
    {
        try {
            $dto = ManageBookingDTO::fromRequest($request);

            $booking = $this->manageBookingFeature->handle($dto);

            return response()->json([
                'status' => true,
                'message' => 'Tickets booked successfully.',
                'data' => new BookingResource($booking),
            ], 201);

        } catch (Exception $e) {
            $statusCode = $e->getCode() >= 400 && $e->getCode() < 500 ? $e->getCode() : 500;
            $message = $statusCode === 500 ? 'An error occurred while processing the booking.' : $e->getMessage();
            
            Log::error('Booking Failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => ['booking' => [$message]],
            ], $statusCode);
        }
    }
}