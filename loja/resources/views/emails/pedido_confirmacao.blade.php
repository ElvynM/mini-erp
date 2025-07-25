<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmação de Pedido</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #e0e0e0; padding: 32px; }
        h2 { color: #2c3e50; margin-top: 0; }
        .info { margin-bottom: 24px; }
        .info p { margin: 6px 0; font-size: 15px; }
        .itens { margin-top: 24px; }
        .itens-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .itens-table th, .itens-table td { border: 1px solid #e0e0e0; padding: 8px; text-align: left; font-size: 14px; }
        .itens-table th { background: #f1f1f1; }
        .total { font-size: 18px; color: #27ae60; font-weight: bold; margin-top: 18px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pedido #{{ $pedido->id }}</h2>
        <div class="info">
            <p><strong>E-mail do cliente:</strong> {{ $email ?? '-' }}</p>
            <p><strong>Endereço:</strong> {{ $pedido->endereco }}</p>
            <p><strong>CEP:</strong> {{ $pedido->cep }}</p>
            <p><strong>Subtotal:</strong> R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</p>
            <p><strong>Frete:</strong> R$ {{ number_format($pedido->frete, 2, ',', '.') }}</p>
            <p class="total"><strong>Total Pedido com Desconto:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</p>
        </div>
        <div class="itens">
            <h3>Itens do Pedido</h3>
            <table class="itens-table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Variação</th>
                        <th>Quantidade</th>
                        <th>Valor Unitário</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itens as $item)
                        <tr>
                            <td>{{ $item->produto->nome }}</td>
                            <td>{{ $item->variacao }}</td>
                            <td>{{ $item->quantidade }}</td>
                            <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
