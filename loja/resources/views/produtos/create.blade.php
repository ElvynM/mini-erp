@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-box-seam-fill me-2"></i>Cadastro de Produto</h4>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger rounded-3">
                    <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Corrija os erros abaixo:</strong>
                </div>
            @endif

            <form action="{{ route('produtos.store') }}" method="POST">
                @csrf

                {{-- Nome --}}
                <div class="mb-4">
                    <label for="nome" class="form-label fw-semibold">Nome do Produto</label>
                    <input type="text" id="nome" name="nome" class="form-control rounded-3 shadow-sm @error('nome') is-invalid @enderror" value="{{ old('nome') }}" placeholder="Ex: Camiseta Básica">
                    @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Preço --}}
                <div class="mb-4">
                    <label for="preco" class="form-label fw-semibold">Preço (R$)</label>
                    <input type="text" id="preco" name="preco"
                        class="form-control rounded-3 shadow-sm @error('preco') is-invalid @enderror"
                        value="{{ old('preco') }}" placeholder="Ex: 99,90">
                    @error('preco')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Variações --}}
                <div class="mb-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-layers me-1"></i>Variações</h5>
                    <div id="variacoes">
                        @php $variacoes = old('variacoes', [['nome' => '', 'estoque' => '']]); @endphp
                        @foreach ($variacoes as $i => $variacao)
                            <div class="row g-2 mb-2 align-items-center">
                                <div class="col-md-6">
                                    <input type="text" name="variacoes[{{ $i }}][nome]" class="form-control rounded-3 shadow-sm @error("variacoes.$i.nome") is-invalid @enderror" placeholder="Ex: Tamanho M" value="{{ $variacao['nome'] }}">
                                    @error("variacoes.$i.nome")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="number" name="variacoes[{{ $i }}][estoque]" class="form-control rounded-3 shadow-sm @error("variacoes.$i.estoque") is-invalid @enderror" placeholder="Estoque" min="0" value="{{ $variacao['estoque'] }}">
                                    @error("variacoes.$i.estoque")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addVariacao()">
                        <i class="bi bi-plus-circle me-1"></i> Adicionar Variação
                    </button>
                </div>

                {{-- Botão de salvar --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4 shadow-sm">
                        <i class="bi bi-check-circle-fill me-1"></i> Salvar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Bootstrap Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

{{-- Scripts necessários --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

{{-- Scripts personalizados --}}
<script>
    let index = {{ count($variacoes) }};

    function addVariacao() {
        const div = document.createElement('div');
        div.classList.add('row', 'g-2', 'mb-2');
        div.innerHTML = `
            <div class="col-md-6">
                <input type="text" name="variacoes[${index}][nome]" class="form-control rounded-3 shadow-sm" placeholder="Nome da Variação">
            </div>
            <div class="col-md-4">
                <input type="number" name="variacoes[${index}][estoque]" class="form-control rounded-3 shadow-sm" placeholder="Estoque" min="0">
            </div>
        `;
        document.getElementById('variacoes').appendChild(div);
        index++;
    }

    $(document).ready(function () {
        // Aplica máscara
        $('#preco').mask('000.000.000,00', { reverse: true });

        // Ao submeter, troca vírgula por ponto
        $('form').on('submit', function () {
            const preco = $('#preco').val().replace(/\./g, '').replace(',', '.');
            $('#preco').val(preco);
        });
    });

</script>

@endsection
