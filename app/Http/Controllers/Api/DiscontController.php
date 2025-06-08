<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'discount' => 'numeric'
        ]);

        $discount = Discount::create($data);

        return response()->json($discount,201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return response()->json($discount,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        $data = $request->validate([
            'discount' => 'numeric'
        ]);

        $discount->update($data);

        return response()->json($discount,200);

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
