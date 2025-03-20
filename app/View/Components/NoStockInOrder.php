<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Gloudemans\Shoppingcart\Facades\Cart;

class NoStockInOrder extends Component
{


    public $rowId, $qty, $quantity, $newqty, $items;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.no-stock-in-order');
    }
}
