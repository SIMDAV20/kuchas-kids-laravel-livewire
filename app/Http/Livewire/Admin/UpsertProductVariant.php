<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Support\Str;
use Livewire\Component;

class UpsertProductVariant extends Component
{
  // Important variables
  public $product, $type_variant, $model_str, $model, $variants, $slug;

  // Secondary variables
  public $sizes, $colors, $slug_color = '', $slug_size = '', $open = false;

  protected $listeners = ['refreshVariants', 'delete'];

  public $createForm = [
    'color_id' => null,
    'size_id' => null,
    'slug' => null,
    'quantity' => null,
    'price' => null,
    'offer_price' => null,
  ];

  public $editForm = [
    'id' => -1, // variant id or simple
    'color_id' => -1,
    'size_id' => -1,
    'slug' => null,
    'quantity' => null,
    'price' => null,
    'offer_price' => null,
  ];

  protected $validationAttributes = [
    'createForm.color_id'    => 'color',
    'createForm.size_id'     => 'talla',
    'createForm.quantity'    => 'cantidad',
    'createForm.price'       => 'precio',
    'createForm.offer_price' => 'precio oferta',

    'editForm.color_id'    => 'color',
    'editForm.size_id'     => 'talla',
    'editForm.quantity'    => 'cantidad',
    'editForm.price'       => 'precio',
    'editForm.offer_price' => 'precio oferta',
  ];

  protected $rules = [
    'createForm.quantity'    => 'required|numeric|min:1',
    'createForm.price'       => 'required|numeric|min:2',
    'createForm.offer_price' => 'nullable|lt:createForm.price',

    'editForm.quantity'    => 'required|numeric|min:1',
    'editForm.price'       => 'required|numeric|min:2',
    'editForm.offer_price' => 'nullable|lt:editForm.price',
  ];

  public function save()
  {
    $this->resetValidation();
    $rules = $this->rules;

    if ($this->type_variant == Product::VARCOLORS || $this->type_variant == Product::VARCOLORSSIZES) {
      $rules['createForm.color_id'] = 'required';
    }
    if ($this->type_variant == Product::VARSIZES || $this->type_variant == Product::VARCOLORSSIZES) {
      $rules['createForm.size_id'] = 'required';
    }

    $table = [
      'colors' => 'color_product',
      'sizes' => 'product_size',
      'colors_sizes' => 'color_product_size',
    ][$this->type_variant];

    $rules['createForm.slug'] = 'required|unique:' . $table . ',slug';

    $this->validate($rules);

    $offer = $this->createForm['offer_price'];
    $this->createForm['offer_price'] = $offer > 0 ? $offer : null;
    $this->createForm['quantity'] = intval($this->createForm['quantity']);
    $this->createForm['product_id'] = $this->product->id;

    $this->model::create($this->createForm);
    $this->emit('saved');

    $this->reset('createForm');
    $this->product = $this->product->fresh();
    $this->getVariants();
  }

  public function edit($id)
  {
    $this->open = true;

    $item = $this->variants->firstWhere('id', $id);

    // fill values;
    if ($this->type_variant == Product::VARCOLORS || $this->type_variant == Product::VARCOLORSSIZES) {
      $this->editForm['color_id'] = $item->color_id;
      $this->updatingEditFormColorId($item->color_id);
    }
    if ($this->type_variant == Product::VARSIZES || $this->type_variant == Product::VARCOLORSSIZES) {
      $this->editForm['size_id'] = $item->size_id;
      $this->updatingEditFormSizeId($item->size_id);
    }

    $this->editForm['quantity']    = $item->quantity;
    $this->editForm['price']       = $item->price;
    $this->editForm['offer_price'] = $item->offer_price;
    $this->editForm['id'] = $id;
  }

  public function updatingCreateFormColorId($id)
  {
    $this->computedSlug($id, 'color_id', 'createForm');
  }

  public function updatingCreateFormSizeId($id)
  {
    $this->computedSlug($id, 'size_id', 'createForm');
  }

