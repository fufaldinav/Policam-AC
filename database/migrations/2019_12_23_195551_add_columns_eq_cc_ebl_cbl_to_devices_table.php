<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsEqCcEblCblToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedInteger('events_queue')->after('sd_error')->default(0);
            $table->unsignedInteger('cards_count')->after('events_queue')->default(0);
            $table->unsignedInteger('events_bl')->after('cards_count')->default(0);
            $table->unsignedInteger('cards_bl')->after('events_bl')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('cards_bl');
            $table->dropColumn('events_bl');
            $table->dropColumn('cards_count');
            $table->dropColumn('events_queue');
        });
    }
}
