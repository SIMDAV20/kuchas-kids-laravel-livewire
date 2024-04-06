<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
    DB::select('ALTER TABLE settings MODIFY COLUMN updated_at TIMESTAMP NULL DEFAULT null');
    DB::select('ALTER TABLE settings MODIFY COLUMN created_at TIMESTAMP NULL DEFAULT NULL');

    Schema::table('settings', function (Blueprint $table) {
      $table->string('logo')->nullable()->after('show_headband');
    });
  }

  /**s
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('settings', function (Blueprint $table) {
      //
    });
  }
};
