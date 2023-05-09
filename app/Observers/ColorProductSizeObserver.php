<?php

namespace App\Observers;

use App\Models\ColorProductSize;
use Illuminate\Support\Facades\Storage;

class ColorProductSizeObserver
{
    /**
     * Handle the ColorProductSize "created" event.
     *
     * @param  \App\Models\ColorProductSize  $colorProductSize
     * @return void
     */
    public function created(ColorProductSize $colorProductSize)
    {
        //
    }

    /**
     * Handle the ColorProductSize "updated" event.
     *
     * @param  \App\Models\ColorProductSize  $colorProductSize
     * @return void
     */
    public function updated(ColorProductSize $colorProductSize)
    {
        if ($colorProductSize->images()->count() > 0) {
            foreach ($colorProductSize->images as $image) {
                Storage::delete($image->path); // ruta de la photo
                $image->delete();
            }
        }
    }

    /**
     * Handle the ColorProductSize "deleted" event.
     *
     * @param  \App\Models\ColorProductSize  $colorProductSize
     * @return void
     */
    public function deleted(ColorProductSize $colorProductSize)
    {
        //
    }

    /**
     * Handle the ColorProductSize "restored" event.
     *
     * @param  \App\Models\ColorProductSize  $colorProductSize
     * @return void
     */
    public function restored(ColorProductSize $colorProductSize)
    {
        //
    }

    /**
     * Handle the ColorProductSize "force deleted" event.
     *
     * @param  \App\Models\ColorProductSize  $colorProductSize
     * @return void
     */
    public function forceDeleted(ColorProductSize $colorProductSize)
    {
        //
    }
}
