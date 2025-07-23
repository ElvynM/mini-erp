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
    @if($cupom_popup)
        <div id="cupom-popup" class="modal fade show" tabindex="-1" style="display:block; background:rgba(0,0,0,0.3);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cupom de Desconto!</h5>
                        <button type="button" class="btn-close" onclick="document.getElementById('cupom-popup').style.display='none';"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Use o código abaixo para ganhar desconto:</p>
                        <div class="fw-bold fs-4 mb-2" id="cupom-codigo">{{ $cupom_popup->codigo }}</div>
                        <button class="btn btn-outline-primary" onclick="navigator.clipboard.writeText('{{ $cupom_popup->codigo }}')">Copiar código</button>
                        <p class="mt-2">Desconto: R$ {{ number_format($cupom_popup->valor_desconto, 2, ',', '.') }}<br>Válido até: {{ \Carbon\Carbon::parse($cupom_popup->validade)->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <script>
            setTimeout(function(){ document.getElementById('cupom-popup').style.display = 'none'; }, 15000);
        </script>
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
    <form action="{{ route('carrinho.mostrar') }}" method="GET" class="mb-3">
        <div class="mb-3">
            <label for="cupom_codigo" class="form-label">Cupom</label>
            <div class="input-group">
                <input type="text" class="form-control" id="cupom_codigo" name="cupom_codigo" value="{{ old('cupom_codigo', $cupom_codigo ?? '') }}" placeholder="Digite o código do cupom">
                <input type="hidden" name="cep" value="{{ $cep ?? '' }}">
                <input type="hidden" name="endereco" value="{{ $endereco ?? '' }}">
                <button type="submit" class="btn btn-outline-primary">Aplicar Cupom</button>
            </div>
            @if($cupom_codigo)
                @if($cupom_aplicado && $desconto > 0)
                    <div class="text-success mt-1">Cupom aplicado! Desconto de R$ {{ number_format($desconto, 2, ',', '.') }}</div>
                @else
                    <div class="text-danger mt-1">Cupom inválido ou não aplicável.</div>
                @endif
            @endif
        </div>
    </form>
    <p><strong>Subtotal:</strong> R$ {{ number_format($subtotal, 2, ',', '.') }}</p>
    <p><strong>Frete estimado:</strong> R$ {{ number_format($frete, 2, ',', '.') }}</p>
    <p><strong>Desconto:</strong> R$ {{ number_format($desconto, 2, ',', '.') }}</p>
    <p><strong>Total estimado:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
    <form action="{{ route('pedido.finalizar') }}" method="POST">
        @csrf
        <input type="hidden" name="cupom_codigo" value="{{ $cupom_codigo }}">
        <input type="hidden" name="cep" value="{{ $cep ?? '' }}">
        <input type="hidden" name="endereco" value="{{ $endereco ?? '' }}">
        <button type="submit" class="btn btn-success">Finalizar Pedido</button>
    </form>
    @endif
</div>
@endsection
