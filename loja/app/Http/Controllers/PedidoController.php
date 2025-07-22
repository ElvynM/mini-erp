<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Cupom;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    public function finalizar(Request $request)
    {
        $request->validate([
            'cep' => 'required|string',
            'endereco' => 'nullable|string',
            'cupom_codigo' => 'nullable|string',
        ]);

        // Integração ViaCEP
        $cep = preg_replace('/[^0-9]/', '', $request->cep);
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");
        if ($response->failed() || isset($response->json()['erro'])) {
            return redirect()->back()->withErrors('CEP inválido');
        }
        $enderecoViaCep = $response->json();
        $enderecoCompleto = "{$enderecoViaCep['logradouro']}, {$enderecoViaCep['bairro']}, {$enderecoViaCep['localidade']}-{$enderecoViaCep['uf']}";
        $endereco = $request->endereco ?: $enderecoCompleto;

        $carrinho = session()->get('carrinho', []);
        if(empty($carrinho)) {
            return redirect()->back()->withErrors('Carrinho vazio');
        }

        // Calcula subtotal
        $subtotal = 0;
        foreach($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Frete conforme regra
        if($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }

        $total = $subtotal + $frete;

        // Valida cupom
        $cupom = null;
        if($request->cupom_codigo) {
            $cupom = Cupom::where('codigo', $request->cupom_codigo)->first();
            if(!$cupom || !$cupom->isValido() || $subtotal < $cupom->valor_minimo) {
                return redirect()->back()->withErrors('Cupom inválido ou não aplicável');
            }
            $total -= $cupom->valor_desconto;
        }

        // Cria pedido
        $pedido = Pedido::create([
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => max($total, 0),
            'cep' => $request->cep,
            'endereco' => $endereco,
            'status' => 'pendente',
        ]);

        // Cria itens do pedido
        foreach($carrinho as $item) {
            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $item['produto_id'],
                'variacao' => $item['variacao'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
            ]);
        }

        // Enviar e-mail (básico)
        Mail::raw("Pedido #{$pedido->id} criado com endereço: {$pedido->endereco}", function($message) {
            $message->to('cliente@exemplo.com') // substitua pelo e-mail real do cliente
                    ->subject('Confirmação de Pedido');
        });

        // Limpa carrinho
        session()->forget('carrinho');

        return redirect()->route('produtos.index')->with('success', 'Pedido finalizado com sucesso!');
    }
}

