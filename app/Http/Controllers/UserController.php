<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    use ValidatesRequests;

    /**
     * Api endpoint /api/login
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $this->validate($request, [
                'email' => 'required|email:rfc,dns',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('authToken')->plainTextToken;

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Api endpoint /api/logout
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

    /**
     * Api endpoint /api/register
     * Type: POST
     * Description: Returns a token for the authenticated user
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|unique:users|email:rfc,dns',
                'password' => 'required',
                'password_confirmation' => 'required|same:password'
            ]);

            $password = bcrypt($request->password);
            $user = \App\Models\User::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => $password
                ]
            );
            $token = $user->createToken('authToken')->plainTextToken;

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
