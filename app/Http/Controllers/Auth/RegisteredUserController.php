<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ManageUserRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\DTOs\User\ManageUserDTO;
use App\Features\User\ManageUserFeature;

class RegisteredUserController extends Controller
{
  public function __construct(
    protected ManageUserFeature $manageUserFeature
  ) {}

  public function create(): View
  {
    return view('auth.register');
  }

  public function store(ManageUserRequest $request): RedirectResponse
  {
    $dto = ManageUserDTO::fromRequest($request);
    
    $user = $this->manageUserFeature->handle($dto);

    Auth::login($user);

    if ($user->role->value === 'organizer') {
      return redirect()->route('organizer.dashboard');
    }

    return redirect()->route('attendee.dashboard');
  }
}