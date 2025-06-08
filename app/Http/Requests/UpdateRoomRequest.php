<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:rooms,name,except,'.$this->route('room')->id,
            'museum_id' => 'required|exists:museums,id|numeric',
            'description' => 'required|string',
            'image' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del museo es obligatorio.',
            'name.string' => 'El nombre de la sala debe ser una cadena de texto.',
            'name.max' => 'El nombre de la sala no puede exceder los 100 caracteres',
            'name.unique' => 'El nombre de la sala ya existe.',
            'museum_id.required' => 'El Museo es obligatorio.',
            'museum_id.exists' => 'El museo seleccionado no existe',
            'museum_id.numeric' => 'El museo no es valido.',
            'description.required' => 'La descripción del museo es obligatoria.',
            'description.string' => 'La descripcion debe ser una cadena de texto.'
        ];
    }
}
