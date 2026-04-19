<?php

use App\Models\Product;
use App\Models\ProductVariant;
use Artesaos\SEOTools\Facades\SEOTools;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

if (!function_exists('current_quantity')) {
    function current_quantity($product_id, $variant_id = null)
    {
        if ($variant_id) {
            $variant = \App\Models\ProductVariant::find($variant_id);
            return $variant ? $variant->stock : 0;
        }

        $product = Product::find($product_id);
        return $product ? $product->quantity : 0;
    }
}

if (!function_exists('qty_added')) {
    function qty_added($product_id, $variant_id = null)
    {

        $cart = Cart::content();

        // retorna un obj con el first
        $item = $cart->where('id', $product_id)->where('options.variant_id', $variant_id)->first();

        if ($item) {
            return  $item->qty;
        } else {
            return 0;
        }
    }
}


if (!function_exists('qty_available')) {
    function qty_available($product_id, $variant_id = null)
    {
        return current_quantity($product_id, $variant_id) - qty_added($product_id, $variant_id);
    }
}

if (!function_exists('discount')) {
    function discount($item)
    {
        $variant_id = @$item->options->variant_id;
        if ($variant_id) {
            $variant = \App\Models\ProductVariant::find($variant_id);
            if ($variant) {
                // $qty_available takes carts into consideration. Typical checkout logic subtracts cart.
                $variant->stock = $variant->stock - $item->qty;
                $variant->save();
            }
        } else {
            $product = Product::find($item->id);
            if ($product) {
                $product->quantity = $product->quantity - $item->qty;
                $product->save();
            }
        }
    }
}

if (!function_exists('increase')) {
    function increase($item)
    {
        $variant_id = @$item->options->variant_id;
        if ($variant_id) {
            $variant = \App\Models\ProductVariant::find($variant_id);
            if ($variant) {
                $variant->stock = $variant->stock + $item->qty;
                $variant->save();
            }
        } else {
            $product = Product::find($item->id);
            if ($product) {
                $product->quantity = $product->quantity + $item->qty;
                $product->save();
            }
        }
    }
}


if (!function_exists('findProduct')) {
    function findProduct($model, $id)
    {
        switch ($model) {
            case 'ProductVariant':
                $item = ProductVariant::findOrFail($id);
                break;
            case 'Product':
            default:
                $item = Product::findOrFail($id);
                break;
        }

        return $item;
    }
}


if (!function_exists('applyOffer')) {
    function applyOffer($item) // product or variant
    {
        $base_price = $item->price;
        $price = $item->price;

        // Verifica primero oferta flash activa
        $flashOffer = collect();
        if (method_exists($item, 'flashOffer')) {
            $flashOffer = $item->flashOffer()->active()->first();
        }
        
        if ($flashOffer && $flashOffer->flash_price > 0) {
            $price = $flashOffer->flash_price;
        } elseif ($item->offer_price > 0) {
            // Verifica oferta por fechas
            if (!isset($item->offer_date) || $item->offer_date === null) {
                $price = $item->offer_price;
            } else if (Carbon::parse($item->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) {
                $price = $item->offer_price;
            }
        }

        return [$base_price, $price];
    }
}

if (!function_exists('setSEOTools')) {
    function setSEOTools(string $entity_name = null, string $description = null, string $url = null)
    {
        $currentRoute = Route::currentRouteName();

        if (is_null($url)) {
            $url = url()->current();
        }

        $title = config('app.name', 'Laravel');


        switch ($currentRoute) {
            case 'welcome':
                $addTitle = 'Home';
                break;
            case 'search':
                $addTitle = 'Buscador';
                break;
            case 'categories.show':
                $addTitle = $entity_name;
                break;
            case 'products.show':
                $addTitle = $entity_name;
                break;

            default:
                $addTitle = '';
                break;
        }

        SEOTools::setCanonical($url);
        SEOTools::setTitle($title . ($addTitle !== '' ?  ' | ' . $addTitle : ''));
        SEOTools::setDescription($description);
    }

}
