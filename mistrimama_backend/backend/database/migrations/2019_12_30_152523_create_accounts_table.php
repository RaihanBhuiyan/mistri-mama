<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->default(0)->nullable();
            $table->float('amount', 10, 2)->default(0)->nullable();
            $table->string('trxno')->nullable();
            $table->string('type')->nullable();
            $table->string('heading')->nullable();
            $table->string('details')->nullable();
            $table->string('ref')->nullable()->comment('order,recharge,withdraw,offer,promotion etc');
            $table->string('ref_key')->nullable()->comment('order_id,withdraw_id,recharge_id etc');
            $table->enum('status', ['debit', 'credit', 'income'])->nullable();
            $table->date('date')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
