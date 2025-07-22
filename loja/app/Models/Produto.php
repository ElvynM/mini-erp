<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = ['nome', 'preco'];

    public function estoques()
    {
        return $this->hasMany(Estoque::class);
    }

    public function itens()
    {
        return $this->hasMany(ItemPedido::class);
    }
}
