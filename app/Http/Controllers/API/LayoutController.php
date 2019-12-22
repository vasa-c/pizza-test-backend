<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\{
    ServiceContainer,
    Price
};

class LayoutController extends APIController
{
    public function layout()
    {
        $user = $this->getCurrentUser();
        return response()->json([
            'pizza_types' => ServiceContainer::pizza()->getDataForList(),
            'user' => $user ? $user->getDataForFrontend() : null,
            'currencies' => Price::getListCurrenciesForFrontend(),
            'csrf' => csrf_token(),
        ]);
    }
}
