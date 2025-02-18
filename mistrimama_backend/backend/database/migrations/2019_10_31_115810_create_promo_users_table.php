<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //User can save promo code for future use or user can add promo code instantly.
        Schema::create('promo_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable()->comment('If user login then user_id will be store else user_id will be zero');
            $table->string('phone')->nullable();
            $table->string('promocode');
            $table->tinyInteger('uses_count')->default(0)->comment('How many times a user use this promo');
            $table->tinyInteger('uses_status')->default(0)->comment('0 => this code can be use more time by the user, 1 => this code can not be use anymore');
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
        Schema::dropIfExists('promo_users');
    }
}
