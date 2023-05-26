<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnvioInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $orders = Order::whereNotNull('envio')->get();
        foreach ($orders as $key => $order) {
            $order->envio = json_decode($order->envio);

            $new_envio = json_encode([
                'department' => $order->envio->department,
                'province'   => $order->envio->city ?: $order->envio->province,
                'district'   => $order->envio->district,
                'address'    => $order->envio->address,
                'references' => $order->envio->references,
            ]);

            $orderTwo = Order::find($order->id);
            $orderTwo->envio = $new_envio;
            $orderTwo->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
