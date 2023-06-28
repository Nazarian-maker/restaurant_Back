<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('dish_id');
            $table->unsignedSmallInteger('order_id');
            $table->unsignedSmallInteger('count');

            $table->index('dish_id', "dish_order_dish_idx");
            $table->index('order_id', "dish_order_order_idx");

            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_orders');
    }
}
