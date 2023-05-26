<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Order;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->enum('status', [
                Order::PENDIENTE,
                Order::RECIBIDO,
                Order::ENVIADO,
                Order::ENTREGADO,
                Order::ANULADO
            ])->default(Order::PENDIENTE);

            $table->enum('envio_type', [1, 2, 3]); // 1 recojo en tienda, 2 a su domicilio, 3 otro destino

            $table->float('shipping_cost');
            $table->float('total');

            $table->json('content'); // se guarda en ese momento como estuvo los productos en el tiempo

            $table->string('contact');
            $table->string('phone');
            $table->string('doc_number');

            $table->string('other_contact')->nullable();
            $table->string('other_phone')->nullable();
            $table->string('other_doc_number')->nullable();

            $table->text('extra_note')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->json('envio')->nullable();

            $table->enum('payment_method', [1, 2])->nullable(); // 1 es IZIPAY, // 2 YAPE

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
        Schema::dropIfExists('orders');
    }
}
