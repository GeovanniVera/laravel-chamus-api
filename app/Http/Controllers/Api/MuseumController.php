<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MuseumResource;
use App\Models\Museum;
use App\Http\Requests\StoreMuseumRequest;
use App\Http\Requests\UpadateMuseumRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MuseumController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $museums = Museum::with(['categories', 'discounts', 'rooms'])->get();
        return response()->json(MuseumResource::collection($museums), 200);
    }

    public function show(Museum $museum)
    {
        $museum->load(['rooms', 'categories', 'discounts']);
        return response()->json(MuseumResource::make($museum), 200);
    }

    public function store(StoreMuseumRequest $request)
    {
        $data = $request->validated();

        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);

        if (request()->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('museums', request()->file('image'));
        } else {
            $data['image'] = 'https://www.publicdomainpictures.net/view-image.php?image=270609&picture=not-found-image';
        }

        $data['user_id'] = auth('api')->id();
        $museum = Museum::create($data);

        $museum->categories()->sync($categoryIds);

        $museum->load(['categories', 'discounts', 'rooms']);

        return response()->json(MuseumResource::make($museum), 201);
    }

    public function update(UpadateMuseumRequest $request, Museum $museum)
    {
        Gate::authorize('update', $museum);

        // Inicia una transacción de base de datos
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $categoryIds = null;
            if (isset($data['category_ids'])) {
                $categoryIds = $data['category_ids'];
                unset($data['category_ids']);
            }

            // Lógica de manejo de imagen
            if ($request->hasFile('image')) {
                if ($museum->image && !str_starts_with($museum->image, 'https://www.publicdomainpictures.net/')) {
                    Storage::disk('public')->delete($museum->image);
                }
                $data['image'] = Storage::disk('public')->put('museums', $request->file('image'));
            } elseif (isset($data['image']) && $data['image'] === null) {
                if ($museum->image && !str_starts_with($museum->image, 'https://www.publicdomainpictures.net/')) {
                    Storage::disk('public')->delete($museum->image);
                }
                $data['image'] = null;
            } else {
                unset($data['image']);
            }

            // Intenta actualizar el modelo principal
            $museum->update($data);

            // Intenta sincronizar las categorías
            if ($categoryIds !== null) {
                $museum->categories()->sync($categoryIds);
            }

            // Si todo fue bien, confirma la transacción
            DB::commit();

            // Carga las relaciones y devuelve la respuesta exitosa
            $museum->load(['categories', 'discounts', 'rooms']);
            return response()->json(MuseumResource::make($museum), 200);

        } catch (Throwable $e) { // Captura cualquier tipo de excepción
            // Si algo falla, revierte la transacción
            DB::rollBack();

            // Loggea el error para depuración
            Log::error('Error al actualizar el museo: ' . $e->getMessage(), ['museum_id' => $museum->id, 'trace' => $e->getTraceAsString()]);

            // Retorna una respuesta de error al cliente
            return response()->json([
                'message' => 'Hubo un error al actualizar el museo.',
                'error' => $e->getMessage() // Para depuración, en producción podrías omitir e.getMessage()
            ], 500); // Código de estado 500 para error interno del servidor
        }
    }

    public function destroy(Museum $museum)
    {
        Gate::authorize('delete', $museum);
        $museum->delete();
        return response()->json(null, 204);
    }
}
