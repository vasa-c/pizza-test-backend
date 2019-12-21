<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('number')->default(0);
            $table->bigInteger('user_id')->unsigned()->nullable(); // NULL if user was deleted
            $table->string('email');
            $table->string('address');
            $table->string('contacts');
            $table->string('currency');
            $table->decimal('delivery_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->string('status')->default('created');
            $table->timestamp('created_at');
            $table->timestamp('finalized_at')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null'); // user was deleted but order stayed in DB
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
