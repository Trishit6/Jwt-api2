<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface AuthServiceInterface
{
    public function register(Request $request);

    public function login(Request $request);

    public function profile();

    public function logout();
}
