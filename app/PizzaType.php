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
     * Returns short data for list of pizza
     *
     * @return array
     */
    public function getShortData(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'photo' => url('assets/img/pizza/'.$this->slug.'.png'),
            'price' => $this->price,
        ];
    }

    /**
     * {@inheritdoc}}
     */
    public $timestamps = false;

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
