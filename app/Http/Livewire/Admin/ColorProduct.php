<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorProduct as Pivot;
use Illuminate\Support\Str;

use Livewire\Component;

class ColorProduct extends Component
{

    public $product, $colors, $color_id, $quantity, $open = false;

    public $pivot, $pivot_color_id, $pivot_quantity;

    protected $listeners = ['delete'];

    protected $rules = [
        'color_id' => 'required',
        'quantity' => 'required|numeric|min:1'
    ];

    protected $validationAttributes = [
        'color_id' => 'color',
        'quantity' => 'cantidad'
    ];

    public function save()
    {
        $this->validate();

        $pivot = Pivot::where('color_id', $this->color_id)
            ->where('product_id', $this->product->id)
            ->first();

        if ($pivot) {
            $pivot->quantity = $pivot->quantity + $this->quantity;
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

        $this->reset(['color_id', 'quantity']);

        $this->emit('saved'); // para el mensaje

        $this->product = $this->product->fresh();
    }

    public function edit($pivot_id)
    {
        $pivot = Pivot::find($pivot_id);

        $this->open = true;

        $this->pivot = $pivot;
        $this->pivot_color_id = $pivot->color_id;
        $this->pivot_quantity = $pivot->quantity;
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
        $this->colors = Color::orderBy('name')->get();
    }

    public function render()
    {
        $product_colors = $this->product->colors;

        return view('livewire.admin.color-product', compact('product_colors'));
    }
}
