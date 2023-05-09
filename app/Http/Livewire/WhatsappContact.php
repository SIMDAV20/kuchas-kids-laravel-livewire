<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WhatsappContact extends Component
{

    public $product, $color, $size;

    public $mensaje = '';

    protected $listeners = ['update_wsp'];

    public function update_wsp($value)
    {
        $this->size = $value['name'];
        $this->mensaje = '';
        if ($this->product !== null) {
            $this->mensaje .= ' Deseo información sobre *' . $this->product->name;
        }
        if ($this->size !== null) {
            $this->mensaje .= ' ' . $this->size;
        }
        $this->mensaje .= '*';
    }

    public function mount()
    {
        $this->mensaje = '';
        if ($this->product !== null) {
            $this->mensaje .= ' Deseo información sobre *' . $this->product->name;
            if (count($this->product->color_product)) {
                $this->mensaje .= ' ' . $this->color->name;
            }
            // else if (count($this->product->product_size)) {
            //     // buscar la primera talla donde

            // } else if (count($this->product->color_product_size)) {
            // }
            if ($this->size !== null) {
                $this->mensaje .= ' ' . $this->size;
            }
        }


        $this->mensaje .= '*';
    }

    public function render()
    {
        return view('livewire.whatsapp-contact');
    }
}
