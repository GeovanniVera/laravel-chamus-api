<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Psy\debug;

class RoomController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show']),

        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::with('museum')->get();
        return response()->json(RoomResource::collection($rooms), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        if (request()->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('rooms', request()->file('image'));
        } else {
            $data['image'] = 'https://www.publicdomainpictures.net/view-image.php?image=270609&picture=not-found-image';
        }
        $room = Room::create($data);
        return response()->json(RoomResource::make($room), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return response()->json(RoomResource::make($room), 200);
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            $data = $request->validated();

            // Procesar la imagen si se envió
            if ($request->hasFile('image')) {
                // Eliminar la imagen antigua si existe
                if ($room->image && Storage::disk('public')->exists($room->image)) {
                    Storage::disk('public')->delete($room->image);
                }
                // Guardar la nueva imagen
                $data['image'] = Storage::disk('public')->put('rooms', $request->file('image'));
            } else {
                // Mantener la imagen existente si no se envió una nueva
                unset($data['image']);
            }

            // Actualizar la sala
            $room->update($data);

            return response()->json(RoomResource::make($room), 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la sala:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al actualizar la sala. Por favor, intenta de nuevo.'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        if(Storage::exists($room->image)) {
            Storage::delete($room->image);
        }
        $room->delete();
        return response()->json(null, 204);
    }
}
