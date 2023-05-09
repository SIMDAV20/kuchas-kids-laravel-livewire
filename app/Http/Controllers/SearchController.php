<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Subcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $products = Product::search($request->name) // solo los publicados
            ->orderBy('name', 'asc')
            ->paginate(8);

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
        return view('search', compact('products', 'name'));
    }

    public function paginate($items, $perPage = 8, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
