@extends('layouts.app')

@section('content')
<div class="container py-4">
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="bi bi-box-seam me-2"></i>Produtos</h2>
        <div>
            <a href="{{ route('produtos.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle me-1"></i> Novo Produto
            </a>
            <a href="{{ route('carrinho.mostrar') }}" class="btn btn-info">
                <i class="bi bi-cart me-1"></i> Ver Carrinho
            </a>
        </div>
    </div>

    <div class="row g-4">
        @forelse($produtos as $produto)
        <div class="col-md-6 col-lg-4">
            <div class="produto-card-modern h-100">
                <div class="produto-card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="produto-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h5 class="mb-0 fw-bold text-dark">{{ $produto->nome }}</h5>
                    </div>
                    <div class="mb-2">
                        <span class="produto-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong class="text-muted">Variações:</strong>
                        <ul class="list-unstyled mt-2 mb-0">
                            @forelse($produto->estoques as $estoque)
                                <li class="mb-2 d-flex align-items-center">
                                    <span class="produto-variacao me-2">{{ $estoque->variacao }}</span>
                                    <span class="produto-quantidade me-2">({{ $estoque->quantidade }} un)</span>
                                    <form action="{{ route('carrinho.adicionar') }}" method="POST" class="d-flex align-items-center ms-auto" style="gap:6px;">
                                        @csrf
                                        <input type="hidden" name="produto_id" value="{{ $produto->id }}">
                                        <input type="hidden" name="variacao" value="{{ $estoque->variacao }}">
                                        <input type="number" name="quantidade" value="1" min="1" max="{{ $estoque->quantidade }}" class="form-control form-control-sm quantidade-input" style="width:60px;" data-max="{{ $estoque->quantidade }}">
                                        <button class="btn btn-sm btn-primary">Comprar</button>
                                    </form>
                                </li>
                            @empty
                                <li class="text-danger">Sem variações cadastradas</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="produto-card-footer px-4 pb-3 d-flex justify-content-end">
                    <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center py-4">
                <i class="bi bi-box-seam fa-2x mb-2"></i><br>
                Nenhum produto cadastrado ainda.
            </div>
        </div>
        @endforelse
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quantidade-input').forEach(function(input) {
                input.addEventListener('input', function() {
                    var max = parseInt(input.getAttribute('data-max'));
                    var val = parseInt(input.value);
                    if(val > max) {
                        input.value = max;
                        input.classList.add('is-invalid');
                        if(!input.nextElementSibling || !input.nextElementSibling.classList.contains('estoque-alert')) {
                            var alert = document.createElement('div');
                            alert.className = 'estoque-alert text-danger small ms-2';
                            alert.innerText = 'Só temos ' + max + ' unidade(s) em estoque.';
                            input.parentNode.insertBefore(alert, input.nextSibling);
                        }
                    } else {
                        input.classList.remove('is-invalid');
                        if(input.nextElementSibling && input.nextElementSibling.classList.contains('estoque-alert')) {
                            input.nextElementSibling.remove();
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .produto-card-modern {
            background: #fff;
            border-radius: 1.25rem;
            box-shadow: 0 2px 16px 0 #00000010;
            border: 1px solid #e3e6ef;
            transition: box-shadow .2s, border-color .2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 340px;
        }
        .produto-card-modern:hover {
            box-shadow: 0 4px 32px 0 #0d6efd22;
            border-color: #0d6efd;
        }
        .produto-card-body {
            flex: 1 1 auto;
        }
        .produto-card-footer {
            background: none;
        }
        .produto-icon {
            box-shadow: 0 2px 8px #0d6efd22;
        }
        .produto-price {
            font-size: 1.15rem;
            font-weight: 600;
            color: #0d6efd;
            background: #f4f8ff;
            border-radius: 0.5rem;
            padding: 0.25rem 0.75rem;
            display: inline-block;
        }
        .produto-variacao {
            font-size: 0.95rem;
            color: #495057;
            background: #e9ecef;
            border-radius: 0.5rem;
            padding: 0.15rem 0.6rem;
            font-weight: 500;
        }
        .produto-quantidade {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</div>
@endsection
