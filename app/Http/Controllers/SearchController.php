<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {

        $products = Product::join('subcategories', 'products.subcategory_id', '=', 'subcategories.id')
            ->select('products.*', 'subcategories.name as subcategory_name')
            ->search($request->name)
            ->orderBy('products.name', 'asc')
            ->orderBy('subcategories.position')
            ->orderBy('position')
            ->paginate(8);

        if (!$products->isEmpty()) {
            $seoItems = collect([]);
            foreach ($products as $key => $product) {
                $seoItems->push($product->subcategory->category->name);
                $seoItems->push($product->subcategory->name);
                $seoItems->push($product->brand->name);
                $seoItems->push($product->name);
            }

            $description =  $seoItems->unique()->values()->implode(',');

            $data = [
                'name' => $request->name,
                'page' => $products->currentPage()
            ];

            $query = Arr::query($data);
            $url = url()->current() . ($query ? ('?' . $query) : '');

            setSEOTools(null, $description, $url);
        }
        return view('search', compact('products'));
    }

    public function paginate($items, $perPage = 8, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
