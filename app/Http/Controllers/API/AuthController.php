<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AuthController extends APIController
{
    public function logout(Request $request)
    {
        if ($this->getCurrentUser()) {
            Auth::guard()->logout();
            try {
                $request->session()->invalidate();
            } catch (RuntimeException $e) {
                // @todo log
            }
        }
        return response()->json([]);
    }
}
