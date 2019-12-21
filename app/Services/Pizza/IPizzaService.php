<?php

declare(strict_types=1);

namespace App\Services\Pizza;

use App\PizzaType;

interface IPizzaService
{
    /**
     * Returns pizza by a slug
     *
     * @param string $slug
     * @return PizzaType|null
     */
    public function getBySlug(string $slug): ?PizzaType;
}
