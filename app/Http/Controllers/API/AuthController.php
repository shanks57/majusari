<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => 'Login succesfull',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully', "code" => 200]);
    }

    public function getUserLogged(Request $request)
    {
        try {
            $user = $request->user();
            $roles = $user->getRoleNames();

            return response()->json([
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'debt_receipt' => $user->debt_receipt,
                    'address' => $user->address,
                    'status' => $user->status,
                    'roles' => $roles
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching user data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to retrieve user data'
            ], 500);
        }
    }
}
