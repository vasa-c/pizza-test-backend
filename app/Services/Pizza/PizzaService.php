<?php

declare(strict_types=1);

namespace App\Services\Pizza;

use App\PizzaType;

class PizzaService implements IPizzaService
{
    /**
     * {@inheritdoc}
     */
    public function getBySlug(string $slug): ?PizzaType
    {
        return PizzaType::where('slug', $slug)->first();
    }
}
