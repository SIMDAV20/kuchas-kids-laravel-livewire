<?php

namespace App\Http\Livewire;

use App\Models\Size;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Gloudemans\Shoppingcart\Facades\Cart;

class OldAddCartItemSize extends Component
{
    public $product, $sizes;
    public $qty = 1;
    public $quantity = 0;

    public $colors = [];
    public $options = [];

    public $size;
    public $key;

    protected $listeners = [
        'update_stock' => 'update_stock',
    ];

    public function update_stock($arr)
    {
        $this->quantity = $this->product->product_size[$arr['key']]->quantity;
        $this->key = $arr['key'];
        $this->size = Size::find($arr['size']['id']);
        $this->options['size']    = $this->size->name;
        $this->options['size_id'] = $this->size->id;
        $this->options['image']   = Storage::url($this->product->images->first()->url);
    }

    public function mount()
    {
        foreach ($this->product->product_size as $key => $prod_size) {
            if ($prod_size->quantity > 0) { // el primero que encuentra q tiene stock
                $this->key = $key;
                $this->quantity = $prod_size->quantity;
                $size_id = $this->product->product_size[$key]->size_id;
                $this->size = Size::find($size_id);
                break;
            }
        }
        $this->options['size'] =    $this->size->name;
        $this->options['size_id'] = $this->size->id;
        $this->options['image'] = Storage::url($this->product->images->first()->url);
    }
    public function decrement()
    {
        $this->qty = $this->qty - 1;
    }
    public function increment()
    {
        $this->qty = $this->qty + 1;
    }
    public function updatingSizeId($value)
    {
        $size = $this->product->sizes->find($value);
        $this->quantity = qty_available($this->product->id, null, $size->id);
        $this->options['size'] = $size->name;
        $this->options['size_id'] = $size->id;
        $this->emitTo('whatsapp-contact', 'update_wsp', $size);
    }
    public function addItem()
    {

        $price = 0;
        $prod_size = $this->product->product_size[$this->key];
        if (
            $prod_size->offer_price > 0 &&
            (Carbon::parse($prod_size->offer_date)->format('Y-m-d') >= Carbon::now()->format('Y-m-d')) &&
            $prod_size->offer_date !== null
        ) {
            $price = $prod_size->offer_price;
            $this->options['base_price'] = $prod_size->price;

            // SI LA FECHA LIMITE ES INDEFINIDO
        } else if ($prod_size->offer_price > 0 && $prod_size->offer_date == null) {
            $price = $prod_size->offer_price;
            $this->options['base_price'] = $prod_size->price;
        } else {
            $price = $prod_size->price;
        }

        Cart::add([
            'id'          => $this->product->id,
            'name'        => $this->product->name,
            'qty'         => $this->qty,
            'price'       => $price,
            'weight'      => 550,
            'options'     => $this->options
        ]);
        // actualizar el stock
        $this->quantity = qty_available($this->product->id, null, $this->size->id);

        // refrescar el qty a t1
        $this->reset('qty');

        // Hago un evento para comunicar entre componentes
        $this->emitTo('dropdown-cart', 'render');
        $this->emitTo('cart-mobil', 'render');
    }
    public function render()
    {
        $this->updatingSizeId($this->size->id);
        return view('livewire.add-cart-item-size');
    }
}
