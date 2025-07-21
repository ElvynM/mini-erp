<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProdutosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/produtos',ProdutosController::class . '@index')->name('produtos.index');
