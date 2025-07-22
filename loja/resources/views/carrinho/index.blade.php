@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Carrinho</h2>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('errors'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            {{ session('errors') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(empty($carrinho))
        <div class="card text-center mt-5 mb-5 p-4 shadow-sm bg-light">
            <div class="card-body">
                <h3 class="card-title text-muted mb-3">
                    <i class="fas fa-shopping-cart fa-2x mb-2"></i><br>
                    Seu carrinho está vazio
                </h3>
                <p class="card-text">Adicione produtos para visualizar aqui.</p>
                <a href="{{ route('produtos.index') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-arrow-left"></i> Voltar para Produtos
                </a>
            </div>
        </div>
    @else
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
                @foreach($carrinho as $item)
                <tr>
                    <td>{{ $item['nome'] }}</td>
                    <td>{{ $item['variacao'] }}</td>
                    <td>{{ $item['quantidade'] }}</td>
                    <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item['preco'] * $item['quantidade'], 2, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('carrinho.remover') }}" method="POST" style="display:inline-block">
                            @csrf
                            <input type="hidden" name="key" value="{{ $item['produto_id'] . '-' . ($item['variacao'] ?? 'null') }}">
                            <button class="btn btn-danger btn-sm">Remover</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    <form action="{{ route('carrinho.mostrar') }}" method="GET" class="mb-3">
        <div class="mb-3">
            <label for="cep" class="form-label">CEP</label>
            <div class="input-group">
                <input type="text" class="form-control" id="cep" name="cep" value="{{ old('cep', $cep ?? '') }}" required maxlength="9" placeholder="Digite o CEP">
                <button type="submit" class="btn btn-outline-primary">Consultar CEP</button>
            </div>
            @if(!is_null($cepValido))
                @if($cepValido)
                    <div class="text-success mt-1">CEP válido!</div>
                @else
                    <div class="text-danger mt-1">CEP inválido ou não encontrado.</div>
                @endif
            @endif
        </div>
        <div class="mb-3">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value="{{ old('endereco', $endereco ?? '') }}" readonly>
        </div>
    </form>
    <form action="{{ route('pedido.finalizar') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="cupom_codigo" class="form-label">Cupom</label>
            <input type="text" class="form-control" id="cupom_codigo" name="cupom_codigo" placeholder="Digite o código do cupom">
        </div>
        <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
        <p><strong>Frete estimado:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
        <p><strong>Total estimado:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
        <small class="text-muted">O desconto do cupom será aplicado na finalização do pedido.</small>
        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
    </form>
    @endif
</div>
@endsection
