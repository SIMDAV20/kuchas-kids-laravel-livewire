<?php

namespace App\Http\Livewire\Admin;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Livewire\Component;

class AttributeComponent extends Component
{
    public $attributes;
    public $name; // for creating new attribute
    
    // For editing/adding options
    public $selectedAttributeId;
    public $optionValue;
    public $optionHex;

    protected $rules = [
        'name' => 'required|unique:attributes,name',
    ];

    public function mount()
    {
        $this->getAttributes();
    }

    public function getAttributes()
    {
        $this->attributes = Attribute::with('options')->get();
    }

    public function saveAttribute()
    {
        $this->validate();

        Attribute::create(['name' => $this->name]);

        $this->reset('name');
        $this->getAttributes();
        $this->emit('saved');
    }

    public function editOptions($attributeId)
    {
        $this->selectedAttributeId = $attributeId;
        $this->reset(['optionValue', 'optionHex']);
    }

    public function saveOption()
    {
        $this->validate([
            'optionValue' => 'required',
            'selectedAttributeId' => 'required'
        ]);

        AttributeOption::create([
            'attribute_id' => $this->selectedAttributeId,
            'value' => $this->optionValue,
            'hex' => $this->optionHex,
        ]);

        $this->reset(['optionValue', 'optionHex']);
        $this->getAttributes();
    }

    public function deleteOption($optionId)
    {
        AttributeOption::find($optionId)->delete();
        $this->getAttributes();
    }

    public function deleteAttribute($attributeId)
    {
        Attribute::find($attributeId)->delete();
        $this->getAttributes();
    }

    public function render()
    {
        return view('livewire.admin.attribute-component')->layout('layouts.admin');
    }
}
