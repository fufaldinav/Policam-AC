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
            $table->string('name', 20)->default('Без имени');
            $table->string('sn', 20)->unique();
            $table->string('type', 20)->default('Z5RWEB');
            $table->string('fw', 10)->nullable();
            $table->string('conn_fw', 10)->nullable();
            $table->unsignedTinyInteger('mode')->default(0);
            $table->string('ip', 15)->nullable();
            $table->unsignedTinyInteger('active')->default(1);
            $table->unsignedTinyInteger('online')->default(0);
            $table->dateTime('last_conn')->nullable();
            $table->unsignedInteger('organization_id')->default(0)->index();
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
