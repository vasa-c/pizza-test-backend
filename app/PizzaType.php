<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 */
class PizzaType extends Model
{
    /**
     * {@inheritdoc}}
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
    ];

    /**
     * {@inheritdoc}}
     */
    protected $casts = [
        'price' => 'float',
    ];
}
