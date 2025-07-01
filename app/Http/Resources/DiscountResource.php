<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id, // Incluye el ID del descuento
            'valor_descuento' => $this->discount,
            'museos_asociados' => $this->whenLoaded('museums', function () {
                // Mapea la colección de museos para incluir los datos de la tabla pivote
                return $this->museums->map(function ($museum) {
                    return [
                        'id' => $museum->id,
                        'nombre' => $museum->name, // Asume que el modelo Museum tiene un campo 'name'
                        'descripcion_en_descuento' => $museum->pivot->description,
                    ];
                });
            }),
            // También puedes incluir la primera descripción si solo esperas una por descuento
            'primera_descripcion_pivote' => $this->whenLoaded('museums', function () {
                return $this->museums->first()->pivot->description ?? null;
            }),
        ];
    }
}
