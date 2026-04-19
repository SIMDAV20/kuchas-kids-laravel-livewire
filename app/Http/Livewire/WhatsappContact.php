<?php

namespace App\Http\Livewire;

use Livewire\Component;

class WhatsappContact extends Component
{
    public $product;

    public $mensaje = '';

    public function mount()
    {
        $this->mensaje = '';
        if ($this->product !== null) {
            $this->mensaje .= ' Deseo información sobre *' . $this->product->name;
        }
        $this->mensaje .= '*';
    }

    public function render()
    {
        return view('livewire.whatsapp-contact');
    }
}
