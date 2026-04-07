<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class UpdateCartItem extends Component
{
    public $rowId, $qty, $quantity;

    public function mount() {
        $item = Cart::get($this->rowId);
        $this->qty = $item->qty;
        
        $variantId = $item->options->variant_id ?? null;
        
        // current_quantity returns the database stock
        // qty_available returns current_quantity - qty_added (excluding the current item in some implementations, but let's check our help function)
        // Our helpers.php: qty_available = current_quantity - qty_added.
        // It includes the current item, so we need to add back the current item's qty to know how much we CAN have in total for this session.
        $this->quantity = current_quantity($item->id, $variantId) - (qty_added($item->id, $variantId) - $item->qty);
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
        Cart::update($this->rowId, $this->qty);

        $this->emit('render');
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
        Cart::update($this->rowId, $this->qty);

        $this->emit('render');
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }

    public function render()
    {
        return view('livewire.update-cart-item');
    }
}
