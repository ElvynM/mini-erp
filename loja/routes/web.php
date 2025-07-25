
<?php

use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;


Route::get('/',[
    ProdutosController::class, 'index'
])->name('produtos.index');


Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProdutosController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProdutosController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{id}/edit', [ProdutosController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProdutosController::class, 'update'])->name('produtos.update');

Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'mostrar'])->name('carrinho.mostrar');
Route::post('/carrinho/remover', [CarrinhoController::class, 'remover'])->name('carrinho.remover');

Route::post('/carrinho/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');

Route::post('/webhook/pedido', [WebhookController::class, 'atualizarStatus'])->name('webhook.atualizarStatus');

Route::get('/cupons', [CupomController::class, 'index'])->name('cupons.index');
Route::get('/cupons/create', [CupomController::class, 'create'])->name('cupons.create');
Route::post('/cupons', [CupomController::class, 'store'])->name('cupons.store');
Route::get('/cupons/{cupom}/edit', [CupomController::class, 'edit'])->name('cupons.edit');
Route::put('/cupons/{cupom}', [CupomController::class, 'update'])->name('cupons.update');
Route::delete('/cupons/{cupom}', [CupomController::class, 'destroy'])->name('cupons.destroy');
