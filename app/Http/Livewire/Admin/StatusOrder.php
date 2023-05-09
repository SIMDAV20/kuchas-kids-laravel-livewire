<?php

namespace App\Http\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;

class StatusOrder extends Component
{
    public $order, $status;

    public function mount() {
        $this->status = $this->order->status;
    }

    public function update() {
        $this->order->status = $this->status;
        $this->order->save();

        if(!$this->order->payment) {
            $payment = new Payment();
            $payment->order_id = $this->order->id;
            $payment->amount   = $this->order->total;
            $payment->user_id  = $this->order->user_id;
            $payment->save();
        }

        if ($this->status > 1 && $this->status > 5) {
            $this->order->payment->status = Payment::APROBADO;
            $this->order->payment->save();
        } elseif($this->status == 5) {
            $payment = Payment::where('order_id', $this->order->id)->first();
            $payment->status = Payment::ANULADO;
            $payment->save();
        }

        $this->render();
    }

    // public function cancelOrder() {
    //     $this->order->status = 5;
    //     $this->order->save();

    //     $payment = Payment::where('order_id', $this->order->id)->first();
    //     $payment->status = Payment::ANULADO;
    //     $payment->save();
    // }

    public function render()
    {

        $items = json_decode($this->order->content);
        $envio = json_decode($this->order->envio);
        $status = $this->order->status;
        return view('livewire.admin.status-order', compact('items', 'envio', 'status'));
    }
}
