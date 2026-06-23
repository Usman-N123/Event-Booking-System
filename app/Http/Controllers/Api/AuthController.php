<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate the Sanctum Token
        $token = $user->createToken('frontend-client')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'role' => $user->role->value
            ]
        ], 200);
    }
}