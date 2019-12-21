<?php

declare(strict_types=1);

namespace App\Services\Pizza;

use App\{
    PizzaType,
    OrderItem
};

class PizzaService implements IPizzaService
{
    /**
     * {@inheritdoc}
     */
    public function getBySlug(string $slug): ?PizzaType
    {
        return PizzaType::where('slug', $slug)->first();
    }

    /**
     * {@inheritdoc}
     */
    public function getDataForList(): array
    {
        $result = [];
        /** @var PizzaType[] $types */
        $types = PizzaType::orderBy('name', 'asc')->get();
        foreach ($types as $type) {
            $result[] = $type->getShortData();
        }
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function parseCart(array $data): ?array
    {
        $result = [];
        foreach ($data as $slug => $count) {
            $pizza = $this->getBySlug($slug);
            if ($pizza === null) {
                return null;
            }
            $item = new OrderItem();
            $item->setPizza($pizza, $count);
            $result[$slug] = $item;
        }
        return $result;
    }
}
