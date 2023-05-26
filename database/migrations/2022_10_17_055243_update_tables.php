<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE departments RENAME old_departments");
        DB::statement("ALTER TABLE districts RENAME old_districts");
        DB::statement("ALTER TABLE cities RENAME old_cities");

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::statement("DROP TABLE old_departments");
        DB::statement("DROP TABLE old_districts");
        DB::statement("DROP TABLE old_cities");
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        $path = base_path() . '/ubigeo2016.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        DB::statement("ALTER TABLE districts ADD COLUMN zone_id bigint(20) unsigned");
        DB::statement("ALTER TABLE districts ADD CONSTRAINT districts_zone_id_foreign FOREIGN KEY 
        (zone_id) REFERENCES zones(id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
