<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('address');
            $table->string('name', 30)->default('Без имени');
            $table->string('sn', 20)->nullable()->unique();
            $table->string('type', 20)->default('Policont');
            $table->string('fw', 10)->nullable();
            $table->unsignedInteger('voltage')->nullable();
            $table->unsignedTinyInteger('alarm')->default(0);
            $table->unsignedTinyInteger('timeout')->default(0);
            $table->unsignedTinyInteger('sd_error')->default(0);
            $table->unsignedInteger('controller_id')->index();
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
        Schema::dropIfExists('devices');
    }
}
