<?php

namespace App\Http\Livewire;

use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Component;

class Search extends Component
{
    public $search;

    public $open = false;

    public function updatingSearch($value)
    {
        if ($value) {
            $this->open = true;
        } else {
            $this->open = false;
        }
    }

    public function render()
    {
        // setSEOTools();
        $products = collect([]);
        if ($this->search) {
            $take = 8;
            $subcategories = Subcategory::name($this->search)->get();
            // dd($subcategories);
            if (count($subcategories) > 0) {
                foreach ($subcategories as $key => $subcategory) {
                    $products = $products->merge(
                        Product::where('subcategory_id', $subcategory->id)
                            ->search($this->search)
                            ->take($take)
                            ->get()
                    );
                    // $products = $products->merge($subcategory->products()
                    //     ->search($this->search)->take($take));
                }
                $products = $products->unique()->sortBy(['position'])->take($take);
            } else {
                $products = Product::search($this->search)
                    ->orderBy('name', 'asc')
                    ->orderBy('position')
                    ->take($take)
                    ->get();
            }

            // foreach ($products as $key => $product) {
            //     $product->low_price = 9999999;
            //     if (count($product->sizes) > 0) {
            //         foreach ($product->product_size as $s_product) {
            //             if (
            //                 $s_product->offer_price > 0 &&
            //                 (Carbon::parse($product->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) &&
            //                 $product->offer_date !== null
            //             ) {
            //                 $product->low_price = $s_product->offer_price;
            //                 // SI LA FECHA LIMITE ES INDEFINIDO
            //             } elseif ($product->offer_price > 0 && $product->offer_date == null) {
            //                 $product->low_price = $s_product->offer_price;
            //             } elseif ($product->low_price > $s_product->price) {
            //                 $product->low_price = $s_product->price;
            //             }
            //         }
            //     }
            // }
        }

        return view('livewire.search', compact('products'));
    }
}
