@extends('layouts.app')

@section('content')
<h2>Produtos</h2>

<a href="{{ route('produtos.create') }}" class="btn btn-success mb-3">
    <i class="bi bi-plus-circle me-1"></i>
    Novo Produto
</a>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Variações</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos as $produto)
        <tr>
            <td>{{ $produto->nome }}</td>
            <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
            <td>
                <ul>
                    @foreach($produto->estoques as $estoque)
                        <li>{{ $estoque->variacao }} ({{ $estoque->quantidade }} un)</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary">Comprar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
