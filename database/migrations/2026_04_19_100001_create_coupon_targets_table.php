<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponTargetsTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_targets', function (Blueprint $table) {
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->morphs('targetable'); // targetable_id + targetable_type
            $table->primary(['coupon_id', 'targetable_id', 'targetable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_targets');
    }
}
