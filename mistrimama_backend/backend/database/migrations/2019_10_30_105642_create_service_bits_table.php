<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceBitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_bits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('service_id');
            $table->string('name');
            $table->float('price', 10, 2)->default(0.00);
            $table->float('additional_price', 10, 2)->default(0.00);
            $table->tinyInteger('unit_remarks')->nullable();
            $table->tinyInteger('additional_unit_remarks')->nullable();
            $table->string('unit_type')->nullable();
            $table->mediumText('brief')->nullable();
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_bits');
    }
}
