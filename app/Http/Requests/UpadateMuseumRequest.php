<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpadateMuseumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:100', // Changed to sometimes
            'opening_time' => 'sometimes|required', // Changed to sometimes
            'clossing_time' => 'sometimes|required', // Changed to sometimes
            'latitude' => 'sometimes|numeric', // Added 'sometimes' and 'numeric'
            'longitude' => 'nullable|numeric', // 'nullable' can remain, but 'sometimes' might also be useful
            'ticket_price' => 'sometimes|numeric|min:0', // Changed to sometimes
            'url' => 'nullable|url', // 'nullable' can remain, but 'sometimes' might also be useful
            'description' => 'sometimes|string', // Changed to sometimes
            'number_of_rooms' => 'sometimes|integer|min:1', // Changed to sometimes
            'status' => 'sometimes|in:active,inactive', // Changed to sometimes
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048', // Added image validation back
            'category_ids' => 'sometimes|array', // Added for categories
            'category_ids.*' => 'exists:categories,id', // Added for categories
        ];
    }

    /** Mensajes de validacion */

    public function messages(): array
    {
        return [
            'name.sometimes' => 'El nombre del museo no es válido.', // New message for sometimes
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',

            'opening_time.sometimes' => 'La hora de apertura no es válida.',
            'opening_time.required' => 'La hora de apertura es obligatoria si se envía.',

            'clossing_time.sometimes' => 'La hora de cierre no es válida.',
            'clossing_time.required' => 'La hora de cierre es obligatoria si se envía.',

            'latitude.sometimes' => 'La latitud no es válida.',
            'latitude.numeric' => 'La latitud debe ser un número.',

            'longitude.numeric' => 'La longitud debe ser un número.',

            'ticket_price.sometimes' => 'El precio del ticket no es válido.',
            'ticket_price.numeric' => 'El precio del ticket debe ser un número.',
            'ticket_price.min' => 'El precio del ticket no puede ser negativo.',

            'url.url' => 'La URL proporcionada no es válida.',

            'description.sometimes' => 'La descripción no es válida.',
            'description.string' => 'La descripción debe ser una cadena de texto.',

            'number_of_rooms.sometimes' => 'El número de salas no es válido.',
            'number_of_rooms.integer' => 'El número de salas debe ser un entero.',
            'number_of_rooms.min' => 'El número de salas debe ser al menos 1.',

            'status.sometimes' => 'El estado no es válido.',
            'status.in' => 'El estado debe ser "active" o "inactive".',

            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, svg.',
            'image.max' => 'La imagen no debe exceder los 2MB.',

            'category_ids.sometimes' => 'Las categorías no son válidas.',
            'category_ids.array' => 'Las categorías deben enviarse como un arreglo.',
            'category_ids.*.exists' => 'Una o más categorías seleccionadas no son válidas.',
        ];
    }
}
