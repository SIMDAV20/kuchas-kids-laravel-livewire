<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

// Ensure the OrderItem model exists in the App\Models namespace

class UpdateOrderItem extends Component
{
    public $orderItemId, $cantidad, $stockDisponible;

    public function mount($orderItem)
    {
        $this->orderItemId = $orderItem->id;
        $this->cantidad = $orderItem->qty;
        $this->stockDisponible = Product::find($orderItem->id)->stock;
    }

    public function updatedCantidad($value)
    {
        if ($value < 1) {
            $this->cantidad = 1;
        } elseif ($value > $this->stockDisponible) {
            $this->cantidad = $this->stockDisponible;
        } else {
            Order::where('id', $this->orderItemId)->update(['qty' => $this->cantidad]);

            $this->emit('ordenActualizada');
        }
    }


    public function render()
    {
        return view('livewire.update-order-item');
    }
}
