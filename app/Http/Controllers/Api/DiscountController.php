<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'discount' => 'required|numeric', // El valor del descuento (ej. 0.15)
            'museum_id' => 'required|exists:museums,id', // El ID de un solo museo
            'description' => 'nullable|string|max:255' // La descripción, puede ser opcional
        ]);

        // 1. Crea el nuevo descuento en la tabla 'discounts'
        $discount = Discount::create([
            'discount' => $data['discount']
            // Si tienes más campos en la tabla 'discounts', agrégalos aquí
        ]);

        // 2. Adjunta el descuento al museo en la tabla pivote
        // 'attach' es adecuado aquí si siempre vas a agregar una nueva relación
        $pivotData = [];
        if (isset($data['description'])) {
            $pivotData['description'] = $data['description'];
        }

        // Relaciona el descuento recién creado con el museo,
        // incluyendo la descripción si se envió.
        $discount->museums()->attach($data['museum_id'], $pivotData);

        // Opcional: Carga la relación para devolver el museo asociado en la respuesta
        $discount->load('museums');

        return response()->json($discount, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return response()->json($discount, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'discount' => 'sometimes|numeric',
            'museum_id' => 'sometimes|exists:museums,id',
            'description' => 'nullable|string|max:255'
        ]);

        if (isset($data['discount'])) {
            $discount->update(['discount' => $data['discount']]);
        }

        if (isset($data['museum_id'])) {
            $pivotData = [];
            if (isset($data['description'])) {
                $pivotData['description'] = $data['description'];
            }

            // Para actualizar la relación, primero desvincula y luego adjunta la nueva relación.
            // Esto asegura que el descuento esté asociado solo con el 'museum_id' enviado.
            $discount->museums()->detach();
            $discount->museums()->attach($data['museum_id'], $pivotData);
        } elseif (isset($data['description'])) {
            // Si solo se envía la descripción y no un museum_id, actualiza la descripción
            // de la primera relación existente. Considera la lógica si hay múltiples museos.
            $currentMuseums = $discount->museums;
            if ($currentMuseums->isNotEmpty()) {
                $currentMuseum = $currentMuseums->first();
                $discount->museums()->updateExistingPivot($currentMuseum->id, ['description' => $data['description']]);
            }
        }

        $discount->load('museums');

        return response()->json($discount, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return response()->noContent();
    }
}
