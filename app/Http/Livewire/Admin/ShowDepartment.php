<?php

namespace App\Http\Livewire\Admin;

use App\Models\Province;
use App\Models\Department;
use Livewire\Component;

class ShowDepartment extends Component
{
    public $department, $provinces, $province;

    protected $listeners = ['delete'];

    protected $rules = [
        'createForm.name' => 'required|unique:provinces,name',
        'createForm.cost' => 'required|numeric|min:1|max:200',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.cost' => 'costo de envío',
        'editForm.name'   => 'nombre',
        'editForm.cost'   => 'costo de envío',
    ];

    public $createForm = [
        'name' => '',
        'cost' => null
    ];

    public $editForm = [
        'open' => false,
        'name' => '',
        'cost' => null
    ];

    public function getProvinces()
    {
        $this->provinces = Province::where('department_id', $this->department->id)->get();
    }

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->getProvinces();
    }

    public function save()
    {
        $this->validate();
        $this->department->Provinces()->create($this->createForm);

        $this->reset('createForm');
        $this->emit('saved');
        $this->getProvinces();
    }

    public function edit(City $province)
    {
        $this->resetValidation();
        $this->city = $province;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $province->name;
        $this->editForm['cost'] = $province->cost;
    }

    public function update()
    {
        $this->validate([
            'editForm.name' => "required|unique:provinces,name,{$this->city->id}",
            'editForm.cost' => 'required|numeric|min:1|max:200',
        ]);
        // $this->city->name = $this->editForm['name'];
        // $this->city->cost = $this->editForm['cost'];
        $this->city->update($this->editForm);

        $this->reset('editForm');
        $this->getProvinces();
    }

    public function delete(City $province)
    {
        $province->delete();
        $this->getProvinces();
    }

    public function render()
    {
        return view('livewire.admin.show-department')->layout('layouts.admin');
    }
}
