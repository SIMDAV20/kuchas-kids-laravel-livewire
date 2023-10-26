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
        'category_id'    => 'required',
        'subcategory_id' => 'required',
        'name'           => 'required',
        'slug'           => 'required|unique:products',
        'description'    => 'required',
        'price'          => 'required|min:2|numeric',
        'offer_price'    => 'nullable|lt:price',
        'quantity'       => 'required|min:0|numeric',
        // 'brand_id'       => 'required',
    ];

    protected $validationAttributes = [
        'category_id'    => 'categoría',
        'subcategory_id' => 'subcategoría',
        'name'           => 'nombre',
        'description'    => 'descripción',
        'price'          => 'precio',
        'offer_price'    => 'precio oferta',
        'quantity'       => 'cantidad',
        'brand_id'       => 'marca',
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
        if (count($this->brands) > 0) {
            $rules['brand_id'] = 'required';
        } else {
            $this->brand_id = null;
        }
        $this->validate();

        $product = new Product();
        $product->name           = $this->name;
        $product->slug           = $this->slug;
        $product->description    = $this->description;
        $product->subcategory_id = $this->subcategory_id;
        $product->brand_id       = $this->brand_id ?: null;
        $product->price    = $this->price > 0 ? $this->price : null;
        $product->offer_price = $this->offer_price > 0 ? $this->offer_price : null;
        $product->quantity = $this->quantity > 0 ? $this->quantity : null;
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
