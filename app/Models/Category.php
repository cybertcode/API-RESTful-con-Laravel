<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];
    //Para consultar las  relaciones
    protected $allowIncluded = ['posts', 'posts.user'];
    /*************************
     * Relación uno a muchos *
     *************************/
    //* Una categoría tiene muchos posts
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // scope para modificar las consultas
    public function scopeIncluded(Builder $query)
    {
        // comprueba si allowIncluded y included no están vacíos
        if (!empty([$this->allowIncluded, request('included')])) {
            // convertimos cadena en un array, utilizando el separador coma.
            $relations = explode(',', request('included')); // [posts, relation2]
            //crea una colección a partir del array allowIncluded
            $allowIncluded = collect($this->allowIncluded);
            //se recorre el array de relaciones incluidas y se elimina cualquier relación que no esté permitida en allowIncluded
            foreach ($relations as $key => $relationship) {
                if (!$allowIncluded->contains($relationship)) {
                    unset($relations[$key]);
                }
            }
            //se utiliza el método with() de Laravel para incluir todas las relaciones permitidas en la consulta
            $query->with($relations);
        }
    }
}
