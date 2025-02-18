<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('medium')->nullable();
            $table->string('trxno')->nullable();
            $table->float('amount', 10, 2)->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 => Request placed, 1 => Request Approve , 2 => Request not approved');
            $table->string('approve_by')->nullable();
            $table->timestamp('approve_time')->nullable();
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
        Schema::dropIfExists('recharge_requests');
    }
}
