<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCameraControllerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camera_controller', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('camera_id');
            $table->unsignedInteger('controller_id');
            $table->unique(['camera_id', 'controller_id']);
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
        Schema::dropIfExists('camera_controller');
    }
}
