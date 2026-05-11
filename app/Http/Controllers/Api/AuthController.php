<?php

namespace App\Http\Controllers\Api;

use App\Facades\AuthFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        return AuthFacade::register($request);
    }

    public function login(Request $request)
    {
        return AuthFacade::login($request);
    }

    public function profile()
    {
        return AuthFacade::profile();
    }

    public function logout()
    {
        return AuthFacade::logout();
    }
}
