<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use Livewire\Component;
use Illuminate\Support\Str;

class BrandComponent extends Component
{
    public $brands, $brand;

    protected $listeners = ['delete'];

    protected $rules = [ // solo para el create brand
        'createForm.name' => 'required|unique:brands,name',
        'createForm.slug' => 'required|unique:brands,slug',
    ];

    protected $validationAttributes = [ // para el create and edit
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',

        'editForm.name'   => 'nombre',
        'editForm.slug'   => 'slug',
    ];

    public $createForm = [
        'name' => null,
        'slug' => null
    ];

    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null
    ];

    public function mount() {
        $this->getBrands();
    }

    public function getBrands() {
        $this->brands = Brand::all();
    }

    public function updatingCreateFormName($value) {
        $this->createForm['slug'] = Str::slug($value);
    }

    public function updatingEditFormName($value) {
        $this->editForm['slug'] = Str::slug($value);
    }

    public function save() {
        $this->validate();
        Brand::create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');
        $this->getBrands();
    }

    public function edit(Brand $brand) {
        $this->resetValidation();
        $this->brand = $brand;
        $this->editForm['open'] = true;
        $this->editForm['name'] = $brand->name;
        $this->editForm['slug'] = $brand->slug;
    }

    public function update() {
        $this->validate([
            'editForm.name' => 'unique:brands,name,'.$this->brand->name,
            'editForm.slug' => 'unique:brands,slug,'.$this->brand->slug,
        ]);

        $this->brand->update($this->editForm);

        $this->reset('editForm');
        $this->getBrands();
    }

    public function delete(Brand $brand) {
        $brand->delete();
        $this->getBrands();
    }

    public function render()
    {
        return view('livewire.admin.brand-component')->layout('layouts.admin');
    }
}
