<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Esquema reconstruido a partir de la tabla real en producción (esta migración tenía el
        // Schema::create comentado, así que nunca creó la tabla — ver docs/incidencias.md).
        // Claves ubigeo (string), sin timestamps (District::$timestamps = false), sin FK a
        // provinces/departments (solo se guarda el código como referencia).
        Schema::create('districts', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('name', 45)->nullable();
            $table->string('province_id', 4)->nullable();
            $table->string('department_id', 2)->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('districts');
    }
}
