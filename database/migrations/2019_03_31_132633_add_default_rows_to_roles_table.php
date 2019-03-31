<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultRowsToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')->insert([
            ['name' => 'Администратор', 'type' => 1],
            ['name' => 'Директор', 'type' => 2],
            ['name' => 'Секретарь', 'type' => 3],
            ['name' => 'Родитель', 'type' => 4],
            ['name' => 'Ученик', 'type' => 5],
            ['name' => 'Охранник', 'type' => 6],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->whereIn('name', [
            'Администратор',
            'Директор',
            'Секретарь',
            'Родитель',
            'Ученик',
            'Охранник',
        ])->delete();
    }
}
