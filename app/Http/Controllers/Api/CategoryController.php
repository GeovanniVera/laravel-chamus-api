<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class CategoryController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
       return [
            new Middleware('auth:sanctum', except: ['index', 'show']),

        ];
    }

    public function index(){
        $categories = Category::all();
        return response()->json(new CategoryCollection($categories),200);
    }

    public function show(Category $category){
        return response()->json($category,200);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::create($data);
        return response()->json(new CategoryResource($category),201);
    }

    public function update(Request $request, Category $category){
        $data = $request->validate([
            'name' => 'required|string'
        ]);
        $category->update($data);
        return response()->json($category,201);
    }

    public function destroy(Category $category){
        $category->delete();
        return response()->json(null,204);
    }
}
