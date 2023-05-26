<?php

use App\Models\Payment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [
                Payment::APROBADO,
                Payment::PENDIENTE,
                Payment::RECHAZADO,
                Payment::ANULADO,
            ])->default(Payment::APROBADO);

            $table->decimal('amount', 10,2);
            $table->string('mp_payment_id')->nullable();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('order_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
