<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ColorProduct;
use App\Models\Size;
use Carbon\Carbon;
use Countable;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProduct(Product $product, $color_url = null)
    {
        // $product->onStockToSell();

        // if (count($this->color_product) > 0) {
        //     $this->color_product = $this->color_product()->where('quantity', '>', 0)->get();
        //     $this->colors = Color::whereIn('id', $this->color_product->pluck('color_id'))->get();
        //     // $this->images = $this->color_product->images ?? [];
        // }
        // if (count($this->product_size) > 0) {
        //     $this->product_size = $this->product_size()->where('quantity', '>', 0)->get();
        //     $this->sizes = Size::whereIn('id', $this->product_size->pluck('size_id'))->get();
        //     // $this->images = $this->product_size->images ?? [];
        // }


        // return $product->with('color_product', 'product_size');

        $config = [
            'color_product',
            'product_size',
            'colors_sizes',
        ];

        $colors = [];
        $sizes = [];

        foreach ($config as $key => $value) {
            $property = $product->{$value};
            if (is_array($property) || $property instanceof Countable) {
                $count = count($property);
                if ($count > 0) {
                    $vars = $product->{$value}()->where('quantity', '>', 0)->get();
                    $colors = Color::whereIn('id', @$vars->pluck('color_id'))->get();
                    $sizes = Size::whereIn('id', @$vars->pluck('size_id'))->get();
                    break;
                }
            }
        }

        // return [$product, $colors, $sizes];

        if (count($colors) && isset($color_url)) {
        }

        return view(
            'products.show',
            compact('product', 'colors', 'sizes')
        );

        // if ($color_url == null) {
        //     # code...
        // }
        // $color = '';

        // $date1 = Carbon::parse($product->offer_date)->format('Y-m-d');
        // $date2 = Carbon::yesterday()->format('Y-m-d');
        // $date3 = Carbon::now()->format('Y-m-d');


        // $showOfferDate = false;
        // $sameDay       = false;
        // if ($date1 > $date2) {
        //     $showOfferDate = true;
        // }

        // if ($date1 == $date2) {
        //     $sameDay = true;
        // }


        // if (isset($color_url)) {
        //     $color = Color::where('slug', $color_url)->first();
        //     return view(
        //         'products.show',
        //         compact('product', 'showOfferDate', 'sameDay', 'color')
        //     );
        // } else if (!isset($color_url)) {
        //     $color = $product->colors()->first();
        //     return view(
        //         'products.show',
        //         compact('product', 'showOfferDate', 'sameDay', 'color')
        //     );
        // } else {
        //     return view(
        //         'products.show',
        //         compact('product', 'showOfferDate', 'sameDay')
        //     );
        // }



    }
}