  public function updatingEditFormColorId($id)
  {
    $this->computedSlug($id, 'color_id', 'editForm');
  }

  public function updatingEditFormSizeId($id)
  {
    $this->computedSlug($id, 'size_id', 'editForm');
  }

  private function computedSlug($id, $type, $form)
  {
    // Always color and then size slug
    $slug = $this->product->slug;
    if ($type == 'color_id') {
      $this->slug_color = '-' . $this->colors->find($id)->name;
    }

    if ($type == 'size_id') {
      $this->slug_size = '-' . $this->sizes->find($id)->name;
    }

    $this->$form['slug'] = Str::slug($slug . $this->slug_color . $this->slug_size) ?: '';
  }

  public function update()
  {
    $this->resetValidation();
    $rules = $this->rules;

    if ($this->type_variant == Product::VARCOLORS || $this->type_variant == Product::VARCOLORSSIZES) {
      $rules['editForm.color_id'] = 'required';
    }
    if ($this->type_variant == Product::VARSIZES || $this->type_variant == Product::VARCOLORSSIZES) {
      $rules['editForm.size_id'] = 'required';
    }

    $table = [
      'colors' => 'color_product',
      'sizes' => 'product_size',
      'colors_sizes' => 'color_product_size',
    ][$this->type_variant];

    $rules['editForm.slug'] = 'required|unique:' . $table . ',slug,' . $this->editForm['id'];
    $this->open = false;

    $this->validate($rules);

    $offer = $this->editForm['offer_price'];
    $this->editForm['offer_price'] = $offer > 0 ? $offer : null;
    $this->editForm['quantity'] = intval($this->editForm['quantity']);
    $this->editForm['product_id'] = $this->product->id;

    // $data = $this->editForm;
    // unset($data['id']);

    // $this->variants->firstWhere('id', $this->editForm['id'])->update($this->editForm);
    // $this->emit('update');
    $this->reset('editForm');
    $this->product = $this->product->fresh();
    $this->getVariants();
  }

  // public function update()
  // {
  //   $this->validate([
  //     'editForm.size_id'     => 'required',
  //     'editForm.slug'        => 'required|unique:product_size,slug,' . $this->product_size->id,
  //     'editForm.quantity'    => 'required|numeric|min:1',
  //     'editForm.price'       => 'required|numeric|min:2',
  //     'editForm.offer_price' => 'nullable|lt:editForm.price',
  //   ]);

  //   $prod_size = Pivot::find($this->editForm['id']);

  //   $prod_size->update($this->editForm);

  //   $this->reset(['editForm']);

  //   $this->product = $this->product->fresh();

  //   $this->open = false;
  // }

  // public function delete($id)
  // {

  //   $pivot = Pivot::find($id);

  //   if ($pivot) {
  //     $pivot->delete();
  //   }

  //   $this->product = $this->product->fresh();
  // }

  public function delete($variant_id)
  {
    $record = $this->variants->find($variant_id);

    $record->delete();

    $this->getVariants();
  }

  public function getVariants()
  {
    $this->model_str = [
      Product::VARCOLORS => 'ColorProduct',
      Product::VARSIZES => 'ProductSize',
      Product::VARCOLORSSIZES => 'ColorProductSize',
    ];

    $model_name = '\\App\\Models\\' . $this->model_str[$this->type_variant];
    $this->model = new $model_name;
    $this->variants = $this->model::where('product_id', $this->product->id)->get();
  }

  public function mount()
  {
    $this->sizes = Size::orderBy('name')->get();
    $this->colors = Color::orderBy('name')->get();

    $this->getVariants();
  }

  public function refreshVariants($newValue)
  {
    $this->resetValidation();
    $this->reset(['createForm', 'editForm', 'slug_color', 'slug_size']);
    $this->type_variant = $newValue;
    $this->type_variant = $this->type_variant;
    $this->product = $this->product->fresh();
    $this->getVariants();
  }

  public function render()
  {
    return view('livewire.admin.upsert-product-variant');
  }
}
