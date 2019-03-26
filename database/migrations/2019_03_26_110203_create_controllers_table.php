<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('controllers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20);
            $table->string('sn', 20)->unique();
            $table->string('type', 20);
            $table->string('fw', 10);
            $table->string('conn_fw', 10);
            $table->unsignedTinyInteger('mode');
            $table->string('ip', 15);
            $table->unsignedTinyInteger('active');
            $table->unsignedTinyInteger('online');
            $table->dateTime('last_conn');
            $table->unsignedInteger('organization_id')->index();
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
        Schema::dropIfExists('controllers');
    }
}
