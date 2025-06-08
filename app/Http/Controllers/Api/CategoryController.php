<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json($categories,200);
    }

    public function show(Category $category){
        return response()->json($category,200);
    }

    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required|string'
        ]);

        $category = Category::create($data);
        return response()->json($category,201);
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
        return response()->json(null,404);
    }
}
