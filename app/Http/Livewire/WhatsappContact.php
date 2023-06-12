<?php

namespace App\Http\Livewire;

use App\Models\Color;
use App\Models\Size;
use Livewire\Component;

class WhatsappContact extends Component
{

    public $product, $color_id = null, $size_id = null;

    public $mensaje = '';

    // protected $listeners = ['update_wsp'];

    // public function update_wsp($value)
    // {
    //     $this->size = $value['name'];
    //     $this->mensaje = '';
    //     if ($this->product !== null) {
    //         $this->mensaje .= ' Deseo información sobre *' . $this->product->name;
    //     }
    //     if ($this->size !== null) {
    //         $this->mensaje .= ' ' . $this->size;
    //     }
    //     $this->mensaje .= '*';
    // }

    public function mount()
    {
        $this->mensaje = '';
        if ($this->product !== null) {
            $this->mensaje .= ' Deseo información sobre *' . $this->product->name;

            if ($this->color_id !== null) {
                $this->mensaje .= ' ' . Color::where('id', $this->color_id)->first()->name;
            }

            if ($this->size_id !== null) {
                $this->mensaje .= ' ' . Size::where('id', $this->size_id)->first()->name;
            }
        }
        $this->mensaje .= '*';
    }

    public function render()
    {
        return view('livewire.whatsapp-contact');
    }
}
