<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no')->index();
            $table->bigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->bigInteger('user_id')->nullable()->comment('For guest order user_id would be zero');
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('name')->index()->nullable();
            $table->string('phone')->index()->nullable();
            $table->string('area')->index()->nullable();
            $table->text('address')->nullable();
            $table->text('location')->nullable();
            $table->float('extra_charge', 10, 2)->default(0.00);
            $table->float('discount', 10, 2)->default(0.00);
            $table->tinyInteger('status')->nullable()->default(0)->comment('0 => Order Placed , 1 => Order accepted , 2 => Order allowcated to SP, 3 => Order Finished (Served) wait for payment , 4 => Order Payment done (order completely finished) , 5 => Order Cancel by user (orderar)');
            $table->tinyInteger('pay_type')->nullable()->comment('0 => Default Cash (auto colect by sp) , 1 => Cash Payment (select from orderer) , 2 => SureCash (select from orderer), 3 => Card (SSL Commerze -select from orderer)');
            $table->timestamp('accept_time')->nullable(); // when order is accepted
            $table->timestamp('allowcate_time')->nullable(); // first allowcate time
            $table->timestamp('finish_time')->nullable(); // when order served and mark as finish
            $table->timestamp('cancel_time')->nullable(); // when order is finished
            $table->string('pay_status')->default(null)->nullable()->comment('payment done =>Success , Work done wait for payment => Pending'); // when order system state is 3 then it will be pending
            $table->enum('order_for', ['others', 'self'])->default(null)->nullable();
            $table->text('comments')->default(null)->nullable();
            $table->string('order_from')->nullable()->comment('From which panel the order came from');
            $table->string('ref_code')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
