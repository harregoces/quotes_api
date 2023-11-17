<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
class UserController extends BaseController
{
    use ValidatesRequests;

    /**
     * Api endpoint /api/login
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => auth()->user(),
            'token' => $token
        ], 200);
    }

    /**
     * Api endpoint /api/register
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ]);
        $credentials['password'] = bcrypt($credentials['password']);
        $user = \App\Models\User::create($credentials);
        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
