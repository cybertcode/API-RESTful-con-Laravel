<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'nombre' => $this->name, // Puedes cambiar los campos a español
            'slug' => $this->slug,
            // 'posts' => $this->whenLoaded('posts'), // Para relación - nuestre cuando nosotros precargamos la relación
            'posts' => PostResource::collection($this->whenLoaded('posts')),
        ];
    }
}
