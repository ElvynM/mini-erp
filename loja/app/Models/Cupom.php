<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $fillable = ['codigo','valor_desconto','valor_minimo','validade'];

    public function isValido()
    {
        return $this->validade->isFuture();
    }
}
