<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MuseumResource;
use App\Models\Museum;
use App\Http\Requests\StoreMuseumRequest;
use App\Http\Requests\UpadateMuseumRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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
        $museums = Museum::with('rooms')->get();
        return response()->json(MuseumResource::collection($museums), 200);
    }

    public function show(Museum $museum)
    {
        return response()->json(MuseumResource::make($museum), 200);
    }

    public function store(StoreMuseumRequest $request)
    {
        $data = $request->validated();
        if(request()->hasFile('image')){
            $data['image'] = Storage::disk('public')->put('museums',request()->file('image'));
        }else{
            $data['image'] = 'https://www.publicdomainpictures.net/view-image.php?image=270609&picture=not-found-image' ;
        }
        $data['user_id'] = auth('api')->id();
        $museum = Museum::create($data);
        return response()->json($museum, 201);
    }

    public function update(UpadateMuseumRequest $request, Museum $museum)
    {
        Gate::authorize('update', $museum);
        $data = $request->validated();
        if(request()->hasFile('image')){
            //Delete the old image if exists
            if($museum->image){
                Storage::disk('public')->delete($museum->image);
            }
            $data['image'] = Storage::disk('public')->put('museums',request()->file('image'));
        }else{
            $data['image'] = 'https://www.publicdomainpictures.net/view-image.php?image=270609&picture=not-found-image' ;
        }
        $museum->update($data);
        return response()->json(MuseumResource::make($museum), 200);
    }

    public function destroy(Museum $museum)
    {
        Gate::authorize('delete', $museum);
        $museum->delete();
        return response()->json(null, 204);
    }
}
