<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\ServiceContainer;
use Illuminate\Support\Facades\Log;

class CabinetController extends APIController
{
    public function cabinet()
    {
        $orders = [];
        $user = $this->getCurrentUser();
        Log::info('Cabinet '.$user->email);
        foreach ($user->getOrders() as $order) {
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
        Log::info('Cabinet order '.$order->number);
        return response()->json([
            'order' => $order->getDataForPage(),
        ]);
    }
}
