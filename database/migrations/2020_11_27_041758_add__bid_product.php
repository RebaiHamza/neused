<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBidProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BidProduct', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->index('carts_user_id_foreign');
            $table->integer('pro_id')->unsigned()->nullable()->index('carts_pro_id_foreign');
            $table->integer('variant_id')->nullable();
            $table->float('price_total', 10, 0)->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('BidProduct', function (Blueprint $table) {
            Schema::drop('BidProduct');
        });
    }
}
