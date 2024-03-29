<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('categories', function (Blueprint $table) {
      $table->boolean('status')->default(1)->after('position');
    });

    Schema::table('subcategories', function (Blueprint $table) {
      $table->boolean('status')->default(1)->after('position');
    });
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
};
