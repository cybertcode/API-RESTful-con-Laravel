<?php

namespace App\Models;

use App\Models\Post;
use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//Trait creado

class Tag extends Model
{
    use HasFactory, ApiTrait;
    /****************************
     * Relación muchos a muchos *
     ****************************/
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
