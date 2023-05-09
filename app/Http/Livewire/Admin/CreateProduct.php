<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CreateProduct extends Component
{
    public $categories, $subcategories = [], $brands = [];

    public $category_id = "", $subcategory_id = "", $brand_id = "";

    public $name, $slug, $description, $price, $offer_price, $offer_date, $quantity;

    protected $rules = [
        'category_id' => 'required',
        'subcategory_id' => 'required',
        'name' => 'required',
        'slug' => 'required|unique:products',
        'description' => 'required',
    ];

    public function updatingCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();

        // whereHas se necesita la relación del model para ref a la clase intermedia, por lo que con el builder puedo hacer las consultas con el $query
        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();

        $this->reset(['subcategory_id', 'brand_id']);
    }

    // prop computada

    public function getSubcategoryProperty()
    {
        // puede haber un null
        return Subcategory::find($this->subcategory_id);
    }

    public function updatingName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function save()
    {
        $rules = $this->rules;
        // if ($this->options == '' || $this->options == 'colors') {
        // }
        // if ($this->options == '')
        $rules['price'] = 'required|min:2|numeric';
        $rules['offer_price'] = 'lt:price';
        $rules['quantity'] = 'required|min:0|numeric';
        if (count($this->brands) > 0)
            $rules['brand_id'] = 'required';

        $this->validate($rules);

        $product = new Product();
        $product->name           = $this->name;
        $product->slug           = $this->slug;
        $product->description    = $this->description;
        $product->subcategory_id = $this->subcategory_id;
        $product->brand_id       = $this->brand_id ?: null;
        if ($this->price > 0)
            $product->price    = $this->price;
        if ($this->price > 0)
            $product->offer_price    = $this->offer_price;
        if ($this->quantity > 0)
            $product->quantity = $this->quantity;
        $product->save();

        $this->reset();

        return redirect()->route('admin.products.edit', $product);
    }

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        return view('livewire.admin.create-product')->layout('layouts.admin');
    }
}
