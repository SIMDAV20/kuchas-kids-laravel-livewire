<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ShowCategory extends Component
{
    use WithFileUploads;

    public $subcategories, $category, $rand, $subcategory;

    public $subcategory_products = [], $subcategory_id = "";

    protected $listeners = ['delete'];

    public $createForm = [
        'name' => null,
        'slug' => null,
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null,
    ];

    protected $rules = [
        'createForm.name'   => 'required',
        'createForm.slug'   => 'required|unique:subcategories,slug',
    ];

    protected $validationAttributes = [
        'createForm.name'  => 'nombre',
        'createForm.slug'  => 'slug',
        'editForm.name'  => 'nombre',
        'editForm.slug'  => 'slug',
    ];

    public function updatingCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function updatingEditFormName($value)
    {
        $this->editForm['slug'] = Str::slug($value);
    }

    public function getSubcategories()
    {
        $this->subcategories = Subcategory::where('category_id', $this->category->id)->orderBy('position')->get();
    }

    public function save()
    {
        $this->validate();
        // con el subcategories() devuelve el category_id que pertene la nueva subcategoria que se esta creando
        $this->category->subcategories()->create([
            'name'  => $this->createForm['name'],
            'slug'  => $this->createForm['slug'],
        ]);

        $this->rand = rand();
        $this->reset('createForm');
        $this->getSubcategories();
        $this->emit("saved");
    }

    public function edit(Subcategory $subcategory)
    {
        $this->resetValidation();
        $this->subcategory = $subcategory;
        $this->editForm['open'] = true;

        $this->editForm['name']   = $subcategory->name;
        $this->editForm['slug']   = $subcategory->slug;
    }

    public function updateSubCategoriesPosition($list)
    {
        foreach ($list as $item) {
            $subcategory = Subcategory::find($item["value"]);
            $subcategory->position = $item["order"];
            $subcategory->save();
        }

        $this->emit('updated_subcategories_positions');
        $this->mount($this->category);
    }

    public function updatingSubcategoryId($value)
    {
        $this->subcategory_products = Product::where('subcategory_id', $value)->orderBy('position')->get();
    }

    public function updateProductsPosition($list)
    {
        // dd($list);
        foreach ($list as $item) {
            $product = Product::find($item["value"]);
            $product->position = $item["order"];
            $product->save();
        }

        $this->emit('updated_products_positions');
        $this->updatingSubcategoryId($this->subcategory_id);
    }

    public function update()
    {
        $rules = [
            'editForm.name'  => 'required',
            'editForm.slug'  => 'required|unique:subcategories,slug,' . $this->subcategory->id,
        ];

        $this->validate($rules);

        $this->subcategory->update($this->editForm);

        $this->reset(['editForm']); // , 'editImage' , iba adentro

        $this->getSubcategories();
    }

    public function delete(Subcategory $subcategory)
    {
        $subcategory->delete();
        $this->getSubcategories();
    }

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->rand = rand();
        $this->getSubcategories();
    }

    public function render()
    {
        return view('livewire.admin.show-category')->layout('layouts.admin');
    }
}
