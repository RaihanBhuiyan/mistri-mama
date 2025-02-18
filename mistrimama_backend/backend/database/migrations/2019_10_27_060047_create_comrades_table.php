<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comrades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('service_provider_id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->text('photo')->nullable();
            $table->string('nid_no');
            $table->text('nid_front')->nullable();
            $table->text('nid_back')->nullable();
            $table->text('services');
            $table->tinyInteger('status')->default('1')->comment('0 => Not Active 1=> Active');
            $table->tinyInteger('approve')->default('0')->comment('0 => No 1=> Yes');
            $table->string('approveBy')->nullable()->comment('If value is zero(0) thats mean self or guest else user_id (managments)');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_provider_id')->references('id')->on('service_providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comrades');
    }
}
