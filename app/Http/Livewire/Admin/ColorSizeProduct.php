<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorProductSize as Pivot;
use App\Models\Size;
use Livewire\Component;

class ColorSizeProduct extends Component
{
    public $product, $colors, $sizes, $color_product_size, $open = false;

    protected $listeners = ['delete'];

    public $createForm = [
        'color_id'    => null,
        'size_id'     => null,
        'slug'        => null,
        'quantity'    => null,
        'price'       => null,
        'offer_price' => null,
    ];

    public $editForm = [
        'id'          => -1, // id del ColorProductSize
        'color_id'    => -1,
        'size_id'     => -1,
        'slug'        => null,
        'quantity'    => null,
        'price'       => null,
        'offer_price' => null,
    ];

    protected $validationAttributes = [
        'createForm.color_id'    => 'color',
        'createForm.size_id'     => 'talla',
        'createForm.slug'        => 'slug',
        'createForm.quantity'    => 'cantidad',
        'createForm.price'       => 'precio',
        'createForm.offer_price' => 'precio oferta',

        'editForm.color_id'     => 'color',
        'editForm.size_id'     => 'talla',
        'editForm.slug'        => 'slug',
        'editForm.quantity'    => 'cantidad',
        'editForm.price'       => 'precio',
        'editForm.offer_price' => 'precio oferta',
    ];

    public function save()
    {
        $this->validate();

        $offer = $this->createForm['offer_price'];

        $this->createForm['offer_price'] = $offer > 0 ? $offer : null;

        Pivot::create([
            'product_id'  => $this->product->id,
            'color_id'    => $this->createForm['color_id'],
            'size_id'     => $this->createForm['size_id'],
            'slug'        => $this->createForm['slug'],
            'quantity'    => intval($this->createForm['quantity']),
            'price'       => $this->createForm['price'],
            'offer_price' => $this->createForm['offer_price'],
        ]);

        $this->reset(['createForm']);

        $this->emit('saved'); // para el mensaje

        $this->product = $this->product->fresh();
    }

    public function edit($id)
    {

        $this->resetValidation();
        $color_product_size = Pivot::find($id);
        $this->open = true;
        $this->color_product_size = $color_product_size;
        $this->editForm['id']           = $color_product_size->id;
        $this->editForm['color_id']     = $color_product_size->color_id;
        $this->editForm['size_id']      = $color_product_size->size_id;
        $this->editForm['slug']         = $color_product_size->slug;

        $this->editForm['quantity']     = $color_product_size->quantity;
        $this->editForm['price']        = $color_product_size->price;
        $this->editForm['offer_price']  = $color_product_size->offer_price;
    }

    public function update()
    {
        $this->pivot->color_id = $this->pivot_color_id;
        $this->pivot->quantity = $this->pivot_quantity;

        $this->pivot->save();

        $this->product = $this->product->fresh();

        $this->open = false;
    }

    public function delete(Pivot $pivot)
    {
        $pivot->delete();

        $this->product = $this->product->fresh();
    }

    public function mount()
    {
        $this->colors = Color::OrderBy('name')->get();
        $this->sizes  = Size::OrderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.admin.color-size-product');
    }
}
