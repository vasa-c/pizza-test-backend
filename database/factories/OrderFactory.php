<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Order;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'user_name' => $faker->name,
        'address' => $faker->address,
        'contacts' => $faker->phoneNumber,
        'currency' => 'eur',
        'delivery_price' => 0,
        'total_price' => 10,
        'status' => 'created',
        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    ];
});
