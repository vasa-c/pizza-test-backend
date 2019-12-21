<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class AuthController extends APIController
{
    public function login(LoginRequest $request)
    {
        $user = $this->getCurrentUser();
        if ($user === null) {
            $this->attemptLogin($request->email, $request->password);
        }
        $user = $this->getCurrentUser();
        return response()->json([
            'user' => $user ? $user->getDataForFrontend() : null,
        ]);
    }

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

    /**
     * @param string $email
     * @param string $password
     */
    private function attemptLogin(string $email, string $password): void
    {
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];
        Auth::guard()->attempt($credentials, true);
    }
}
