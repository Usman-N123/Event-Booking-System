<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\DTOs\Booking\BookingCreateDTO;
use App\Features\Booking\ProcessTicketBookingFeature;
use App\Http\Resources\BookingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    public function __construct(
        protected ProcessTicketBookingFeature $processTicketBookingFeature
    ) {}

    /**
     * Store a newly created booking securely.
     *
     * @param StoreBookingRequest $request
     * @return JsonResponse
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            // 1. Map validated request to DTO
            $dto = BookingCreateDTO::fromRequest($request);

            // 2. Execute Transactional Business Logic
            $booking = $this->processTicketBookingFeature->handle($dto);

            // 3. Return Strictly Formatted Success Response
            return response()->json([
                'status' => true,
                'message' => 'Tickets booked successfully.',
                'data' => new BookingResource($booking),
            ], 201);

        } catch (Exception $e) {
            // Differentiate between user errors (422/404) and server errors (500)
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