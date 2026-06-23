<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
             $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function login(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
        ]);
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        // ]);

        // try {
        //     $user = User::where('email', $request->email)->first();

        //     if (!$user || !Hash::check($request->password, $user->password)) {
        //         return response()->json(['message' => 'Invalid credentials'], 401);
        //     }

        //     $token = $user->createToken('auth_token')->plainTextToken;

        //     return response()->json([
        //         'success' => true,
        //         'data' => [
        //             'user' => $user,
        //             'token' => $token,
        //         ],
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Login failed: ' . $e->getMessage(),
        //     ], 500);
        // }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout: ' . $e->getMessage(),
            ], 500);
        }
        // $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
