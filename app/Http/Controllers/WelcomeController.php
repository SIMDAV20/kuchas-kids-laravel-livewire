<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use App\Models\Payment;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    // se usa para los controladores de unico metodo
    public function __invoke() {

        if (auth()->user()) {
            $orders = Order::where('status', 1)
                ->whereNull('payment_method')
                ->where('user_id', auth()->user()->id)
                ->get();
            // $payments = Payment::join('orders as o', 'o.id', 'payments.order_id')
            //     ->select('o.*')->where('payments.status', '!==', 1);
            $pendiente = $orders->count();
            if ($pendiente) {
                $mensaje = "Usted tiene $pendiente ordenes pendientes . <a class='font-bold' href='". route('orders.index')."?status=1'>Ir a pagar</a>";
                // generar el mensaje flash
                session()->flash('flash.banner', $mensaje);
            }
        }

        $categories = Category::orderBy('position', 'ASC')->get();

        return view('welcome', compact('categories'));
    }
}
