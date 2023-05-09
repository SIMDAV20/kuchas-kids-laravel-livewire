<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Http\Controllers\MailController;
use App\Mail\MessageRecieved;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class WebhooksController extends Controller
{
    // como tiene q recibir un token se tiene q hacer una excepcion en el verifyCsrfToken del middleware
    // public function index(Order $order, Request $request) {

    //     $payment_id = $request->get('payment_id');

    //     $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id"."?access_token=APP_USR-7619090548261657-021123-f56511b1fb376a2d0be49fb4f8226646-1072979634");

    //     $response = json_decode($response);

    //     $status = $response->status; // devuelve una cadena

    //     if ($order) {
    //         $payment = new Payment();
    //         $payment->amount        = $order->total;
    //         $payment->mp_payment_id = $payment_id;
    //         $payment->user_id       = auth()->user()->id;
    //         $payment->order_id      = $order->id;
    //     } else {
    //         $payment = Payment::find('mp_payment_id', $payment_id);
    //         $order = $payment->order;
    //     }

    //     $order->payment_method = 1;  // 1 es mercado pago, 2 yape

    //     if ($status == 'approved') {
    //         $order->status   = 2;
    //         $payment->status = 1;
    //         $mensaje = "Gracias por comprar en KuchasKids";
    //     } else if ($status == 'in_process') {
    //         $payment->status = 2;
    //         $mensaje = "El pago esta en proceso de aprobación, Gracias por comprar en KuchasKids";
    //     } else if ($status == 'rejected') {
    //         $payment->status = 3;
    //         $order->status   = 5;
    //         $mensaje = "El pago a sido rechazado! comuníquese con nosotros";
    //     }
    //     $order->save();
    //     $payment->save();

    //     session()->flash('flash.banner', $mensaje);

    //     $data = [
    //         'order' => $order
    //     ];

    //     Mail::to('atencionalclientekuchaskids@gmail.com')
    //         ->queue(new MessageRecieved($data,
    //             'Nueva Venta Página web Kuchas Kids'));

    //     return redirect()->route('orders.show', $order);
    // }


}
