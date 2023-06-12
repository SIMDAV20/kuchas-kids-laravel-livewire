<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItem extends Component
{

    public $product, $quantity, $qty = 1, $options = [];

    public function mount()
    {
        $this->quantity = qty_available($this->product->id);
        $this->options['image'] = Storage::url($this->product->images->first()->url);
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
    }
    public function addItem()
    {
        [$base_price, $price] = applyOffer($this->product);
        $this->options['base_price'] = $base_price;

        Cart::add([
            'id'          => $this->product->id,
            'name'        => $this->product->name,
            'qty'         => $this->qty,
            'price'       => $price,
            'weight'      => 550,
            'options'     => $this->options
        ]);

        // actualiazr el stock
        $this->quantity = qty_available($this->product->id);

        // refrescar el qty a t1
        $this->reset('qty');

        // Hago un evento para comunicar entre componentes
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }
    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
