<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ApiTrait
{
    /**************************************
     * scope para modificar las consultas *
     **************************************/
    //Para ver categoría y sus relaciones
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
    // Para filtrar por campos
    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }
        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);
        foreach ($filters as $filter => $value) {
            if ($allowFilter->contains($filter)) {
                $query->where($filter, 'LIKE', '%' . $value . '%');
            }
        }
    }
    public function scopeSort(Builder $query)
    {
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }
        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if (substr($sortField, 0, 1) == '-') {
                $direction = 'desc';
                $sortField = substr($sortField, 1);
            }
            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }
    public function scopeGetOrPaginate(Builder $query)
    {
        if (request('perPage')) {
            $perPage = intval(request('perPage')); //convertimos a un entero
            //Si es un entero
            if ($perPage) {
                //paginar
                return $query->paginate($perPage);
            }
        }
        //caso contrario mostrar todos los registros
        return $query->get();
    }
}
