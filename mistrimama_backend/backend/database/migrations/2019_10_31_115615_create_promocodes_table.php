<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('promocode');
            $table->float('cash',10,2)->default(0.00)->comment('Flat cash discount');
            $table->float('percent',10,2)->default(0.00)->comment('Discount upon total charge percentage');
            $table->float('up_to')->default(0.00)->comment('Up to 100 taka (applicabel for only percentage promos) ');
            $table->date('validity_date')->nullable();
            $table->integer('uses_count')->default(0)->comment('How many times an user can use this promo');
            $table->text('details')->comment('Promo details');
            $table->tinyInteger('status')->default(1)->comment('0 => This promo is not active , 1 => This promo is active and can be apply');
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
        Schema::dropIfExists('promocodes');
    }
}
