<?php

namespace App\Mail;

use App\Models\Pedido;
use App\Models\ItemPedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmacao extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;
    public $itens;
    public $email;

    public function __construct(Pedido $pedido, $itens, $email)
    {
        $this->pedido = $pedido;
        $this->itens = $itens;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Confirmação de Pedido')
            ->view('emails.pedido_confirmacao');
    }
}
