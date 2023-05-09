<?php

namespace App\Http\Livewire;

use App\Models\ColorProduct;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItemColor extends Component
{

    public $product,  $colors, $options = [];
    // public $options = [
    //     'size_id' => null
    // ];
    public $color; // $color es el que paso x la url y puedo obtener cualquier attr

    public $qty = 1;

    public $quantity = 0;

    public function mount() // para renderizar en el carrito
    {
        $this->colors = $this->product->colors;

        if (count($this->product->color_product)) {

            $colorprod = ColorProduct::where('color_id', $this->color->id)->where('product_id', $this->product->id)->first();

            $this->options['image'] = Storage::url(@$colorprod->images->first()->url);

            // foreach ($this->product->color_product as $key => $p_color_prod) {
            //     $this->options['image'] = Storage::url($p_color_prod->images->where('color_id', $this->color_id)->first()->url);
            // }
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
    // siempre que lleve una funcion la palabra update, se actualizara cada
    // vez que sufra un cambio el wire:model
    public function updatingColorId($value)
    {
        $color = $this->product->colors->find($value);
        // pivot nos ayuda a recuperar la informacion de la tabla intermedia
        $this->quantity = qty_available($this->product->id, $color->id);
        $this->options['color'] = $color->name;
        $this->options['color_id'] = $color->id;
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

        // actualizar el stock
        $this->quantity = qty_available($this->product->id, $this->color->id);

        // refrescar el qty a t1
        $this->reset('qty');

        // Hago un evento para comunicar entre componentes
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }
    public function render()
    {
        $this->updatingColorId($this->color->id);
        return view('livewire.add-cart-item-color');
    }
}
