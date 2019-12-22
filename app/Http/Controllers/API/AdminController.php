<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Order;

class AdminController extends APIController
{
    public function admin()
    {
        $orders = [];
        foreach (Order::orderBy('id', 'desc')->get() as $order) {
            /** @var Order $order */
            $orders[] = $order->getDataForList();
        }
        return response()->json([
            'orders' => $orders,
        ]);
    }
}
