<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CotizacionGrupal;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CotizacionGrupalController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'museum_id' => 'required|exists:museums,id',
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s|after:start_hour',
            'total_people' => 'required|integer|min:3',
            'total_people_discount' => 'required|integer|min:0',
            'total_people_whitout_discount' => 'required|integer|min:0',
            'total_whit_discount' => 'required|numeric|min:0',
            'total_whitout_discount' => 'required|numeric|min:0',
            'price_total' => 'required|numeric|min:0',
        ]);

        // // Validar que total_people = total_people_discount + total_people_whitout_discount
        // if ($validated['total_people'] !== ($validated['total_people_discount'] + $validated['total_people_whitout_discount'])) {
        //     return response()->json(['error' => 'El total de personas no coincide con la suma de personas con y sin descuento'], 422);
        // }

        // Obtener el museo
        $museum = Museum::findOrFail($validated['museum_id']);

        // Validar el horario contra los horarios del museo
        $startHour = \Carbon\Carbon::parse($validated['start_hour']);
        $endHour = \Carbon\Carbon::parse($validated['end_hour']);
        $museumOpen = \Carbon\Carbon::parse($museum->opening_hour);
        $museumClose = \Carbon\Carbon::parse($museum->closing_hour);

        // if ($startHour < $museumOpen || $endHour > $museumClose) {
        //     return response()->json(['error' => "El horario seleccionado está fuera del horario del museo ({$museum->opening_hour} - {$museum->closing_hour})"], 422);
        // }

        // Generar un unique_id
        $uniqueId = Str::uuid()->toString();

        // Crear la cotización
        $cotizacion = CotizacionGrupal::create(array_merge($validated, ['unique_id' => $uniqueId]));

        // Retornar respuesta JSON con el unique_id y el nombre del museo
        return response()->json([
            'message' => 'Cotización creada exitosamente',
            'unique_id' => $uniqueId,
            'cotizacion' => array_merge($cotizacion->toArray(), ['museum_name' => $museum->name])
        ], 201);
    }

    public function show($uniqueId)
    {
        $cotizacion = CotizacionGrupal::with('museum')->where('unique_id', $uniqueId)->firstOrFail();
        return response()->json(array_merge($cotizacion->toArray(), ['museum_name' => $cotizacion->museum->name]));
    }
}
