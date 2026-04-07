<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProduct(Request $request)
    {
        // Buscar el producto por slug (ahora centralizado)
        $product = Product::where('slug', $request->slugProduct)
            ->where('status', Product::PUBLICADO)
            ->first();

        if (!$product) {
            return view('errors.404');
        }

        // SEO LOGIC
        $seoItems = collect([]);
        $subcategory = $product->subcategory;
        
        if (!empty($subcategory->keywords)) {
            $seoItems->push(json_decode($subcategory->keywords));
        }

        $seoItems->push($subcategory->name);
        $seoItems->push($subcategory->category->name);
        $seoItems->push($product->brand->name ?? '');

        $description = '';
        $seoItems = $seoItems->flatten()->unique()->values();
        foreach ($seoItems as $key => $seoItem) {
            $description .= $seoItem . ($key === array_key_last($seoItems->toArray()) ? '' : ',');
        }

        setSEOTools($product->name, $description);

        return view('products.show', compact('product'));
    }
}
