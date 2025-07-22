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

    public function edit($id)
    {
        $produto = Produto::with('estoques')->findOrFail($id);
        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $rules = [
            'nome' => 'required|string',
            'preco' => 'required|numeric',
            'estoques.*.id' => 'nullable|exists:estoques,id',
            'estoques.*.variacao' => 'nullable|string',
            'estoques.*.quantidade' => 'nullable|integer|min:0',
        ];
        // Se o campo de nova variação estiver preenchido, exige obrigatoriedade
        if (
            isset($request->estoques['new']) &&
            (
                (!empty($request->estoques['new']['variacao']) && $request->estoques['new']['variacao'] !== null) ||
                ($request->estoques['new']['quantidade'] !== null && $request->estoques['new']['quantidade'] !== '')
            )
        ) {
            $rules['estoques.new.variacao'] = 'required|string';
            $rules['estoques.new.quantidade'] = 'required|integer|min:0';
        }
        $request->validate($rules);
        $produto->update([
            'nome' => $request->nome,
            'preco' => $request->preco,
        ]);

        foreach ($request->estoques as $key => $estoque) {
            if ($key === 'new') {
                continue;
            }
            if (isset($estoque['id']) && $estoque['id']) {
                $estoqueModel = Estoque::find($estoque['id']);
                if ($estoqueModel) {
                    $estoqueModel->variacao = $estoque['variacao'];
                    $estoqueModel->quantidade = (int) $estoque['quantidade'];
                    $estoqueModel->save();
                }
            }
        }
        // Adiciona nova variação se os campos estiverem preenchidos
        if (
            isset($request->estoques['new']) &&
            isset($request->estoques['new']['variacao']) &&
            isset($request->estoques['new']['quantidade']) &&
            $request->estoques['new']['variacao'] !== null &&
            $request->estoques['new']['variacao'] !== '' &&
            $request->estoques['new']['quantidade'] !== null &&
            $request->estoques['new']['quantidade'] !== ''
        ) {
            $produto->estoques()->create([
                'variacao' => $request->estoques['new']['variacao'],
                'quantidade' => (int) $request->estoques['new']['quantidade'],
            ]);
        }

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }
}
