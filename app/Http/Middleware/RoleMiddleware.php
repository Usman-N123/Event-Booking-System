<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @param Closure $next
   * @param string $role
   * @return Response
   */
  public function handle(Request $request, Closure $next, string $role): Response
  {
    $user = $request->user();

    if (!$user || $user->role->value !== $role) {
      abort(403, 'You do not have the required permissions.');
    }

    return $next($request);
  }
}