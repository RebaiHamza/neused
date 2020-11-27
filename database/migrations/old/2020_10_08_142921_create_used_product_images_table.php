<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_product_images', function (Blueprint $table) {
            $table->increments('id');
			$table->string('image1', 191)->nullable();
			$table->string('image2', 191)->nullable();
			$table->string('image3', 191)->nullable();
			$table->string('image4', 191)->nullable();
			$table->string('image5', 191)->nullable();
			$table->string('image6', 191)->nullable();
			$table->string('main_image', 191)->nullable();
			$table->integer('pro_id')->unsigned()->nullable();
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
        Schema::dropIfExists('used_product_images');
    }
}
