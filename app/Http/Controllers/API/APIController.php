<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\User;

abstract class APIController extends Controller
{
    /**
     * @return JsonResponse
     */
    protected function error404(): JsonResponse
    {
        return response()->json([], 404);
    }

    /**
     * @return JsonResponse
     */
    protected function forbidden(): JsonResponse
    {
        return response()->json([], 403);
    }

    /**
     * @param array $errors [optional]
     * @return JsonResponse
     */
    protected function errorRequest(array $errors = []): JsonResponse
    {
        return response()->json(['errors' => $errors], 422);
    }

    /**
     * @return User|null
     */
    protected function getCurrentUser(): ?User
    {
        return Auth::user();
    }
}
