<?php

namespace App\Http\Livewire;

use App\Models\Color;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItemColor extends Component
{

    public $product, $prod_color, $color_id, $qty = 1, $quantity = 0, $options = [];

    public function mount() // para renderizar en el carrito
    {
        $this->quantity = qty_available($this->product->id, $this->color_id);

        $color = Color::find($this->color_id);

        $this->prod_color = $this->product->color_product()->where('color_id', $color->id)->first();

        if ($this->prod_color->images->count() > 0) {
            $image_path = $this->prod_color->images->first()->url;
        } else {
            $image_path = $this->product->images->first()->url;
        }

        $this->options['color'] =    $color->name;
        $this->options['color_id'] = $color->id;
        $this->options['image'] = Storage::url($image_path);
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

        // actualizar el stock
        $this->quantity = qty_available($this->product->id, $this->color_id);

        // refrescar el qty a t1
        $this->reset('qty');

        // Hago un evento para comunicar entre componentes
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }
    public function render()
    {
        return view('livewire.add-cart-item-color');
    }
}
