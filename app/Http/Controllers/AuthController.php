<?php

namespace App\Http\Controllers;

use App\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $authService
    ) {}

    private function jwtCookie(string $token)
    {
        return cookie(
            'jwt_token',
            $token,
            60 * 24 * 7,
            '/',
            'jwt-api2.local',
            true,
            true,
            false,
            'None'// samesite
        );
    }

    public function register(Request $request)
    {
        $data = $this->authService->register($request);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $data['user'],
        ], 201)->withCookie(
            $this->jwtCookie($data['token'])
        );
    }

    public function login(Request $request)
    {
        try {

            $data = $this->authService->login($request);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $data['user'],
            ])->withCookie(
                $this->jwtCookie($data['token'])
            );

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function profile()
    {
        return response()->json([
            'success' => true,
            'user' => auth('api')->user(),
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ])->withCookie(
            cookie()->forget(
                'jwt_token',
                '/',
                'jwt-api2.local'
            )
        );
    }
}
