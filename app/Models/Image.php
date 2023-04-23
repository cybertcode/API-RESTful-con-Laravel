<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    /***************************
     * Relaciones polimórficas *
     ***************************/
    //* Habilitamos las relaciones polimórficas

    public function imageable(){
        return $this->morphTo();
    }
}
