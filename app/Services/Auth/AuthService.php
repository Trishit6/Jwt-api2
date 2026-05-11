<?php

namespace App\Services\Auth;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    private function jwtCookie(string $token)
    {
        return cookie(
            'jwt_token',
            $token,
            60,     // minutes
            '/',    // path
            null,   // domain
            true,   // secure -> false on local http
            true,   // httpOnly
            false,  // raw
            'Strict'
        );
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201)->withCookie($this->jwtCookie($token));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);

        if (! $token) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'user' => JWTAuth::user(),
        ])->withCookie($this->jwtCookie($token));
    }

    public function profile()
    {
        return response()->json([
            'user' => JWTAuth::user(),
        ]);
    }

    public function logout()
    {
        if ($token = JWTAuth::getToken()) {
            JWTAuth::invalidate($token);
        }

        return response()->json([
            'message' => 'Successfully logged out',
        ])->withCookie(cookie()->forget('jwt_token'));
    }
}
