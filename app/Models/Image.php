<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//Trait creado

class Image extends Model
{
    use HasFactory, ApiTrait;
    /***************************
     * Relaciones polimórficas *
     ***************************/
    //* Habilitamos las relaciones polimórficas

    public function imageable()
    {
        return $this->morphTo();
    }
}
