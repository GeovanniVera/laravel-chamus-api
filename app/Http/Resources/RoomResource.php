<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RoomResource extends JsonResource
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
            'nombre' => $this->name,
            'imagen' => Storage::url($this->image),
            'descripcion' => $this->description,
            'creado' => $this->created_at,
            'actualizado' => $this->updated_at,
            'museo' =>MuseumResource::make($this->whenLoaded('museum')),
            
        ];
    }
}
