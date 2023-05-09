<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryProducts extends Component
{
    // instanciar las propiedades que quiero comunicar desde el componente livewire
    public $category;

    public $products = [];

    public $pos_color = 0;

    public $product, $isLoading = true;

    public function loadProducts()
    {
        if ($this->category == null) {
            $this->category = Category::all()->random();
        }
        // jalar todos los productos por categoria y recien enviarlo al componente category-products
        $this->products = $this->category->products()->where('status', 2)
            ->orderBy('id', 'ASC')->get(); // ->take(15)

        if (isset($this->product)) {
            $product_id = $this->product->id;
            $this->products = $this->products->filter(function ($product) use ($product_id) {
                if ($product->id !== $product_id)
                    return $product;
            });
        }

        $this->emit('glider', $this->category->id); // se emite el evento que se llama como clase en en el componente
        $this->isLoading = false;
    }

    public function render()
    {
        // $this->products = $this->category->products()->where('status', 2)->take(15)->get();
        return view('livewire.category-products');
    }
}
