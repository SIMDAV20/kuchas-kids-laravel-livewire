<?php

namespace App\Observers;

use App\Models\ColorProduct;
use Illuminate\Support\Facades\Storage;

class ColorProductObserver
{
    /**
     * Handle the ColorProduct "created" event.
     *
     * @param  \App\Models\ColorProduct  $colorProduct
     * @return void
     */
    public function created(ColorProduct $colorProduct)
    {
        //
    }

    /**
     * Handle the ColorProduct "updated" event.
     *
     * @param  \App\Models\ColorProduct  $colorProduct
     * @return void
     */
    public function updated(ColorProduct $colorProduct)
    {
        //
    }

    /**
     * Handle the ColorProduct "deleted" event.
     *
     * @param  \App\Models\ColorProduct  $colorProduct
     * @return void
     */
    public function deleted(ColorProduct $colorProduct)
    {
        if ($colorProduct->images()->count() > 0) {
            foreach ($colorProduct->images as $image) {
                Storage::delete($image->path); // ruta de la photo
                $image->delete();
            }
        }
    }

    /**
     * Handle the ColorProduct "restored" event.
     *
     * @param  \App\Models\ColorProduct  $colorProduct
     * @return void
     */
    public function restored(ColorProduct $colorProduct)
    {
        //
    }

    /**
     * Handle the ColorProduct "force deleted" event.
     *
     * @param  \App\Models\ColorProduct  $colorProduct
     * @return void
     */
    public function forceDeleted(ColorProduct $colorProduct)
    {
        //
    }
}
