<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = ['subtotal', 'frete', 'total', 'cep', 'endereco', 'status'];
}

