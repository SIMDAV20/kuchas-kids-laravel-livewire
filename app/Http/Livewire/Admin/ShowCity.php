<?php

namespace App\Http\Livewire\Admin;

use App\Models\Province;
use App\Models\District;
use Livewire\Component;

class ShowCity extends Component
{
    public $province, $districts, $district;

    protected $listeners = ['delete'];

    protected $rules = [
        'createForm.name' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'editForm.name'   => 'nombre',
    ];

    public $createForm = [
        'name' => '',
    ];

    public $editForm = [
        'open' => false,
        'name' => '',
    ];

    public function getDistricts()
    {
        $this->districts = District::where('province_id', $this->city->id)->get();
    }

    public function mount(City $province)
    {
        $this->city = $province;
        $this->getDistricts();
    }

    public function save()
    {
        $this->validate();
        $this->city->districts()->create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');
        $this->getDistricts();
    }

    public function edit(District $district)
    {
        $this->resetValidation();
        $this->district = $district;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $district->name;
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => "required",
        ]);
        $this->district->update($this->editForm);

        $this->reset('editForm');
        $this->getDistricts();
    }

    public function delete(District $district)
    {
        $district->delete();
        $this->getDistricts();
    }
    public function render()
    {
        return view('livewire.admin.show-city')->layout('layouts.admin');
    }
}
