<?php

namespace App\Http\Livewire\Admin;

use App\Models\District;
use App\Models\Zone;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DeliveryZone extends Component
{

    public $districts = [], $zones = [], $editZone, $editDistricts = [], $editDistrictsBef = [];

    protected  $listeners = ['delete'];

    public $createForm = [
        'name' => null,
        'cost' => 1,
        'districts' => []
    ];

    public $editForm = [
        'name' => null,
        'cost' => 1,
        'districts' => [],
        'open' => false
    ];

    protected $rules = [
        'createForm.name'   => 'required|unique:zones,name',
        'createForm.cost'   => 'required|min:1|max:1000',
    ];

    protected $validationAttributes = [
        'createForm.name'      => 'nombre',
        'createForm.cost'      => 'costo envío',
        'createForm.districts' => 'distritos',

        'editForm.name'      => 'nombre',
        'editForm.costo'     => 'costo envío',
        'editForm.districts' => 'distritos'
    ];

    public function mount()
    {
        $this->getZones();
        $this->getDistricts();
    }

    public function getZones()
    {
        $this->zones = Zone::all();
    }

    public function getDistricts()
    {
        $this->districts = District::orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate();

        $zone = Zone::create([
            'name'  => $this->createForm['name'],
            'cost'  => $this->createForm['cost'],
        ]);

        foreach ($this->createForm['districts'] as $key => $dist_id) {
            $district = District::find($dist_id);
            $district->zone_id = $zone->id;
            $district->save();
        }

        $this->reset('createForm');

        $this->getZones();
        $this->getDistricts();
        $this->emit("saved"); //mensaje de que ha sido creada la zona
    }

    public function edit(Zone $zone)
    {
        $this->resetValidation();
        $this->editZone = $zone;
        // dd($this->editZone);

        $this->editForm['open']  = true;
        $this->editForm['name']  = $zone->name;
        $this->editForm['cost']  = $zone->cost;

        $this->editDistricts = $this->districts;
        // $this->editDistrictsBef = $zone->districts;

        // $districts = $this->getDistricts();
        // $editDistricts = $editDistricts->concat($this->districts);

        // se guardan los ids de cada distrito
        $this->editForm['districts'] = $zone->districts->pluck('id') ?: [];
    }

    public function update()
    {

        $rules = [
            'editForm.name'   => 'required|unique:zones,name,' . $this->editZone->id,
            'editForm.cost'   => 'required|min:1|max:100',
            'editForm.districts' => 'required'
        ];

        $this->validate($rules);

        $this->editZone->update($this->editForm);

        foreach ($this->editForm['districts'] as $key => $distrito_id) { // 15
            $distrito = District::find($distrito_id);
            if ($distrito) {
                $distrito->zone_id = $this->editZone->id;
                $distrito->save();
            }
        }
        $this->reset('editForm');
        $this->mount();
    }

    public function delete(Zone $zone)
    {
        $districts = $zone->districts()->update(['zone_id' => null]);
        $zone->delete();
        $this->mount();
    }

    public function render()
    {
        return view('livewire.admin.delivery-zone')->layout('layouts.admin');;
    }
}
