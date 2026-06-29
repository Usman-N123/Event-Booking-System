<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Features\Event\OrganizerCancelEventFeature;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class OrganizerEventController extends Controller
{
    public function __construct(
        protected OrganizerCancelEventFeature $organizerCancelEventFeature
    ) {}

    /**
     * Soft-delete (cancel) an organizer's own event.
     *
     * @param Event $event
     * @return RedirectResponse
     */
    public function cancel(Event $event): RedirectResponse
    {
        try {
            $this->organizerCancelEventFeature->handle($event->id, Auth::id());

            return redirect()->route('organizer.dashboard')
                ->with('success', 'Your event has been cancelled successfully.');
        } catch (Exception $e) {
            Log::error('Organizer Event Cancellation Failed: ' . $e->getMessage());

            return back()->with('error', 'Could not cancel the event. Please try again.');
        }
    }
}
