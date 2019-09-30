<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsDevicesToControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('controllers', function (Blueprint $table) {
            $table->tinyInteger('devices')->unsigned()->after('last_conn')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('controllers', function (Blueprint $table) {
            $table->dropColumn('devices');
        });
    }
}
