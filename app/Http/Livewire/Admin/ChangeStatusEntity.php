<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class ChangeStatusEntity extends Component
{
  public $entity, $entity_status;

  public function updatingEntityStatus($value)
  {
    $this->entity->status = $value ? 1 : 0;
    $this->entity->save();
  }

  public function mount()
  {
    $this->entity_status = $this->entity->status == 1 ? true : false;
  }

  public function render()
  {
    return view('livewire.admin.change-status-entity');
  }
}
