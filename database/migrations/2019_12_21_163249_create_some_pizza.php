<?php

use Illuminate\Database\Migrations\Migration;
use App\PizzaType;

/**
 * https://www.webstaurantstore.com/article/101/types-of-pizza.html
 */
class CreateSomePizza extends Migration
{
    /**
     * @var string[]
     */
    private $types = [
        'Neapolitan' => 'Neapolitan is the original pizza. This delicious pie dates all the way back to 18th century in Naples, Italy.',
        'Chicago' => 'Chicago pizza, also commonly referred to as deep-dish pizza, gets its name from the city it was invented in.',
        'New York-Style' => 'With its characteristic large, foldable slices and crispy outer crust, New York-style pizza is one of America’s most famous regional pizza types',
        'Sicilian' => 'Sicilian pizza, also known as "sfincione," provides a thick cut of pizza with pillowy dough, a crunchy crust, and robust tomato sauce',
        'Greek' => 'Greek pizza was created by Greek immigrants who came to America and were introduced to Italian pizza',
        'California' => 'California pizza, or gourmet pizza, is known for its unusual ingredients',
        'Detroit' => 'Reflecting the city’s deep ties to the auto industry, Detroit-style pizza was originally baked in a square automotive parts pan in the 1940’s',
        'St. Louis' => 'Looking for a light slice? St. Louis pizza features a thin crust with a cracker-like consistency that is made without yeast',
    ];

    public function up()
    {
        $price = 31.99;
        foreach ($this->types as $name => $description) {
            $slug = strtolower($name);
            $slug = preg_replace('/[^a-z]+/', '-', $slug);
            $pizza = new PizzaType();
            $pizza->name = $name;
            $pizza->slug = $slug;
            $pizza->description = $description;
            $price -= 2;
            $pizza->price = $price;
            $pizza->save();
        }
    }

    public function down()
    {
        PizzaType::truncate();
    }
}
