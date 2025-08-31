<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class PagoRecibido extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;

    public function __construct($pago)
    {
        $this->pago = $pago;
    }

    public function build()
    {
        return $this->subject('Nuevo pago recibido - Sofnica')
                    ->view('emails.pago');
    }
}
