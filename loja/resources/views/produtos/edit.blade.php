@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Produto</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrija os erros abaixo:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('produtos.update', $produto->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
        </div>
        <div class="mb-3">
            <label for="preco" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="{{ old('preco', $produto->preco) }}" required>
        </div>
        <h4>Variações e Estoque</h4>
        @php
            $oldEstoques = old('estoques', []);
        @endphp
        @foreach($produto->estoques as $i => $estoque)
        <div class="row mb-2">
            <input type="hidden" name="estoques[{{ $i }}][id]" value="{{ $estoque->id }}">
            <div class="col">
                <input type="text" class="form-control" name="estoques[{{ $i }}][variacao]" placeholder="Variação" value="{{ isset($oldEstoques[$i]['variacao']) ? $oldEstoques[$i]['variacao'] : $estoque->variacao }}">
            </div>
            <div class="col">
                <input type="number" class="form-control" name="estoques[{{ $i }}][quantidade]" placeholder="Quantidade" value="{{ isset($oldEstoques[$i]['quantidade']) ? $oldEstoques[$i]['quantidade'] : $estoque->quantidade }}" min="0">
            </div>
        </div>
        @endforeach
        <hr>
        <h5>Adicionar nova variação</h5>
        <div class="row mb-2">
            <div class="col">
                <input type="text" class="form-control" name="estoques[new][variacao]" placeholder="Nova Variação">
            </div>
            <div class="col">
                <input type="number" class="form-control" name="estoques[new][quantidade]" placeholder="Quantidade" min="0">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
