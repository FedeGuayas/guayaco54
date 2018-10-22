<?php

namespace App\Mail;

use App\Inscripcion;
use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InscripcionPayOut extends Mailable
{
    use Queueable, SerializesModels;

    public $inscripcion;
    public $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Inscripcion $inscription,Payment $payment)
    {
        $this->inscripcion=$inscription;
        $this->payment=$payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@fedeguayas.com.ec', 'Guayaco Runner')
            ->subject('Confirmación de pago')
            ->markdown('emails.inscripcions.pay-out');
    }
}
