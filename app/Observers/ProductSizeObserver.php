<?php

namespace App\Observers;

use App\Models\ProductSize;
use Illuminate\Support\Facades\Storage;

class ProductSizeObserver
{
    /**
     * Handle the ProductSize "created" event.
     *
     * @param  \App\Models\ProductSize  $productSize
     * @return void
     */
    public function created(ProductSize $productSize)
    {
        //
    }

    /**
     * Handle the ProductSize "updated" event.
     *
     * @param  \App\Models\ProductSize  $productSize
     * @return void
     */
    public function updated(ProductSize $productSize)
    {
        //
    }

    /**
     * Handle the ProductSize "deleted" event.
     *
     * @param  \App\Models\ProductSize  $productSize
     * @return void
     */
    public function deleted(ProductSize $productSize)
    {
        if ($productSize->images()->count() > 0) {
            foreach ($productSize->images as $image) {
                Storage::delete($image->path); // ruta de la photo
                $image->delete();
            }
        }
    }

    /**
     * Handle the ProductSize "restored" event.
     *
     * @param  \App\Models\ProductSize  $productSize
     * @return void
     */
    public function restored(ProductSize $productSize)
    {
        //
    }

    /**
     * Handle the ProductSize "force deleted" event.
     *
     * @param  \App\Models\ProductSize  $productSize
     * @return void
     */
    public function forceDeleted(ProductSize $productSize)
    {
        //
    }
}
