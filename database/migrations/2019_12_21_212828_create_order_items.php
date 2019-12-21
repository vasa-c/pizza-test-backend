<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItems extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('pizza_type_id')->unsigned()->nullable();
            $table->smallInteger('count')->unsigned();
            $table->string('currency');
            $table->decimal('total_price', 12, 4)->unsigned();
            $table->decimal('item_price', 12, 4)->unsigned();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->foreign('pizza_type_id')
                ->references('id')
                ->on('pizza_types')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
