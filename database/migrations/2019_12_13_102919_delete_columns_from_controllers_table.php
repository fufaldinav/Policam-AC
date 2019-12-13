<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsFromControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('controllers', function (Blueprint $table) {
            $table->dropColumn('devices_voltage');
            $table->dropColumn('devices_status');
            $table->dropColumn('devices');
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
            $table->tinyInteger('devices')->unsigned()->after('last_conn')->default(1);
            $table->json('devices_status')->after('devices')->nullable();
            $table->json('devices_voltage')->after('devices')->nullable();
        });
    }
}
