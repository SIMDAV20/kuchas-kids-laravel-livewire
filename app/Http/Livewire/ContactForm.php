<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactNotification;

class ContactForm extends Component
{
    public $contact, $email, $phone, $message;

    protected $rules = [
        'contact' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:255',
        'message' => 'required|string|max:1000',
    ];

    public function submit()
    {
        $this->validate();

        $settings = Setting::first();
        $adminEmail = $settings->email_receive;
        
        Mail::to($adminEmail)->send(new ContactNotification([
            'contact' => $this->contact,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => $this->message,
        ]));

        $this->reset(['contact', 'email', 'phone', 'message']);

        $this->emit('alert', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo lo antes posible.');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
