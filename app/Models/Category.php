<?php

namespace App\Models;

use App\Traits\ApiTrait; //Trait creado
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, ApiTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];
    //Para consultar las  relaciones
    protected $allowIncluded = ['posts', 'posts.user', 'posts.category', 'posts.images', 'posts.tags'];
    //Para filtrar
    protected $allowFilter = ['id', 'name', 'slug'];
    // Para ordernar
    protected $allowSort = ['id', 'name', 'slug'];
    /*************************
     * Relación uno a muchos *
     *************************/
    //* Una categoría tiene muchos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
