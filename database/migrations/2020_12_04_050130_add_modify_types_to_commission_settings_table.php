<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModifyTypesToCommissionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commission_settings', function (Blueprint $table) {
            DB::statement("ALTER TABLE commission_settings MODIFY COLUMN type ENUM ('c', 'flat', 'n', 's', 'u', 't', 'b') NOT NULL DEFAULT 'flat'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commission_settings', function (Blueprint $table) {
            //
        });
    }
}
