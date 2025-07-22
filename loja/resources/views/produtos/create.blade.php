@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cadastro de Produto</h4>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Oops!</strong> Corrija os erros abaixo.
                </div>
            @endif

            <form action="{{ route('produtos.store') }}" method="POST">
                @csrf

                {{-- Nome do Produto --}}
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}"
                        class="form-control @error('nome') is-invalid @enderror">
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Preço --}}
                <div class="mb-3">
                    <label for="preco" class="form-label">Preço</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0"
                        value="{{ old('preco') }}"
                        class="form-control @error('preco') is-invalid @enderror">
                    @error('preco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Variações --}}
                <div class="mt-4">
                    <h5 class="mb-3">Variações</h5>
                    <div id="variacoes">
                        @php $variacoes = old('variacoes', [['nome' => '', 'estoque' => '']]); @endphp

                        @foreach ($variacoes as $i => $variacao)
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <input type="text"
                                        name="variacoes[{{ $i }}][nome]"
                                        class="form-control @error("variacoes.$i.nome") is-invalid @enderror"
                                        placeholder="Nome da Variação"
                                        value="{{ $variacao['nome'] }}">
                                    @error("variacoes.$i.nome")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input type="number"
                                        name="variacoes[{{ $i }}][estoque]"
                                        class="form-control @error("variacoes.$i.estoque") is-invalid @enderror"
                                        placeholder="Estoque"
                                        min="0"
                                        value="{{ $variacao['estoque'] }}">
                                    @error("variacoes.$i.estoque")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-outline-secondary btn-sm mb-3" onclick="addVariacao()">
                        <i class="bi bi-plus-circle"></i> Adicionar Variação
                    </button>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Salvar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional: Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<script>
    let index = {{ count($variacoes) }};

    function addVariacao() {
        const div = document.createElement('div');
        div.classList.add('row', 'g-2', 'mb-2');
        div.innerHTML = `
            <div class="col">
                <input type="text" name="variacoes[${index}][nome]" class="form-control" placeholder="Nome da Variação">
            </div>
            <div class="col">
                <input type="number" name="variacoes[${index}][estoque]" class="form-control" placeholder="Estoque" min="0">
            </div>
        `;
        document.getElementById('variacoes').appendChild(div);
        index++;
    }
</script>
@endsection
