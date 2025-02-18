<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('sp_code');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->text('address')->nullable();
            $table->string('mfs_type')->nullable();
            $table->text('mfs_no')->nullable();
            $table->text('photo')->nullable();
            $table->string('nid_no');
            $table->text('nid_front')->nullable();
            $table->text('nid_back')->nullable();
            $table->longText('others_doc')->nullable()->comment('Business TIN certificate , Trade Liences etc');
            $table->string('alt_phone')->nullable()->comment('Alternative Phone No');
            $table->tinyInteger('status')->default(0)->comment('1 => Active , 0 => Not Active');
            $table->enum('type', ['esp', 'fsp']);
            $table->enum('category', ['starter', 'expert', 'professional']);
            $table->string('addedBy')->comment('If value is zero(0) thats mean self or guest else user_id (managments)');
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('service_providers');
    }
}
