<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_systems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->bigInteger('service_provider_id');
            $table->bigInteger('service_provider_comrad_id')->nullable();
            $table->tinyInteger('state')->defult(0)->comment('0=> SP accept the order, 1=>  Comrade allowcated , 2=> Comrade start work , 3=> Comrade finish work wait for payment, 4 => Payment recived and completely done the project');
            $table->tinyInteger('user_rating');
            $table->tinyInteger('sp_rating');
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
        Schema::dropIfExists('order_systems');
    }
}
