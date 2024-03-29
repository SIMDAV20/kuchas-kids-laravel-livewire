<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorProduct as Pivot;
use Illuminate\Support\Str;

use Livewire\Component;

class ColorProduct extends Component
{

  public $product, $colors, $color_id, $quantity;

  protected $listeners = ['delete'];

  public $createForm = [
    'color_id' => null,
    'slug' => null,
    'quantity' => null,
    'price' => null,
    'offer_price' => null,
  ];

  public $editForm = [
    'open' => false,
    'id' => -1, // id del ProductSize
    'color_id' => -1,
    'slug' => null,
    'quantity' => null,
    'price' => null,
    'offer_price' => null,
  ];

  protected $validationAttributes = [
    'createForm.size_id'     => 'color',
    'createForm.slug'        => 'slug',
    'createForm.quantity'    => 'cantidad',
    'createForm.price'       => 'precio',
    'createForm.offer_price' => 'precio oferta',

    'editForm.size_id'     => 'color',
    'editForm.slug'        => 'slug',
    'editForm.quantity'    => 'cantidad',
    'editForm.price'       => 'precio',
    'editForm.offer_price' => 'precio oferta',
  ];

  protected $rules = [
    'createForm.color_id'     => 'required',
    'createForm.slug'        => 'required||unique:color_product,slug',
    'createForm.quantity'    => 'required|numeric|min:1',
    'createForm.price'       => 'required|numeric|min:2',
    'createForm.offer_price' => 'nullable|lt:createForm.price',
  ];


  public function save()
  {
    $this->validate();

    $offer = $this->createForm['offer_price'];
    $this->createForm['offer_price'] = $offer > 0 ? $offer : null;

    $pivot = Pivot::where('color_id', $this->color_id)
      ->where('product_id', $this->product->id)
      ->first();

    if ($pivot) {
      $pivot->quantity += $this->quantity;
      $pivot->save(); // se aumenta el quantity
    } else {
      // attach sirve para introducir un registro en la tabla intermedia
      $this->product->colors()->attach([
        $this->color_id => [
          'quantity' => $this->quantity,
          'slug' => Str::slug($this->product->slug . '-' . Color::find($this->color_id)->name)
        ],
      ]);
    }

    $this->reset(['createForm']);

    $this->emit('saved'); // para el mensaje

    $this->product = $this->product->fresh();
  }

  public function edit($pivot_id)
  {
    $pivot = Pivot::find($pivot_id);

    $this->editForm['open'] = true;
    $this->editForm['color_id'] = $pivot->color_id;
    $this->editForm['quantity'] = $pivot->quantity;
  }

  public function updatingCreateFormColorId($id)
  {
    $this->createForm['slug'] = Str::slug($this->product->slug . '-' . Color::find($id)->name) ?: '';
  }

  public function updatingEditFormColorId($id)
  {
    $prod_color = Pivot::find($id);
    $this->editForm['slug'] = Str::slug($this->product->slug . '-' . Color::find($prod_color->size_id)->name) ?: '';
  }

  public function update()
  {
    $this->pivot->color_id = $this->pivot_color_id;
    $this->pivot->quantity = $this->pivot_quantity;

    $this->pivot->save();
    $this->product = $this->product->fresh();

    $this->editForm['open'] = false;
    $this->resetValidation(['editForm']);
  }

  public function delete(Pivot $pivot)
  {
    $pivot->delete();

    $this->product = $this->product->fresh();
  }

  public function mount()
  {
    $this->colors = Color::orderBy('name')->get();
  }

  public function render()
  {
    $color_products = $this->product->colors;
    return view('livewire.admin.color-product', compact('color_products'));
  }
}
