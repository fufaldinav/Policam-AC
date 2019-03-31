<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToCameraControllerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camera_controller', function (Blueprint $table) {
            $table->foreign('camera_id')->references('id')->on('cameras');
            $table->foreign('controller_id')->references('id')->on('controllers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camera_controller', function (Blueprint $table) {
            $table->dropForeign('camera_controller_camera_id_foreign');
            $table->dropForeign('camera_controller_controller_id_foreign');
        });
    }
}
