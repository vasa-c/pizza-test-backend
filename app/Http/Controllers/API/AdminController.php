<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\{
    Order,
    ServiceContainer
};

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

    public function order(int $number)
    {
        $order = ServiceContainer::orders()->getByNumber($number);
        if ($order === null) {
            return $this->error404();
        }
        return response()->json([
            'order' => $order->getDataForPage(),
        ]);
    }
}
