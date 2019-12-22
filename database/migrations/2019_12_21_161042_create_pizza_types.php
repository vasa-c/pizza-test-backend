<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePizzaTypes extends Migration
{
    public function up()
    {
        Schema::create('pizza_types', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('slug', 50)->unique();
            $table->string('description', 4000)->default('');
            $table->decimal('price', 8, 2);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pizza_types');
    }
}
