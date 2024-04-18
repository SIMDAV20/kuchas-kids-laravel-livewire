<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class EditProductVariant extends Component
{
    public $variant;

    public function mount(Product $variant)
    {
        $this->variant = $variant;
    }

    public function render()
    {
        return view('livewire.admin.edit-product-variant')->layout('layouts.admin');;
    }
}
