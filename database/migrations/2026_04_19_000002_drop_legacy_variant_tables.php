<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropLegacyVariantTables extends Migration
{
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('color_product_size');
        Schema::dropIfExists('age_product');
        Schema::dropIfExists('age_categories');
        Schema::dropIfExists('color_product');
        Schema::dropIfExists('product_size');
        Schema::dropIfExists('ages');
        Schema::dropIfExists('colors');

        Schema::enableForeignKeyConstraints();
    }

    public function down()
    {
        // Tablas legacy — no se restauran
    }
}
