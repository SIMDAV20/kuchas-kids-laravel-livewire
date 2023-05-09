<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Product;
use App\Models\ColorProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProduct(Product $product, $color_url = null)
    {
        $product->onStockToSell();
        $color = '';

        $date1 = Carbon::parse($product->offer_date)->format('Y-m-d');
        $date2 = Carbon::yesterday()->format('Y-m-d');
        $date3 = Carbon::now()->format('Y-m-d');


        $showOfferDate = false;
        $sameDay       = false;
        if ($date1 > $date2) {
            $showOfferDate = true;
        }

        if ($date1 == $date2) {
            $sameDay = true;
        }

        if (isset($color_url)) {
            $color = Color::where('slug', $color_url)->first();
            return view(
                'products.show',
                compact('product', 'showOfferDate', 'sameDay', 'color')
            );
        } else if (!isset($color_url)) {
            $color = Color::first();
            return view(
                'products.show',
                compact('product', 'showOfferDate', 'sameDay', 'color')
            );
        } else {
            return view(
                'products.show',
                compact('product', 'showOfferDate', 'sameDay')
            );
        }
    }
}
