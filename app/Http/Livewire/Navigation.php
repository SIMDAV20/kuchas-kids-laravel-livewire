<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Category;

class Navigation extends Component
{
    public function render()
    {

        $categories = Category::orderBy('position')->get();

        return view('livewire.navigation', compact('categories'));
    }
}
