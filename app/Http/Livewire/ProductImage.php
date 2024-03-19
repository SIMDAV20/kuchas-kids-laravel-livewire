<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class ProductImage extends Component
{
    public $product;

    // public function show_image($value)
    // {
    //     $this->p_color = $value;
    // }

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

        // $this->getProductVariants($this->product);

        $this->product->onStockToSell();

        // TODO: a refactorizar
        $typeProduct = '';
        if (count($this->product->color_product) > 0) {
            $typeProduct = 1;
        }
        if (count($this->product->product_size) > 0) {
            $typeProduct = 2;
        }
        if (count($this->product->color_product_size) > 0) {
            $typeProduct = 3;
        }

        return view('livewire.product-image', compact('typeProduct'));
    }

    // private function getProductVariants($prod)
    // {
    //     $base = 'Product';
    //     $image = collect([]);

    //     if (count($prod->color_product) > 0 && count($prod->product_size) > 0 && count($prod->color_product_size) > 0) {
    //         $image = $prod->images->first()->url;
    //     } else if (count($prod->color_product) > 0) {
    //         $base = 'ColorProduct';
    //     } else if (count($prod->color_product) > 0) {

    //         $base = 'ProductSize';
    //     } else if (count($prod->color_product_size) > 0) {
    //         $base = 'ColorProductSize';
    //     }

    //     return [$base];
    // }
}
