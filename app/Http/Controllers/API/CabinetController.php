<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\CheckoutRequest;
use App\ServiceContainer;
use Illuminate\Support\Facades\{
    Log,
    Auth
};
use Exception;

class CabinetController extends APIController
{
    public function cabinet()
    {
        $orders = [];
        foreach ($this->getCurrentUser()->getOrders() as $order) {
            $orders[] = $order->getDataForList();
        }
        return response()->json([
            'orders' => $orders,
        ]);
    }
}
