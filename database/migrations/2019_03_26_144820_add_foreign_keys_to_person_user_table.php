<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPersonUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person_user', function (Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('persons');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('person_user', function (Blueprint $table) {
            $table->dropForeign('person_user_organization_id_foreign');
            $table->dropForeign('person_user_user_id_foreign');
        });
    }
}
