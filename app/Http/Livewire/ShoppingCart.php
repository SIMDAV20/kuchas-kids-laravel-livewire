<?php

namespace App\Http\Livewire;

use App\Models\Color;
use Livewire\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class ShoppingCart extends Component
{

    protected $listeners = ['render'];

    public function destroy() {
        Cart::destroy();
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }

    public function delete($rowID) {
        Cart::remove($rowID);
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }

    public function render()
    {
        $colors = Color::all();
        return view('livewire.shopping-cart', compact('colors'));
    }
}
