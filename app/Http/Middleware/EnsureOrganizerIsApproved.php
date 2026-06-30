<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrganizerIsApproved
{
    /**
     * Block Organizer accounts that have not yet been approved by an Admin.
     * All other roles pass through without restriction.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user &&
            $user->role === UserRole::Organizer &&
            ! $user->isApproved()
        ) {
            return redirect()
              ->route('organizer.dashboard')
              ->with('warning', 'Your organizer account is pending Admin approval. You cannot publish events yet.');
        }

        return $next($request);
    }
}
