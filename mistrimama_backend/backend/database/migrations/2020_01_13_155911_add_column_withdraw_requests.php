<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWithdrawRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('mfs')->nullable();
            $table->string('mfs_number')->nullable();
            $table->float('amount', 10, 2)->default(0.00);
            $table->tinyInteger('status')->default(0)->comment('0 => is not approve , 1 => for approve ');
            $table->string('remarks')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdraw_requests', function (Blueprint $table) {
            //
        });
    }
}
