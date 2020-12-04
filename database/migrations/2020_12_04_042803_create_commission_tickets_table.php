<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_tickets', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('ticket_id')->unsigned();
			$table->string('rate', 191)->nullable();
			$table->enum('type', array('p','f'));
			$table->enum('status', array('0','1'));
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
        Schema::dropIfExists('commission_tickets');
    }
}
