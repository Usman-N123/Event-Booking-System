<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventStatusRequest;
use App\Features\Event\UpdateEventStatusFeature;
use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Http\Resources\EventResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminController extends Controller
{
    public function __construct(
        protected UpdateEventStatusFeature $updateEventStatusFeature,
        protected GetGlobalStatisticsFeature $getGlobalStatisticsFeature
    ) {}

    /**
     * Approve or reject an event.
     *
     * @param UpdateEventStatusRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateEventStatus(UpdateEventStatusRequest $request, int $id): JsonResponse
    {
        try {
            $event = $this->updateEventStatusFeature->handle(
                $id, 
                $request->validated('approval_status')
            );

            return response()->json([
                'status' => true,
                'message' => 'Event status updated successfully.',
                'data' => new EventResource($event),
            ], 200);

        } catch (Exception $e) {
            $statusCode = $e->getCode() === 404 ? 404 : 500;
            Log::error('Event Status Update Failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the event status.',
                'errors' => ['admin' => [$e->getMessage()]],
            ], $statusCode);
        }
    }

    /**
     * Retrieve global statistics for the admin dashboard.
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->getGlobalStatisticsFeature->handle();

            return response()->json([
                'status' => true,
                'message' => 'Global statistics retrieved successfully.',
                'data' => $stats,
            ], 200);

        } catch (Exception $e) {
            Log::error('Dashboard Stats Failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching statistics.',
                'errors' => ['admin' => ['Internal Server Error']],
            ], 500);
        }
    }
}