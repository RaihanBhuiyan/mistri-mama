<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->integer('service_id');
            $table->integer('service_name');
            $table->integer('service_bit_id');
            $table->string('service_bit_name');
            $table->integer('quantity')->default(0);
            $table->float('additional_price', 15, 2);
            $table->float('price', 15, 2)->default(0.00);
            $table->float('total_price', 15, 2);
            $table->tinyInteger('status')->default(0)->comment('Individual bit completion status: 0 => Not Done, 1 => Done');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
