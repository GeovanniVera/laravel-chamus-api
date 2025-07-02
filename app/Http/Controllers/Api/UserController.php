<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
       return [
            new Middleware('auth:sanctum', except: []),

        ];
    }


    public function index()
    {
        try {
            $users = User::all();
            return response()->json(UserResource::collection($users), 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener usuarios:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al obtener los usuarios'], 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            return response()->json([
                'message' => 'Usuario creado exitosamente.',
                'data' => UserResource::make($user)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear usuario:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al crear el usuario'], 500);
        }
    }

    public function show(User $user)
    {
        return response()->json(UserResource::make($user), 200);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);
            return response()->json([
                'message' => 'Usuario actualizado exitosamente.',
                'data' => UserResource::make($user)
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar usuario:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al actualizar el usuario'], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            if (auth()->id() === $user->id) {
                return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
            }
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado exitosamente.'], 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error al eliminar el usuario'], 500);
        }
    }
}
