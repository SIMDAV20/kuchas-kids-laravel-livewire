<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Category;
use App\Models\Setting;
use App\Models\Subcategory;

class Navigation extends Component
{
  public function render()
  {

    $categories = Category::with(['subcategories' => function ($query) {
      $query->orderBy('position')->where('status', Subcategory::PUBLIC);
    }])->orderBy('position')->where('status', Category::PUBLIC)->get();
    return view('livewire.navigation', compact('categories'));
  }
}
