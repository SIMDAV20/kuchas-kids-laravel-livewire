<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CreateCategory extends Component
{
    use WithFileUploads;

    public $categories, $category, $brands, $rand;

    protected  $listeners = ['delete'];

    // TODO: falta colocarle por las edades
    public $createForm = [
        'name' => null,
        'slug' => null,
        'image' => null,
        'brands' => []
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null,
        'image' => null,
        'brands' => []
    ];

    public $editImage = null;

    protected $rules = [
        'createForm.name'   => 'required',
        'createForm.slug'   => 'required|unique:categories,slug',
        'createForm.image'  => 'required|image|max:1024', //1MB
        'createForm.brands' => 'required'
    ];


    protected $validationAttributes = [
        'createForm.name'   => 'nombre',
        'createForm.slug'   => 'slug',
        'createForm.image'  => 'imagen',
        'createForm.brands' => 'marcas',

        'editForm.name'   => 'nombre',
        'editForm.slug'   => 'slug',
        'editImage'       => 'imagen',
        'editForm.brands' => 'marcas'
    ];

    public function mount()
    {
        $this->getCategories();
        $this->getBrands();
        $this->rand = rand();
    }

    public function updatingCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function updatingEditFormName($value)
    {
        $this->editForm['slug'] = Str::slug($value);
    }

    public function getBrands()
    {
        $this->brands = Brand::all();
    }

    public function getCategories()
    {
        $this->categories = Category::orderBy('position')->get();
    }

    public function save()
    {
        $this->validate();
        // no existe ningun error, es el editor de codigo
        $image = $this->createForm['image']->store('categories');

        $category = Category::create([
            'name'  => $this->createForm['name'],
            'slug'  => $this->createForm['slug'],
            'image' => $image,
        ]);

        // relaciono las marcas con las categorias
        $category->brands()->attach($this->createForm['brands']);

        $this->rand = rand();
        $this->reset('createForm');

        $this->getCategories();
        $this->emit("saved");
    }

    public function edit(Category $category)
    {
        $this->reset(['editImage']);

        $this->resetValidation();

        $this->category = $category;

        $this->editForm['open'] = true;
        $this->editForm['name']   = $category->name;
        $this->editForm['slug']   = $category->slug;
        // $this->editForm['icon']   = $category->icon;
        $this->editForm['image']  = $category->image;
        // pluck me ayuda para solo captura ciertos campos de una coleccion
        $this->editForm['brands'] = $category->brands->pluck('id');
    }

    public function update()
    {

        $rules = [
            'editForm.name'   => 'required',
            'editForm.slug'   => 'required|unique:categories,slug,' . $this->category->id,
            // 'editForm.icon'   => 'required',
            'editForm.brands' => 'required'
        ];

        if ($this->editImage) {
            $rules['editImage'] = 'required|image|max:1024'; //1MB
        }

        $this->validate($rules);

        if ($this->editImage) {
            Storage::delete($this->editForm['image']);
            $this->editForm['image'] = $this->editImage->store('categories');
        }

        $this->category->update($this->editForm);

        $this->category->brands()->sync($this->editForm['brands']);

        $this->reset(['editForm', 'editImage']);

        $this->getCategories();
    }

    public function delete(Category $category)
    {
        $category->delete();
        $this->getCategories();
    }

    public function updateCategoriesPosition($list)
    {
        foreach ($list as $item) {
            $category = Category::find($item["value"]);
            $category->position = $item["order"];
            $category->save();
        }

        $this->emit('updated_positions');
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.create-category');
    }
}
