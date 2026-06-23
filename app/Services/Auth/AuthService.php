<?php

namespace App\Services\Auth;

use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (! $token = JWTAuth::attempt($validated)) {

            throw new \Exception('Invalid credentials');
        }

        return [
            'token' => $token,
            'user' => auth('api')->user(),
        ];
    }

    public function profile()
    {
        return auth('api')->user();
    }

    public function logout()
    {
        if ($token = JWTAuth::getToken()) {
            JWTAuth::invalidate($token);
        }

        return true;
    }
}
