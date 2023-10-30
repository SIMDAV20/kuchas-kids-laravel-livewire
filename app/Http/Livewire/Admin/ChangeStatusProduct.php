<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorProduct;
use App\Models\ColorProductSize;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Support\Facades\DB;
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

      case 'ColorProduct':
        $this->item->status =  $value ? ColorProduct::PUBLICADO : ColorProduct::BORRADOR;
        break;

      case 'ProductSize':
        $this->item->status =  $value ? ProductSize::PUBLICADO : ProductSize::BORRADOR;
        break;

      case 'ColorProductSize':
        $this->item->status =  $value ? ColorProductSize::PUBLICADO : ColorProductSize::BORRADOR;
        break;
    }

    $this->item->save();
  }

  public function mount()
  {
    switch ($this->model) {
      case 'Product':
        $this->item = Product::find($this->item_id);
        break;

      case 'ColorProduct':
        $this->item = ColorProduct::find($this->item_id);
        break;

      case 'ProductSize':
        $this->item = ProductSize::find($this->item_id);
        break;

      case 'ColorProductSize':
        $this->item = ColorProductSize::find($this->item_id);
        break;
    }
    $this->prod_status = $this->item->status == 2 ? true : false;
  }

  public function render()
  {
    return view('livewire.admin.change-status-product');
  }
}
