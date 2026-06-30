<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Features\User\ApproveOrganizerFeature;
use App\Features\User\RejectOrganizerFeature;
use App\Features\User\AdminDeleteUserFeature;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminUserController extends Controller
{
    public function __construct(
        protected ApproveOrganizerFeature $approveOrganizerFeature,
        protected RejectOrganizerFeature  $rejectOrganizerFeature,
        protected AdminDeleteUserFeature  $adminDeleteUserFeature
    ) {}

    /**
     * Approve a pending organizer account.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function approve(User $user): RedirectResponse
    {
        try {
            $this->approveOrganizerFeature->handle($user->id);

            return redirect()->route('admin.dashboard')
              ->with('success', "Organizer \"{$user->name}\" has been approved.");
        } catch (Exception $e) {
            Log::error('Organizer Approval Failed: ' . $e->getMessage());
            return back()->with('error', 'Could not approve the organizer.');
        }
    }

    /**
     * Reject a pending organizer account.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function reject(User $user): RedirectResponse
    {
        try {
            $this->rejectOrganizerFeature->handle($user->id);

            return redirect()->route('admin.dashboard')
              ->with('success', "Organizer \"{$user->name}\" has been rejected.");
        } catch (Exception $e) {
            Log::error('Organizer Rejection Failed: ' . $e->getMessage());
            return back()->with('error', 'Could not reject the organizer.');
        }
    }

    /**
     * Soft-delete a user account.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        try {
            $this->adminDeleteUserFeature->handle($user->id);

            return redirect()->route('admin.dashboard')
              ->with('success', "User \"{$user->name}\" has been removed.");
        } catch (Exception $e) {
            Log::error('Admin User Deletion Failed: ' . $e->getMessage());
            return back()->with('error', 'Could not delete the user.');
        }
    }
}
