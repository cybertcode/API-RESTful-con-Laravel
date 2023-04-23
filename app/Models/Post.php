<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;
    const BORRADOR = 1;
    const PUBLICADO = 2;
    /*********************************
     * Relación uno a muchos inversa *
     *********************************/
    //* Ojo: los nombres de los métodos son como vamos recuperar la relación - ejemplo: un post tiene una única categoría. Asimismo, un post tiene un única categoría

    public function user(){
        $this->belongsTo(User::class);
    }
    public function category(){
        $this->belongsTo(Category::class);
    }
    /****************************
     * Relación muchos a muchos *
     ****************************/
    //* Un post puede pertenecer o estar en muchos etiquetas
    public function tags(){
        $this->belongsToMany(Tag::class);
    }
    /**********************************
     * Relación uno a uno polimórfica *
     **********************************/
    //* Un post puede tener muchas imágenes
    public function images(){
        //* Se pasa el modelo y el método dentro de los paréntesis
        return $this->morphMany(Image::class,'imageable');
    }
}
