<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Models\Payment;
use App\Http\Controllers\MailController;
use App\Mail\MessageRecieved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {

        $orders = Order::query()->where('user_id', auth()->user()->id);

        if (request('status')) { // si existe x la url el param status
            $orders->where('status', request('status'));
        }

        $orders = $orders->orderBy('id', 'DESC')->get();

        $pendiente = Order::where('status', 1)->where('user_id', auth()->user()->id)->count();
        $recibido  = Order::where('status', 2)->where('user_id', auth()->user()->id)->count();
        $enviado   = Order::where('status', 3)->where('user_id', auth()->user()->id)->count();
        $entregado = Order::where('status', 4)->where('user_id', auth()->user()->id)->count();
        $anulado   = Order::where('status', 5)->where('user_id', auth()->user()->id)->count();


        return view('orders.index', compact('orders', 'pendiente', 'recibido', 'enviado', 'entregado', 'anulado'));
    }

    public function show(Order $order)
    {

        $this->authorize('author', $order);
        $items = json_decode($order->content); // es como un json_parse en js
        $envio = json_decode($order->envio);
        return view('orders.show', compact('order', 'items', 'envio'));
    }

    public function annuled(Order $order, Request $request)
    {
        $this->authorize('author', $order);

        DB::beginTransaction();
        try {
            $order->status = ORDER::ANULADO;
            $order->observation = $request->comment;
            $order->save();

            $items = json_decode($order->content);

            foreach ($items as $item) {
                increase($item);
            }

            $payment = Payment::where('order_id', $order->id)->first();

            if ($payment) {
                $payment->status = Payment::ANULADO;
                $payment->save();
            }

            DB::commit();
            return 'ok';
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $th->getMessage();
        }
    }

    // metodo solo para el desarrollo
    public function pay(Order $order, Request $request)
    {

        $this->authorize('author', $order);
        //https://www.mercadopago.com.pe/developers/es/reference/payments/_payments_id/get

        $payment_id = $request->get('payment_id');

        $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id" . "?access_token=" . config('services.mercadopago.token'));

        $response = json_decode($response);

        $status = $response->status; // devuelve una cade   na

        if ($order) {
            $payment = new Payment();
            $payment->amount        = $order->total;
            $payment->mp_payment_id = $payment_id;
            $payment->user_id       = auth()->user()->id;
            $payment->order_id      = $order->id;
            $order->payment_method = 1;  // 1 es mercado pago, 2 yape
        } else {
            $payment = Payment::find('mp_payment_id', $payment_id);
            $order = $payment->order;
        }


        if ($status == 'approved') {
            $order->status   = 2;
            $payment->status = 1;
            $mensaje = "Gracias por comprar en KuchasKids";
        } else if ($status == 'in_process') {
            $payment->status = 2;
            $mensaje = "El pago esta en proceso de aprobación, Gracias por comprar en KuchasKids";
        } else if ($status == 'rejected') {
            $payment->status = 3;
            $order->status   = 5;
            $mensaje = "El pago a sido rechazado! comuníquese con nosotros";
        }

        $order->save();
        $payment->save();

        session()->flash('flash.banner', $mensaje);


        $data = [
            'order' => $order
        ];

        Mail::to(Setting::first()->email_receive)
            ->queue(new MessageRecieved(
                $data,
                'Nueva Venta Página web Kuchas Kids'
            ));

        return redirect()->route('orders.show', $order);
    }

    public function izipay(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            abort(404, 'Orden no encontrada.');
        }

        $response = json_decode($request["kr-answer"]);

        $status = $response->orderStatus;

        $payment = new Payment();
        $payment->amount        = $order->total;
        $payment->user_id       = auth()->user()->id;
        $payment->order_id      = $order->id;
        $order->payment_method = 1;  // 1 es Izipay

        if ($status == 'PAID') {
            $order->status   = 2;
            $payment->status = 1;
            $mensaje = "Gracias por comprar en KuchasKids";
        } else if ($status == 'RUNNING') {
            $payment->status = 2;
            $mensaje = "El pago esta en proceso de aprobación, Gracias por comprar en KuchasKids";
        } else if ($status == 'UNPAID') {
            $payment->status = 3;
            $order->status   = 5;
            $mensaje = "El pago a sido rechazado! comuníquese con nosotros";
        } else {
            $payment->status = 2;
            $mensaje = "No pudimos confirmar el estado de tu pago. Nos pondremos en contacto contigo.";
        }

        $order->save();
        $payment->save();

        session()->flash('flash.banner', $mensaje);

        $data = [
            'order' => $order
        ];

        Mail::to(Setting::first()->email_receive)
            ->queue(new MessageRecieved(
                $data,
                'Nueva Venta Página web Kuchas Kids'
            ));

        return redirect()->route('orders.show', $order);
    }

    public function orderFailure(Request $request)
    {
        $mensaje = 'Su tarjeta ha sido rechazada por favor comuníquese con su entidad financiera';
        session()->flash('flash.banner', $mensaje);
        return redirect()->route('welcome');
    }
}
