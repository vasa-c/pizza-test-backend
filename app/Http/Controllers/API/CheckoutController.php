<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\CheckoutRequest;
use App\ServiceContainer;
use Exception;
use Illuminate\Support\Facades\Log;

class CheckoutController extends APIController
{
    public function checkout(CheckoutRequest $request)
    {
        try {
            $result = ServiceContainer::orders()->checkout($request, $this->getCurrentUser());
        } catch (Exception $e) {
            Log::error('checkout: '.$e->getMessage());
            return response()->json([], 500);
        }
        return response()->json($result->responseData, $result->responseCode);
    }
}
