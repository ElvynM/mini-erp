<?php
namespace App\Http\Controllers;

use App\Models\Cupom;
use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    public function adicionar(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'variacao' => 'nullable|string',
            'quantidade' => 'required|integer|min:1',
        ]);

        $produto = Produto::findOrFail($request->produto_id);

        // Buscar estoque da variação
        $estoque = Estoque::where('produto_id', $produto->id)
            ->where('variacao', $request->variacao)
            ->first();

        if (!$estoque || $estoque->quantidade < 1) {
            return redirect()->route('produtos.index')->with('errors', 'Variação ou estoque indisponível.');
        }

        $carrinho = session()->get('carrinho', []);
        $key = $request->produto_id . '-' . ($request->variacao ?? 'null');
        $quantidadeNoCarrinho = isset($carrinho[$key]) ? $carrinho[$key]['quantidade'] : 0;
        $totalSolicitado = $quantidadeNoCarrinho + $request->quantidade;

        if ($totalSolicitado > $estoque->quantidade) {
            return redirect()->route('produtos.index')->with('errors', 'Só temos ' . $estoque->quantidade . ' unidade(s) em estoque para esta variação.');
        }

        if(isset($carrinho[$key])) {
            $carrinho[$key]['quantidade'] = $totalSolicitado;
        } else {
            $carrinho[$key] = [
                'produto_id' => $produto->id,
                'nome' => $produto->nome,
                'variacao' => $request->variacao,
                'preco' => $produto->preco,
                'quantidade' => $request->quantidade,
            ];
        }

        session()->put('carrinho', $carrinho);

        return redirect()->route('produtos.index')->with('success', 'Produto adicionado ao carrinho!');
    }

    public function mostrar()
    {
        $carrinho = session()->get('carrinho', []);
        $subtotal = 0;

        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        // Calcular frete
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20;
        }

        // Cupom manual
        $cupom_codigo = request('cupom_codigo');
        $desconto = 0;
        $cupom_aplicado = null;

        if ($cupom_codigo) {
            $cupom_aplicado = Cupom::where('codigo', $cupom_codigo)
                ->where('validade', '>=', now()->toDateString())
                ->first();

            if ($cupom_aplicado && $subtotal >= $cupom_aplicado->valor_minimo) {
                $desconto = $cupom_aplicado->valor_desconto;
            } else {
                // cupom manual inválido ou não atingiu mínimo
                $cupom_aplicado = null;
            }
        }

        // Cupom automático se nenhum válido foi aplicado
        if (!$cupom_aplicado) {
            $cupom_automatico = Cupom::where('validade', '>=', now()->toDateString())
                ->where('valor_minimo', '<=', $subtotal)
                ->orderByDesc('valor_desconto')
                ->first();

            if ($cupom_automatico) {
                $cupom_aplicado = $cupom_automatico;
                $desconto = $cupom_aplicado->valor_desconto;

                // Preenche o cupom na view
                $cupom_codigo = $cupom_aplicado->codigo;
            }
        }

        $total = max($subtotal + $frete - $desconto, 0);

        // Verificação de CEP
        $cep = request('cep');
        $cepValido = null;
        $endereco = null;

        if ($cep) {
            $cepLimpo = preg_replace('/\D/', '', $cep);
            if (strlen($cepLimpo) === 8) {
                $response = @file_get_contents("https://viacep.com.br/ws/{$cepLimpo}/json/");
                if ($response) {
                    $data = json_decode($response, true);
                    if (!isset($data['erro'])) {
                        $cepValido = true;
                        $endereco = $data['logradouro'] . ', ' . $data['bairro'] . ', ' . $data['localidade'] . ' - ' . $data['uf'];
                    } else {
                        $cepValido = false;
                    }
                } else {
                    $cepValido = false;
                }
            } else {
                $cepValido = false;
            }
        }

        // Exibir popup com o mesmo cupom que está sendo aplicado
        $cupom_popup = null;
        $show_popup = !request()->isMethod('post');

        if ($cupom_aplicado && $show_popup) {
            $cupom_popup = $cupom_aplicado;
        }

        return view('carrinho.index', compact(
            'carrinho',
            'subtotal',
            'frete',
            'desconto',
            'total',
            'cep',
            'cepValido',
            'endereco',
            'cupom_popup',
            'cupom_codigo',
            'cupom_aplicado'
        ));
    }


    public function remover(Request $request)
    {
        $key = $request->key;
        $carrinho = session()->get('carrinho', []);
        if(isset($carrinho[$key])) {
            unset($carrinho[$key]);
            session()->put('carrinho', $carrinho);
        }
        return redirect()->route('carrinho.mostrar')->with('success', 'Item removido do carrinho!');
    }
}
