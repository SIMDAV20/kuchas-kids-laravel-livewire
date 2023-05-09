<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class FooterPublic extends Component
{
    // public $categories = [];

    public function render()
    {

        $categories = Category::all();

        return view('livewire.footer-public', compact('categories'));
    }
}
