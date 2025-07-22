@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Editar Cupom</h2>
    <form action="{{ route('cupons.update', $cupom) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" required value="{{ old('codigo', $cupom->codigo) }}">
        </div>
        <div class="mb-3">
            <label for="valor_desconto" class="form-label">Valor do Desconto</label>
            <input type="number" step="0.01" class="form-control" id="valor_desconto" name="valor_desconto" required value="{{ old('valor_desconto', $cupom->valor_desconto) }}">
        </div>
        <div class="mb-3">
            <label for="valor_minimo" class="form-label">Valor Mínimo</label>
            <input type="number" step="0.01" class="form-control" id="valor_minimo" name="valor_minimo" required value="{{ old('valor_minimo', $cupom->valor_minimo) }}">
        </div>
        <div class="mb-3">
            <label for="validade" class="form-label">Validade</label>
            <input type="date" class="form-control" id="validade" name="validade" required value="{{ old('validade', $cupom->validade) }}">
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('cupons.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
