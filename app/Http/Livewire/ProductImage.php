<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class ProductImage extends Component
{
    public $product, $p_color = 0;

    public function show_image($value)
    {
        $this->p_color = $value;
    }

    public function render()
    {
        // $low_price = 9999999;
        // if (count($this->product->sizes) > 0) {
        //     foreach ($this->product->product_size as $s_product) {
        //         // if (
        //         //     $s_product->offer_price > 0 &&
        //         //     (Carbon::parse($this->product->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) &&
        //         //     $this->product->offer_date !== null
        //         // ) {
        //         //     $low_price = $s_product->offer_price;
        //         // SI LA FECHA LIMITE ES INDEFINIDO
        //         // && $this->product->offer_date == null
        //         if ($this->product->offer_price > 0) {
        //             $low_price = $s_product->offer_price;
        //         }
        //         // elseif ($s_product->offer_price && $low_price > $s_product->price) {
        //         //     $low_price = $s_product->price;
        //         // }
        //     }
        // }
        $this->product->onStockToSell();
        $low_price = $this->product->getMinPrice();
        return view('livewire.product-image', compact('low_price'));
    }
}
