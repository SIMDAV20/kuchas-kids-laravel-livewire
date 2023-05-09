<?php

namespace App\Http\Livewire;

use App\Models\Image;
use App\Models\Order;
use App\Models\Payment;
use App\Mail\MessageRecieved;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaymentOrder extends Component
{

    use AuthorizesRequests, WithFileUploads;

    public $order, $photo, $rand;

    public $payment_method;

    protected $rules = [
        'photo' => 'required|mimes:png,jpeg,jpg|max:2048' // 2MB
    ];

    protected $listeners = ['payOrder'];

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->rand = rand();
    }

    public function payOrder()
    {
        $this->order->status = 2;
        $this->order->payment_method = 2;
        $this->order->save();

        $this->order->payment->status = Payment::APROBADO;
        $this->order->payment->save();

        $mensaje = "Gracias por comprar en KuchasKids";
        session()->flash('flash.banner', $mensaje);

        $data = [
            'order' => $this->order
        ];

        if (env('APP_URL') !== 'http://127.0.0.1:8000') {
            Mail::to('atencionalclientekuchaskids@gmail.com')
                ->queue(new MessageRecieved(
                    $data,
                    'Nueva Venta Página web Kuchas Kids'
                ));
        }

        return redirect()->route('orders.show', $this->order);
    }

    public function saveYape()
    {
        $this->resetValidation();
        $this->validate();

        $image = $this->photo->store('photos');

        $payment = new Payment();
        $payment->amount   = $this->order->total;
        $payment->user_id  = Auth::id();
        $payment->order_id = $this->order->id;
        $payment->save();

        Image::create([
            'url' => $image,
            'imageable_id'   => $payment->id,
            'imageable_type' => Payment::class
        ]);

        $this->cleanupOldUploads();

        $this->payOrder();
    }

    public function updatingPaymentMethod($value)
    {
        if ($value == 1) {
            $this->emit('loadMP');
        }
    }

    public function render()
    {

        $this->authorize('author', $this->order);
        $this->authorize('payment', $this->order);


        $items = json_decode($this->order->content); // es como un json_parse en js
        $envio = json_decode($this->order->envio);

        return view('livewire.payment-order', compact('items', 'envio'));
    }

    protected function cleanupOldUploads()
    {

        $storage = Storage::disk('local');

        foreach ($storage->allFiles('livewire-tmp') as $filePathname) {
            if (!$storage->exists($filePathname)) continue;
            $yesterdaysStamp = now()->subSeconds(10)->timestamp;
            if ($yesterdaysStamp > $storage->lastModified($filePathname)) {
                $storage->delete($filePathname);
            }
        }
    }
}
