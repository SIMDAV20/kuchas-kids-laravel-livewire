<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItem extends Component
{

    public $product, $quantity;

    public $options = [
        'size_id' => null,
        'color_id' => null
    ];

    public $qty = 1;

    public function mount()
    {
        $this->quantity = qty_available($this->product->id);

        if (count($this->product->color_product)) {
            foreach ($this->product->color_product as $key => $p_color_prod) {
                $this->options['image'] = Storage::url($p_color_prod->images->first()->url);
            }
        } else {
            $this->options['image'] = Storage::url($this->product->images->first()->url);
        }
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
        $price = 0;
        if (
            $this->product->offer_price > 0 &&
            (Carbon::parse($this->product->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) &&
            $this->product->offer_date !== null
        ) {
            $price = $this->product->offer_price;
            $this->options['base_price'] = $this->product->price;

            // SI LA FECHA LIMITE ES INDEFINIDO
        } else if ($this->product->offer_price > 0 && $this->product->offer_date == null) {
            $price = $this->product->offer_price;
            $this->options['base_price'] = $this->product->price;
        } else {
            $price = $this->product->price;
        }

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
