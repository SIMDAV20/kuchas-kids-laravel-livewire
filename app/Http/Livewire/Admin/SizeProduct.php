<?php

namespace App\Http\Livewire\Admin;

use App\Models\Size;
use App\Models\ProductSize as Pivot;
use Illuminate\Support\Str;

use Livewire\Component;
// TODO: A BORRAR
class SizeProduct extends Component
{
    public $product, $sizes, $open = false, $product_size, $slug;

    protected $listeners = ['delete'];

    public $createForm = [
        'size_id' => null,
        'slug' => null,
        'quantity' => null,
        'price' => null,
        'offer_price' => null,
    ];

    public $editForm = [
        'id' => -1, // id del ProductSize
        'size_id' => -1,
        'slug' => null,
        'quantity' => null,
        'price' => null,
        'offer_price' => null,
    ];

    protected $validationAttributes = [
        'createForm.size_id'     => 'talla',
        'createForm.slug'        => 'slug',
        'createForm.quantity'    => 'cantidad',
        'createForm.price'       => 'precio',
        'createForm.offer_price' => 'precio oferta',

        'editForm.size_id'     => 'talla',
        'editForm.slug'        => 'slug',
        'editForm.quantity'    => 'cantidad',
        'editForm.price'       => 'precio',
        'editForm.offer_price' => 'precio oferta',
    ];

    protected $rules = [
        'createForm.size_id'     => 'required',
        'createForm.slug'        => 'required||unique:product_size,slug',
        'createForm.quantity'    => 'required|numeric|min:1',
        'createForm.price'       => 'required|numeric|min:2',
        'createForm.offer_price' => 'nullable|lt:createForm.price',
    ];

    public function save()
    {
        $this->validate();

        $offer = $this->createForm['offer_price'];

        $this->createForm['offer_price'] = $offer > 0 ? $offer : null;

        Pivot::create([
            'product_id'  => $this->product->id,
            'size_id'     => $this->createForm['size_id'],
            'slug'        => $this->createForm['slug'],
            'quantity'    => intval($this->createForm['quantity']),
            'price'       => $this->createForm['price'],
            'offer_price' => $this->createForm['offer_price'],
        ]);

        $this->reset('createForm');

        $this->emit('saved');

        $this->product = $this->product->fresh();
    }

    public function edit($id)
    {
        $this->resetValidation();
        $product_size = Pivot::find($id);
        $this->open = true;
        $this->product_size = $product_size;
        $this->editForm['id']           = $product_size->id;
        $this->editForm['size_id']      = $product_size->size_id;
        $this->editForm['slug']         = $product_size->slug;

        $this->editForm['quantity']     = $product_size->quantity;
        $this->editForm['price']        = $product_size->price;
        $this->editForm['offer_price']  = $product_size->offer_price;
    }

    public function updatingCreateFormSizeId($id)
    {
        // $prod_size = Pivot::find($id);
        $this->createForm['slug'] = Str::slug($this->product->slug . '-' . Size::find($id)->name) ?: '';
    }

    public function updatingEditFormSizeId($id)
    {
        $prod_size = Pivot::find($id);
        $this->editForm['slug'] = Str::slug($this->product->slug . '-' . Size::find($prod_size->size_id)->name) ?: '';
    }

    public function update()
    {
        $this->validate([
            'editForm.size_id'     => 'required',
            'editForm.slug'        => 'required|unique:product_size,slug,' . $this->product_size->id,
            'editForm.quantity'    => 'required|numeric|min:1',
            'editForm.price'       => 'required|numeric|min:2',
            'editForm.offer_price' => 'nullable|lt:editForm.price',
        ]);

        $prod_size = Pivot::find($this->editForm['id']);

        $prod_size->update($this->editForm);

        $this->reset(['editForm']);

        $this->product = $this->product->fresh();

        $this->open = false;
    }

    public function delete($id)
    {

        $pivot = Pivot::find($id);

        if ($pivot) {
            $pivot->delete();
        }

        $this->product = $this->product->fresh();
    }

    public function mount()
    {
        $this->sizes = Size::orderBy('name')->get();
    }

    public function render()
    {
        $product_sizes = $this->product->sizes;
        return view('livewire.admin.size-product', compact('product_sizes'));
    }
}
