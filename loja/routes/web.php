<?php

use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\CupomController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('welcome');
// });

Route::get('/',[
    ProdutosController::class, 'index'
])->name('produtos.index');


Route::get('/produtos', [ProdutosController::class, 'index'])->name('produtos.index');
Route::get('/produtos/create', [ProdutosController::class, 'create'])->name('produtos.create');
Route::post('/produtos', [ProdutosController::class, 'store'])->name('produtos.store');
Route::put('/produtos/{id}', [ProdutosController::class, 'update'])->name('produtos.update');

Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionar'])->name('carrinho.adicionar');
Route::get('/carrinho', [CarrinhoController::class, 'mostrar'])->name('carrinho.mostrar');

Route::post('/carrinho/finalizar', [PedidoController::class, 'finalizar'])->name('pedido.finalizar');

// Route::post('/webhook/pedido', [WebhookController::class, 'atualizarStatus'])->name('webhook.atualizarStatus');

// Route::resource('cupons', CupomController::class);
