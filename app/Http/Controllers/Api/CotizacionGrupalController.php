<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CotizacionGrupal;
use App\Models\Museum;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon; // Importar Carbon para facilitar el manejo de fechas y horas

class CotizacionGrupalController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'museum_id' => 'required|exists:museums,id',
            'appointment_date' => 'required|date_format:Y-m-d', // Nuevo campo para la fecha de la cita
            'start_hour' => 'required|date_format:H:i:s',
            'end_hour' => 'required|date_format:H:i:s|after:start_hour',
            'total_people' => 'required|integer|min:3',
            'total_people_discount' => 'required|integer|min:0',
            'total_people_whitout_discount' => 'required|integer|min:0',
            'total_infants' => 'required|integer|min:0', // Nuevo campo para el total de infantes
            'total_whit_discount' => 'required|numeric|min:0',
            'total_whitout_discount' => 'required|numeric|min:0',
            'price_total' => 'required|numeric|min:0',
        ]);


        // if ($validated['total_people'] !== ($validated['total_people_discount'] + $validated['total_people_whitout_discount'] + $validated['total_infants'])) {
        //     return response()->json(['error' => 'El total de personas no coincide con la suma de personas con descuento, sin descuento e infantes'], 422);
        // }

        // Obtener el museo
        $museum = Museum::findOrFail($validated['museum_id']);

        // Combinar la fecha y las horas para crear objetos Carbon completos
        // Esto es crucial para comparar con los horarios del museo si estos incluyen fecha,
        // o para asegurar que la comparación de horas se haga en el contexto de un día.
        $appointmentDate = Carbon::parse($validated['appointment_date']);
        $startDateTime = $appointmentDate->copy()->setTimeFromTimeString($validated['start_hour']);
        $endDateTime = $appointmentDate->copy()->setTimeFromTimeString($validated['end_hour']);

        // Obtener los horarios de apertura y cierre del museo
        // Se ha corregido el nombre de las columnas a 'opening_time' y 'closing_time'
        $museumOpenHour = Carbon::parse($museum->opening_time);
        $museumCloseHour = Carbon::parse($museum->closing_time);

        // Validar que el horario seleccionado esté dentro del horario del museo
        // Comparamos solo las partes de la hora de los objetos Carbon
        // if ($startDateTime->format('H:i:s') < $museumOpenHour->format('H:i:s') || $endDateTime->format('H:i:s') > $museumCloseHour->format('H:i:s')) {
        //      return response()->json(['error' => "El horario seleccionado está fuera del horario del museo ({$museum->opening_time} - {$museum->closing_time})"], 422);
        // }

        // Generar un unique_id
        $uniqueId = Str::uuid()->toString();

        // Crear la cotización
        // Asegúrate de que tu modelo CotizacionGrupal tenga 'appointment_date' y 'total_infants' en su propiedad $fillable
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

    public function index(){
        return CotizacionGrupal::with('museum')->get();
    }
}
