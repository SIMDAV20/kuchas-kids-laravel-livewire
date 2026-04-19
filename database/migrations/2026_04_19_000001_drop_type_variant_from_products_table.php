<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTypeVariantFromProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type_variant');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type_variant')->after('slug')->default('base');
        });
    }
}
