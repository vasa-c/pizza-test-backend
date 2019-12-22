<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\{
    Order,
    ServiceContainer
};
use App\Http\Requests\AdminChangeStatusRequest;

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

    public function changeStatus(AdminChangeStatusRequest $request, int $number)
    {
        $order = ServiceContainer::orders()->getByNumber($number);
        if ($order === null) {
            return $this->error404();
        }
        switch ($request->status) {
            case 'delivery':
                $result = $order->toDelivery();
                break;
            case 'success':
                $result = $order->toSuccess();
                break;
            case 'fail':
                $result = $order->toFail();
                break;
        }
        if (!$result) {
            return $this->errorRequest();
        }
        return response()->json([
            'order' => $order->getDataForPage(),
        ]);
    }
}
