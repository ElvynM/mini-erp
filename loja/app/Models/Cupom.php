<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    protected $table = 'cupons';
    protected $fillable = ['codigo','valor_desconto','valor_minimo','validade'];

    public function getRouteKeyName()
    {
        return 'id';
    }

    public function isValido()
    {
        $validade = \Carbon\Carbon::parse($this->validade)->endOfDay();
        return $validade->isFuture();
    }
}
