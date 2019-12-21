<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\ServiceContainer;

class PizzaController extends APIController
{
    public function pizza(string $slug)
    {
        $pizza = ServiceContainer::pizza()->getBySlug($slug);
        if ($pizza === null) {
            return $this->error404();
        }
        return response()->json([
            'pizza' => $pizza->getFullData(),
        ]);
    }
}
