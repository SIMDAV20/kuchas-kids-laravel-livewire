<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Size;
use Livewire\Component;

// ELMINAR EN EL FUTURO
class ProductSize extends Component
{
    public $product, $p_talla, $price = 0, $offer_price = 0;

    public function show_price($key)
    {
        $this->p_talla = $key;
        $this->price = $this->product->product_size[$key]->price;
        $this->offer_price = $this->product->product_size[$key]->offer_price;

        $size_id = $this->product->product_size[$key]->size_id;

        $size = Size::find($size_id);

        $this->emitTo('add-cart-item-size', 'update_stock', [
            'key' => $key,
            'size' => $size
        ]);
    }

    public function mount()
    {
        foreach ($this->product->product_size as $key => $prod_size) {
            if ($prod_size->quantity > 0) { // el primero que encuentra q tiene stock
                $this->price       = $this->product->product_size[$key]->price;
                $this->offer_price = $this->product->product_size[$key]->offer_price;
                $this->p_talla = $key;
                break; // sale del foreach
            }
        }
    }

    public function render()
    {
        return view('livewire.product-size');
    }
}
