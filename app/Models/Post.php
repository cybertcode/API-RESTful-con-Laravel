<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Trait creado

class Post extends Model
{
    use HasFactory, ApiTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'extract', 'body', 'status', 'category_id', 'user_id'];
    const BORRADOR = 1;
    const PUBLICADO = 2;
    //Para consultar las  relaciones
    protected $allowIncluded = ['user', 'category', 'tags', 'images'];
    //Para filtrar
    protected $allowFilter = ['id', 'name', 'slug', 'extract', 'body'];
    // Para ordernar
    protected $allowSort = ['id', 'name', 'slug', 'extract', 'body'];

    /*********************************
     * Relación uno a muchos inversa *
     *********************************/
    //* Ojo: los nombres de los métodos son como vamos recuperar la relación - ejemplo: un post tiene una única categoría. Asimismo, un post tiene un única categoría

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /****************************
     * Relación muchos a muchos *
     ****************************/
    //* Un post puede pertenecer o estar en muchos etiquetas
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    /**********************************
     * Relación uno a uno polimórfica *
     **********************************/
    //* Un post puede tener muchas imágenes
    public function images()
    {
        //* Se pasa el modelo y el método dentro de los paréntesis
        return $this->morphMany(Image::class, 'imageable');
    }
}
