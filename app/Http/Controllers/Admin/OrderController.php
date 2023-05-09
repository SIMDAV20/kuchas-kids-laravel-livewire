<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function show(Order $order) {
        return view('admin.orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::query();
        $status = 0;
        if (request('status')) { // si existe x la url el param status
            // $orders->where('status', request('status'));
            $status = request('status');
            if($status > 5 || $status < 1) {
                $status = 0;
            }
        }

        $pendiente = Order::where('status', 1)->count();
        $recibido  = Order::where('status', 2)->count();
        $enviado   = Order::where('status', 3)->count();
        $entregado = Order::where('status', 4)->count();
        $anulado   = Order::where('status', 5)->count();

        return view('admin.orders.index', compact('status', 'pendiente', 'recibido', 'enviado', 'pendiente', 'entregado', 'anulado'));
    }
}
