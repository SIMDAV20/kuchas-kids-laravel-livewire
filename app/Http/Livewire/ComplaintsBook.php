<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ComplaintsBook extends Component
{
    public $fullName;
    public $documentId;
    public $phone;
    public $email;
    public $type = 'Reclamo';
    public $orderNumber;
    public $claimedAmount;
    public $description;
    public $consumerRequest;

    public $successMessage = false;

    protected $rules = [
        'fullName' => 'required|string|max:255',
        'documentId' => 'required|string|max:20',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'type' => 'required|in:Reclamo,Queja',
        'orderNumber' => 'nullable|string|max:50',
        'claimedAmount' => 'nullable|numeric|min:0',
        'description' => 'required|string|max:1000',
        'consumerRequest' => 'required|string|max:1000',
    ];

    public function submit()
    {
        $this->validate();

        // En un caso real se guardaría en BD o se enviaría por correo.
        // Simulamos éxito para el libro de reclamaciones.
        $this->successMessage = true;

        $this->reset([
            'fullName', 'documentId', 'phone', 'email', 'type', 
            'orderNumber', 'claimedAmount', 'description', 'consumerRequest'
        ]);
        
        $this->type = 'Reclamo';
    }

    public function render()
    {
        return view('livewire.complaints-book')->layout('layouts.app');
    }
}
