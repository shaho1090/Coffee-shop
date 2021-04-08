<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'bail|required|string|email|max:150|exists:users,email',
            'password' => 'bail|required|string|min:6'
        ]);

        $user = User::where('email', $request->get('email'))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('userToken')->plainTextToken
        ]);
    }

    public function destroy(): JsonResponse
    {
        // request()->user()->currentAccessToken()->delete();

        request()->user()->tokens()->delete();

        return response()->json([
            'massage' => 'logged out successfully!'
        ]);
    }
}
