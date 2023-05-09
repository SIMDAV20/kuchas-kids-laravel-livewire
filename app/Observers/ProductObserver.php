<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{

    /**
     * Handle the Product "deleted" event.
     *
     * @param  \App\Models\Product  $Product
     * @return void
     */
    public function deleted(Product $product)
    {
        if ($product->images()->count() > 0) {
            foreach ($product->images as $image) {
                Storage::delete($image->path); // ruta de la photo
                $image->delete();
            }
        }
    }

    public function updated(Product $product)
    {
        // $subcategory_id = $product->subcategory_id;

        // $subcategory = Subcategory::find($subcategory_id);

        // if ($subcategory->size) {
        //     if ($product->colors->count()) {
        //         $product->colors()->detach();
        //     }
        // }
        // elseif ($subcategory->color) {
        //     if ($product->sizes->count()) {
        //         foreach ($product->sizes as $size) {
        //             $size->delete();
        //         }
        //     }
        // }
        // else {
        //     if ($product->colors->count()) {
        //         $product->colors()->detach();
        //     }

        //     if ($product->sizes->count()) {
        //         foreach ($product->sizes as $size) {
        //             $size->delete();
        //         }
        //     }
        // }
    }
}
