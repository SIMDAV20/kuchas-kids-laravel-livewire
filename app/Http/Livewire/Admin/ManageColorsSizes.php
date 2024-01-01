<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ManageColorsSizes extends Component
{

    const Colors = 'colores';
    const Sizes = 'Tallas';

    public $variants = [self::Colors, self::Sizes];
    public $selected = self::Colors;


    public $createForm = [
        'name' => null,
        'slug' => null,
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null,
    ];


    public function save()
    {
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function render()
    {
        return view('livewire.admin.manage-colors-sizes')->layout('layouts.admin');
    }
}
