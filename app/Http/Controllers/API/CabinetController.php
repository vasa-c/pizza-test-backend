<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\ServiceContainer;

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

    public function order(int $number)
    {
        $order = ServiceContainer::orders()->getByNumber($number);
        if ($order === null) {
            return $this->error404();
        }
        if ($order->user_id !== $this->getCurrentUser()->id) {
            return $this->error404(); // not 403 against brute
        }
        return response()->json([
            'order' => $order->getDataForPage(),
        ]);
    }
}
