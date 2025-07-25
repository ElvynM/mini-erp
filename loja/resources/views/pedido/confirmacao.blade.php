@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pedido Finalizado</h2>
    <div class="alert alert-success">Seu pedido foi realizado com sucesso!</div>
    <h4>Dados do Pedido</h4>
    <ul class="list-group mb-3">
        <li class="list-group-item"><strong>ID:</strong> {{ $pedido->id }}</li>
        <li class="list-group-item"><strong>Endereço:</strong> {{ $pedido->endereco }}</li>
        <li class="list-group-item"><strong>CEP:</strong> {{ $pedido->cep }}</li>
        <li class="list-group-item"><strong>Subtotal:</strong> R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</li>
        <li class="list-group-item"><strong>Frete:</strong> R$ {{ number_format($pedido->frete, 2, ',', '.') }}</li>
        <li class="list-group-item"><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</li>
        @if($pedido->cupom_codigo)
            <li class="list-group-item"><strong>Cupom:</strong> {{ $pedido->cupom_codigo }}</li>
        @endif
    </ul>
    <h4>Itens do Pedido</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Variação</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item->produto ? $item->produto->nome : '-' }}</td>
                <td>{{ $item->variacao }}</td>
                <td>{{ $item->quantidade }}</td>
                <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($item->preco_unitario * $item->quantidade, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('produtos.index') }}" class="btn btn-primary">Voltar para Produtos</a>
</div>
@endsection
