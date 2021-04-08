<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'bail|required|string|min:3|max:150',
            'email' => 'bail|required|string|email|max:150|unique:users',
            'password' => 'bail|required|string|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role_id' => Role::customer()->first()->id
        ]);

        Auth::login($user);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('userToken')->plainTextToken
        ]);
    }
}
