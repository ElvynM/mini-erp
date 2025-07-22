<?php
namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Estoque;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    public function index()
    {
        $produtos = Produto::with('estoques')->get();
        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'variacoes' => 'nullable|array',
            'variacoes.*.nome' => 'required_with:variacoes|string|max:255',
            'variacoes.*.estoque' => 'required_with:variacoes|numeric|min:0',
        ]);

        $produto = Produto::create([
            'nome' => $validated['nome'],
            'preco' => $validated['preco'],
        ]);

       // Criar as variações, se existirem
    if (!empty($validated['variacoes'])) {
        foreach ($validated['variacoes'] as $variacao) {
            $produto->estoques()->create([
                'variacao' => $variacao['nome'],
                'quantidade' => $variacao['estoque'],
            ]);
        }
    }

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'estoques.*.id' => 'nullable|exists:estoques,id',
            'estoques.*.variacao' => 'nullable|string',
            'estoques.*.quantidade' => 'required|integer|min:0',
        ]);

        $produto->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        foreach ($request->estoques as $estoque) {
            if (isset($estoque['id'])) {
                $estoqueModel = Estoque::find($estoque['id']);
                $estoqueModel->update([
                    'variacao' => $estoque['variacao'] ?? null,
                    'quantidade' => $estoque['quantidade'],
                ]);
            } else {
                $produto->estoques()->create([
                    'variacao' => $estoque['variacao'] ?? null,
                    'quantidade' => $estoque['quantidade'],
                ]);
            }
        }

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
