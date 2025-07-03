<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMuseumRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'opening_time' => 'required',
            'clossing_time' => 'required',
            'latitude' => 'numeric',
            'longitude' => 'nullable|numeric',
            'ticket_price' => 'numeric|min:0',
            'url' => 'nullable|url',
            'description' => 'required|string',
            'status' => 'in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            // --- Nuevas reglas para categorías ---
            'category_ids' => 'required|array', // Espera que 'category_ids' sea un array
            'category_ids.*' => 'exists:categories,id', // Cada ID en el array debe existir en la tabla 'categories'
        ];
    }

    /** Mensajes de validacion */

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del museo es obligatorio.',
            'opening_time.required' => 'La hora de apertura es obligatoria.',
            'clossing_time.required' => 'La hora de cierre es obligatoria.',
            'latitude.numeric' => 'La latitud debe ser un número.',
            'longitude.numeric' => 'La longitud debe ser un número.',
            'ticket_price.numeric' => 'El precio del ticket debe ser un número.',
            'ticket_price.min' => 'El precio del ticket no puede ser negativo.',
            'url.url' => 'La URL proporcionada no es válida.',
            'description.required' => 'La descripción del museo es obligatoria.',
            'number_of_rooms.integer' => 'El número de salas debe ser un entero.',
            'number_of_rooms.min' => 'El número de salas debe ser al menos 1.',
            'status.in' => 'El estado debe ser activo o inactivo.',
            // --- Nuevos mensajes de validación para categorías ---
            'category_ids.required' => 'Debes seleccionar al menos una categoría para el museo.',
            'category_ids.array' => 'Las categorías deben enviarse como un arreglo.',
            'category_ids.*.exists' => 'Una o más categorías seleccionadas no son válidas.',
        ];
    }
}
