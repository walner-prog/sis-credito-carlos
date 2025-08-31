<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SolicitarDemo extends Component
{


    public function clearSuccess()
{
    $this->successMessage = null;
}

    
    public $nombre, $email, $whatsapp;
    public $successMessage;

    protected $rules = [
        'nombre' => 'required|string|max:100',
        'email' => 'required|email',
        'whatsapp' => 'required|string|max:20',
    ];

    public function submit()
    {
        $this->validate();

        Mail::raw("Solicitud de demo\n\nNombre: {$this->nombre}\nEmail: {$this->email}\nWhatsApp: {$this->whatsapp}", function ($message) {
           $message->to('ca140611@gmail.com')
                    ->subject('Nueva solicitud demo GymApp');
        });

        $this->successMessage = "¡Solicitud enviada! Te contactaremos pronto.";

        // Limpiar los campos
        $this->reset(['nombre', 'email', 'whatsapp']);
    }

    public function render()
    {
        return view('livewire.solicitar-demo');
    }
}
