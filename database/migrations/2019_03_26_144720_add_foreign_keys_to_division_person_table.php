<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToDivisionPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('division_person', function (Blueprint $table) {
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->foreign('person_id')->references('id')->on('persons');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('division_person', function (Blueprint $table) {
            $table->dropForeign('division_person_division_id_foreign');
            $table->dropForeign('division_person_person_id_foreign');
        });
    }
}
