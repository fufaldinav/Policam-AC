<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysDeviceIdDivisionIdToDeviceDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_division', function (Blueprint $table) {
            $table->foreign('device_id')->references('id')->on('devices');
            $table->foreign('division_id')->references('id')->on('divisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_division', function (Blueprint $table) {
            $table->dropForeign('device_division_device_id_foreign');
            $table->dropForeign('device_division_division_id_foreign');
        });
    }
}
