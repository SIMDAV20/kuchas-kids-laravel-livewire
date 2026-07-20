<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function files(Product $product, Request $request) {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $url = Storage::put('products', $request->file('file'));

        $product->images_morph()->create([
            'url' => $url
        ]);
    }
}
