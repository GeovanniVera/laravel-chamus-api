<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RoomResource extends JsonResource
{
    

    
    public function toArray(Request $request): array
    {

        $defaultImageUrl = asset('img/image-not-found.svg'); 
        $imageUrl = $defaultImageUrl; 


        if (!empty($this->image)) {
            if (Storage::exists($this->image)) {
                $imageUrl = Storage::url($this->image);
            }
        }


        return [
            'id' => $this->id,
            'nombre' => $this->name,
            'imagen' => $imageUrl,
            'descripcion' => $this->description,
            'creado' => $this->created_at,
            'actualizado' => $this->updated_at,
            'museo' =>MuseumResource::make($this->whenLoaded('museum')),
            
        ];
    }
}
