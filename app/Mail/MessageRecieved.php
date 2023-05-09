<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MessageRecieved extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $msg; //para que este disponible en la vista
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($msg, $subject)
    {

        $this->msg = $msg;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // dd($this->msg['order']);
        //creo la vista, creo en resources/views
        if ($this->subject == 'Nueva Venta Página web Kuchas Kids') {
            return $this->subject($this->subject)->view('emails.new-sale')->with($this->msg);
        }
        if ($this->subject == 'Kuchas-kids Comprobante de Pago') {
            return $this->subject($this->subject)->view('emails.send-file')->with($this->msg);
        }
        // return $this->subject($this->subject)->view('emails.message-received')->with($this->msg);
    }
}
