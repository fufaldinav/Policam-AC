<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsEqMqEblMblAlarmSdErrorToControllersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('controllers', function (Blueprint $table) {
            $table->unsignedInteger('events_queue')->default(0)->after('organization_id');
            $table->unsignedInteger('messages_queue')->default(0)->after('events_queue');
            $table->unsignedInteger('events_bl')->default(0)->after('messages_queue');
            $table->unsignedInteger('messages_bl')->default(0)->after('events_bl');
            $table->unsignedTinyInteger('alarm')->default(0)->after('organization_id');
            $table->unsignedTinyInteger('sd_error')->default(0)->after('alarm');
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
            $table->dropColumn('events_queue');
            $table->dropColumn('messages_queue');
            $table->dropColumn('events_bl');
            $table->dropColumn('messages_bl');
            $table->dropColumn('alarm');
            $table->dropColumn('sd_error');
        });
    }
}
