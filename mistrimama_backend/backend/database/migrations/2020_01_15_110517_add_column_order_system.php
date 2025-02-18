<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrderSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_systems', function (Blueprint $table) {
            $table->string('sp_cat')->nullable();
            $table->float('commission', 5, 2)->default(0)->comment('Mistrimama Commission from Service Provider');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_systems', function (Blueprint $table) {
            //
        });
    }
}
