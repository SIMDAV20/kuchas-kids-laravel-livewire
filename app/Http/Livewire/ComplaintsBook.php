<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComplaintNotification;

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

        $settings = Setting::first();
        $adminEmail = $settings->email_receive;

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new ComplaintNotification([
                'fullName' => $this->fullName,
                'documentId' => $this->documentId,
                'phone' => $this->phone,
                'email' => $this->email,
                'type' => $this->type,
                'orderNumber' => $this->orderNumber,
                'claimedAmount' => $this->claimedAmount,
                'description' => $this->description,
                'consumerRequest' => $this->consumerRequest,
            ]));
        }

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
