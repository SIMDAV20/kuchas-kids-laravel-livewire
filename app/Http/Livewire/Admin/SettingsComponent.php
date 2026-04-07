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
    'whatsapp' => null,
    'email_receive' => null,
    'email_client' => null,
    'business_name' => null,
    'trade_name' => null,
    'ruc' => null,
  ];

  protected $rules = [
    'editForm.min_amount' => 'numeric|min:0',
    'editForm.whatsapp' => 'nullable|string|max:20|regex:/^\d+$/',
    'editForm.email_receive' => 'nullable|email|max:255',
    'editForm.email_client' => 'nullable|email|max:255',
    'editForm.business_name' => 'nullable|string|max:255',
    'editForm.trade_name' => 'nullable|string|max:255',
    'editForm.ruc' => 'nullable|string|max:20',
  ];

  protected $validationAttributes = [
    'editForm.min_amount' => 'monto mínimo',
    'editForm.whatsapp' => 'WhatsApp',
    'editForm.email_receive' => 'correo para recibir mensajes',
    'editForm.email_client' => 'correo para clientes',
    'editForm.business_name' => 'Razón social',
    'editForm.trade_name' => 'Nombre comercial',
    'editForm.ruc' => 'RUC',
  ];

  protected $messages = [
    'editForm.whatsapp.regex' => 'El número de WhatsApp solo debe contener dígitos.',
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
      $this->editForm['whatsapp'] = $this->setting->whatsapp;
      $this->editForm['email_receive'] = $this->setting->email_receive;
      $this->editForm['email_client'] = $this->setting->email_client;
      
      $company_info = $this->setting->company_info;
      $this->editForm['business_name'] = $company_info['business_name'] ?? null;
      $this->editForm['trade_name'] = $company_info['trade_name'] ?? null;
      $this->editForm['ruc'] = $company_info['ruc'] ?? null;

      $this->show_headband = $this->setting->show_headband;
    }
  }

  public function update()
  {
    $this->validate();

    $this->setting->update([
      'min_amount' => $this->editForm['min_amount'],
      'headband_one' => $this->editForm['headband_one'],
      'headband_two' => $this->editForm['headband_two'],
      'whatsapp' => $this->editForm['whatsapp'],
      'email_receive' => $this->editForm['email_receive'],
      'email_client' => $this->editForm['email_client'],
      'company_info' => [
        'business_name' => $this->editForm['business_name'] ?? null,
        'trade_name' => $this->editForm['trade_name'] ?? null,
        'ruc' => $this->editForm['ruc'] ?? null,
      ]
    ]);

    $this->emit('saved');
    $this->mount();
  }

  public function render()
  {
    return view('livewire.admin.settings-component')->layout('layouts.admin');
  }
}
