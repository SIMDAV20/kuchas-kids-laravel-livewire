<?php

namespace App\Http\Livewire;

use App\Models\Size;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class AddCartItemSize extends Component
{
    public $product, $size_id, $prod_size, $qty = 1, $quantity = 0, $options = [];

    public function mount()
    {
        $this->quantity = qty_available($this->product->id, null, $this->size_id);
        $size = Size::find($this->size_id);

        $this->prod_size = $this->product->product_size()->where('size_id', $size->id)->first();

        if ($this->prod_size->images->count() > 0) {
            $image_path = $this->prod_size->images->first()->url;
        } else {
            $image_path = $this->product->images->first()->url;
        }

        $this->options['size'] =    $size->name;
        $this->options['size_id'] = $size->id;
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

        [$base_price, $price] = applyOffer($this->prod_size);
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
        $this->quantity = qty_available($this->product->id, null, $this->size_id);

        // refrescar el qty a 1
        $this->reset('qty');

        // Hago un evento para comunicar entre componentes
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }
    public function render()
    {
        return view('livewire.add-cart-item-size');
    }
}
