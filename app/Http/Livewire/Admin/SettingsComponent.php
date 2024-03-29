<?php

namespace App\Http\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingsComponent extends Component
{
  public $setting, $show_headband = false;

  public $editForm = [
    'id' => -1,
    'min_amount' => null,
    'headband_one' => null,
    'headband_two' => null,
  ];

  protected $rules = [
    'editForm.min_amount' => 'numeric|min:0',
  ];

  protected $validationAttributes = [
    'editForm.min_amount' => 'monto mínimo',
  ];

  public function updatingShowHeadband($value)
  {
    if ($value) {
      $this->emit('show_headband', "mostrará");
    } else {
      $this->emit('show_headband', "ha ocultado");
      $this->editForm['min_amount'] = 0;
      $this->update();
    }

    $this->setting->update([
      'show_headband' => $value
    ]);
  }

  public function mount()
  {
    @$this->setting = Setting::first();
    if (isset($this->setting)) {
      $this->editForm['id'] = $this->setting->id;
      $this->editForm['min_amount'] = $this->setting->min_amount;
      $this->editForm['headband_one'] = $this->setting->headband_one;
      $this->editForm['headband_two'] = $this->setting->headband_two;
      $this->show_headband = $this->setting->show_headband;
    }
  }

  public function update()
  {
    $this->validate();
    $this->setting->update($this->editForm);
    $this->emit('saved');
    $this->mount();
  }

  public function render()
  {
    return view('livewire.admin.settings-component')->layout('layouts.admin');
  }
}
