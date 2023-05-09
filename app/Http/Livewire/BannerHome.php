<?php

namespace App\Http\Livewire;

use App\Models\Banner;
use Livewire\Component;

class BannerHome extends Component
{
    public function render()
    {
        $banners = Banner::all();
        return view('livewire.banner-home', compact('banners'));
    }
}
