<?php

namespace App\Http\Livewire\Admin;

use App\Models\District;
use App\Models\Zone;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryZone extends Component
{
    use WithPagination;

    public $zones = [], $editZone;
    public $districtsLoaded = false;
    public $search = '';
    public $editSearch = '';

    protected $listeners = ['delete'];

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
        'createForm.name' => 'required|unique:zones,name',
        'createForm.cost' => 'required|min:1|max:1000',
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
    }

    public function loadDistricts()
    {
        $this->districtsLoaded = true;
    }

    public function updatingSearch()
    {
        $this->resetPage('page');
    }

    public function updatingEditSearch()
    {
        $this->resetPage('editPage');
    }

    public function getZones()
    {
        $this->zones = Zone::all();
    }

    public function save()
    {
        $this->validate();

        $zone = Zone::create([
            'name' => $this->createForm['name'],
            'cost' => $this->createForm['cost'],
        ]);

        foreach ($this->createForm['districts'] as $dist_id) {
            $district = District::find($dist_id);
            $district->zone_id = $zone->id;
            $district->save();
        }

        $this->reset('createForm');
        $this->search = '';
        $this->resetPage('page');
        $this->getZones();
        $this->emit('saved');
    }

    public function edit(Zone $zone)
    {
        $this->resetValidation();
        $this->editZone = $zone;

        $this->editForm['open']      = true;
        $this->editForm['name']      = $zone->name;
        $this->editForm['cost']      = $zone->cost;
        $this->editForm['districts'] = $zone->districts->pluck('id')->toArray() ?: [];
    }

    public function update()
    {
        $rules = [
            'editForm.name'      => 'required|unique:zones,name,' . $this->editZone->id,
            'editForm.cost'      => 'required|min:1|max:100',
            'editForm.districts' => 'required'
        ];

        $this->validate($rules);

        $this->editZone->update($this->editForm);

        foreach ($this->editForm['districts'] as $distrito_id) {
            $distrito = District::find($distrito_id);
            if ($distrito) {
                $distrito->zone_id = $this->editZone->id;
                $distrito->save();
            }
        }

        $this->reset('editForm');
        $this->editSearch = '';
        $this->resetPage('editPage');
        $this->getZones();
    }

    public function delete(Zone $zone)
    {
        $zone->districts()->update(['zone_id' => null]);
        $zone->delete();
        $this->getZones();
    }

    public function render()
    {
        $districts = $this->districtsLoaded
            ? District::orderBy('name')
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->paginate(30, ['*'], 'page')->onEachSide(1)
            : District::whereRaw('0=1')->paginate(30, ['*'], 'page')->onEachSide(1);

        $editDistricts = ($this->editForm['open'] && $this->districtsLoaded)
            ? District::orderBy('name')
                ->when($this->editSearch, fn($q) => $q->where('name', 'like', "%{$this->editSearch}%"))
                ->paginate(30, ['*'], 'editPage')->onEachSide(1)
            : District::whereRaw('0=1')->paginate(30, ['*'], 'editPage')->onEachSide(1);

        return view('livewire.admin.delivery-zone', compact('districts', 'editDistricts'))
            ->layout('layouts.admin');
    }
}
