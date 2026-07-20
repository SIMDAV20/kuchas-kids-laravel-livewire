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
    'inputKeyword' => null,
    'keywords' => [],
  ];

  public $editForm = [
    'open' => false,
    'id' => -1,
    'name' => null,
    'slug' => null,
    'keywords' => [],
  ];

  protected $rules = [
    'createForm.name'   => 'required',
    'createForm.slug'   => 'required|unique:subcategories,slug',
    'createForm.keywords'   => 'required|array|min:1',
    'createForm.keywords.*'   => 'required|string|distinct|min:1',
  ];

  protected $validationAttributes = [
    'createForm.name'  => 'nombre',
    'createForm.slug'  => 'slug',
    'createForm.keywords'  => 'palabras claves',
    'createForm.keywords.*'  => 'palabras claves',
    'editForm.name'  => 'nombre',
    'editForm.slug'  => 'slug',
    'editForm.keywords'  => 'palabras claves',
  ];

  public function addKeyword(string $type)
  {
    $keyword = trim($type === 'create' ? $this->createForm['inputKeyword'] : $this->editForm['inputKeyword']);

    // Check if the keyword is less than 2 characters
    if (strlen($keyword) < 2) {
      return;
    }

    if ($type == 'create') {
      $this->createForm['keywords'][] = $keyword;
      $this->createForm['inputKeyword'] = null;
    } else {
      $this->editForm['keywords'][] = $keyword;
      $this->editForm['inputKeyword'] = null;
    }
  }

  public function deleteKeyword(string $type, $value)
  {
    if ($type == 'create') {
      $this->createForm['keywords'] = $this->filterKeywords($this->createForm['keywords'], $value);
    } else {
      $this->editForm['keywords'] = $this->filterKeywords($this->editForm['keywords'], $value);
    }
  }

  private function filterKeywords(array $keywords, string $value)
  {
    $filterValues = array_filter($keywords, function ($keyword) use ($value) {
      $keyword = trim($keyword);
      return $keyword !== $value;
    });

    return array_values($filterValues);
  }

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
      'keywords'  => $this->createForm['keywords'],
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

    $this->editForm['id'] = $subcategory->id;
    $this->editForm['name']   = $subcategory->name;
    $this->editForm['slug']   = $subcategory->slug;
    $this->editForm['keywords'] = $subcategory->keywords ?? [];
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
      'editForm.keywords'   => 'required|array|min:1',
      'editForm.keywords.*'   => 'required|string|distinct|min:1',
    ];

    $this->validate($rules);

    $this->subcategory->update([
      'name'  => $this->editForm['name'],
      'slug' => $this->editForm['slug'],
      'keywords'  => $this->editForm['keywords'],
    ]);

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
