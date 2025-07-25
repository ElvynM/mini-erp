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
            'email' => 'required|email',
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido para receber a confirmação.',
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
        $codigo = trim($request->cupom_codigo ?? '');
        if($codigo && strtolower($codigo) !== 'null') {
            $cupom = Cupom::where('codigo', $codigo)->first();
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
            'cupom_codigo' => isset($cupom) && $cupom ? $cupom->codigo : null,
        ]);

        // Cria itens do pedido e atualiza estoque
        foreach($carrinho as $item) {
            ItemPedido::create([
                'pedido_id' => $pedido->id,
                'produto_id' => $item['produto_id'],
                'variacao' => $item['variacao'],
                'quantidade' => $item['quantidade'],
                'preco_unitario' => $item['preco'],
            ]);
            // Atualiza estoque
            $estoque = \App\Models\Estoque::where('produto_id', $item['produto_id'])
                ->where('variacao', $item['variacao'])
                ->first();
            if ($estoque) {
                $estoque->quantidade = max($estoque->quantidade - $item['quantidade'], 0);
                $estoque->save();
            }
        }
        // Atualiza status do pedido para finalizado
        $pedido->status = 'finalizado';
        $pedido->save();
        // Recupera itens para exibir na confirmação
        $itens = ItemPedido::where('pedido_id', $pedido->id)->get();
        // Limpa carrinho
        session()->forget('carrinho');

        $email = $request->email;
        Mail::to($email)->send(new \App\Mail\PedidoConfirmacao($pedido, $itens, $email));
        return view('pedido.confirmacao', compact('pedido', 'itens'));
    }
}

