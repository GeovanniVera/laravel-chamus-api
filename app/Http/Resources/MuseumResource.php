<?php

namespace App\Http\Resources;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MuseumResource extends JsonResource
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
            'hora_de_apertura' => $this->opening_time,
            'hora_de_cierre' => $this->clossing_time,
            'latitud' => $this->latitude,
            'longitud' => $this->longitude,
            'descripcion' => $this->description, // Descripción del museo
            'precio' => $this->ticket_price,
            'url' => $this->url,
            'numero_de_salas' => $this->number_of_rooms,
            'estado' => $this->status,
            'creado' => $this->created_at,
            'actualizado' => $this->updated_at,
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            // Recupera los descuentos y sus datos de la tabla pivote
            'descuentos_asociados' => $this->whenLoaded('discounts', function () {
                return $this->discounts->map(function ($discount) {
                    return [
                        'id' => $discount->id,
                        'valor_descuento' => $discount->discount,
                        // Accede a la descripción de la tabla pivote a través del objeto 'pivot'
                        'descripcion_aplicacion' => $discount->pivot->description,
                    ];
                });
            }),
        ];
    }
}
