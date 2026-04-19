<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ChangeStatusProduct extends Component
{
    public $item_id, $item, $prod_status, $model = 'Product';

    public function updatingProdStatus($value)
    {
        switch ($this->model) {
            case 'Product':
                $this->item->status = $value ? Product::PUBLICADO : Product::BORRADOR;
                break;

            case 'ProductVariant':
                // En variantes usualmente es booleano (Activo/Inactivo)
                $this->item->status = $value;
                break;

            case 'FlashOffer':
                // En ofertas flash es booleano (Activo/Inactivo)
                $this->item->status = $value;
                break;
        }

        $this->item->save();
    }

    public function mount()
    {
        switch ($this->model) {
            case 'Product':
                $this->item = Product::findOrFail($this->item_id);
                $this->prod_status = $this->item->status == Product::PUBLICADO;
                break;

            case 'ProductVariant':
                $this->item = \App\Models\ProductVariant::findOrFail($this->item_id);
                $this->prod_status = (bool) $this->item->status;
                break;

            case 'FlashOffer':
                $this->item = \App\Models\FlashOffer::findOrFail($this->item_id);
                $this->prod_status = (bool) $this->item->status;
                break;
        }
    }

    public function render()
    {
        return view('livewire.admin.change-status-product');
    }
}
