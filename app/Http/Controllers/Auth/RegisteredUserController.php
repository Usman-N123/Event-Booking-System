<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserCreateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\DTOs\Auth\UserCreateDTO;
use App\Features\Auth\RegisterUserFeature;

class RegisteredUserController extends Controller
{
  public function __construct(
    protected RegisterUserFeature $registerUserFeature
  ) {}

  public function create(): View
  {
    return view('auth.register');
  }

  public function store(UserCreateRequest $request): RedirectResponse
  {
    $dto = UserCreateDTO::fromRequest($request);
    
    $user = $this->registerUserFeature->handle($dto);

    Auth::login($user);

    if ($user->role->value === 'organizer') {
      return redirect()->route('organizer.dashboard');
    }

    return redirect()->route('attendee.dashboard');
  }
}