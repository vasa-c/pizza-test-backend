<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\CheckoutRequest;
use App\ServiceContainer;
use Illuminate\Support\Facades\{
    Log,
    Auth
};
use Throwable;

class CheckoutController extends APIController
{
    public function checkout(CheckoutRequest $request)
    {
        $user = $this->getCurrentUser();
        try {
            $result = ServiceContainer::orders()->checkout($request, $user);
        } catch (Throwable $e) {
            Log::error('checkout: '.$e->getMessage());
            return response()->json([], 500);
        }
        if (($user === null) && ($result->isUserCreated())) {
            $user = $result->order->getUser();
            Auth::login($user, true);
        }
        return response()->json($result->responseData, $result->responseCode);
    }
}
