<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function atualizarStatus(Request $request)
    {
        $request->validate([
            'pedido_id' => 'required|integer|exists:pedidos,id',
            'status' => 'required|string',
        ]);

        $pedido = Pedido::findOrFail($request->pedido_id);

        if(strtolower($request->status) === 'cancelado') {
            $pedido->delete();
            return response()->json(['message' => 'Pedido removido.']);
        }

        $pedido->status = $request->status;
        $pedido->save();

        return response()->json(['message' => 'Status atualizado.']);
    }
}
