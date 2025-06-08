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
use Illuminate\Support\Facades\Storage;

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
        return response()->json(RoomResource::collection($rooms),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $data = $request->validated();
        if(request()->hasFile('image')){
            $data['image'] = Storage::disk('public')->put('rooms',request()->file('image'));
        }else{
            $data['image'] = 'https://www.publicdomainpictures.net/view-image.php?image=270609&picture=not-found-image' ;
        }
        $room = Room::create($data);
        return response()->json(RoomResource::make($room), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return response()->json(RoomResource::make($room),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update($request->only(['name', 'image', 'description', 'museum_id']));
        return response()->json(RoomResource::make($room), 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(null,204);
    }
}
