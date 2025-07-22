<?php

namespace App\Http\Controllers;

use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $cupons = Cupom::all();
        return view('cupons.index', compact('cupons'));
    }

    public function create()
    {
        return view('cupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo',
            'valor_desconto' => 'required|numeric|min:0',
            'valor_minimo' => 'required|numeric|min:0',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        Cupom::create($request->only('codigo', 'valor_desconto', 'valor_minimo', 'validade'));

        return redirect()->route('cupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit(Cupom $cupom)
    {
        return view('cupons.edit', compact('cupom'));
    }

    public function update(Request $request, Cupom $cupom)
    {
        $request->validate([
            'codigo' => 'required|string|unique:cupons,codigo,' . $cupom->id,
            'valor_desconto' => 'required|numeric|min:0',
            'valor_minimo' => 'required|numeric|min:0',
            'validade' => 'required|date|after_or_equal:today',
        ]);

        $cupom->update($request->only('codigo', 'valor_desconto', 'valor_minimo', 'validade'));

        return redirect()->route('cupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return redirect()->route('cupons.index')->with('success', 'Cupom removido com sucesso!');
    }
}
