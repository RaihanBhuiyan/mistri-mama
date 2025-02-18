<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToQuickOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quick_orders', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0 => new quick order which not procced yet, 1 => quick order has been procced');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quick_orders', function (Blueprint $table) {
            //
        });
    }
}
