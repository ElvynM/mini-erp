<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutosController extends Controller
{
    public function index(){
        // $produtos = [
        //     ['id' => 1, 'nome' => 'Produto 1', 'preco' => 10.00],
        //     ['id' => 2, 'nome' => 'Produto 2', 'preco' => 20.00],
        //     ['id' => 3, 'nome' => 'Produto 3', 'preco' => 30.00],
        // ];

        $produtos = Produto::with('estoques')->get();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request){
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
        ]);

        $produto = Produto::create($request->only(['nome', 'preco']));

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso.');
    }

}
