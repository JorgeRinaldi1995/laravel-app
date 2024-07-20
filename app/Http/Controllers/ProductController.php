<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                
                'name' => 'required|string|unique:products',
                'price_per_unit' => 'required|numeric',
                'basic_unit' => 'nullable|string',
                'tax_percentage' => 'nullable|numeric',
                'limited' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = Auth::user();
            if ($user->role !== 'manager') {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $product = Product::create([
                'name' => $request->name,
                'price_per_unit' => $request->price_per_unit,
                'basic_unit' => $request->basic_unit,
                'tax_percentage' => $request->tax_percentage,
                'limited' => $request->limited,
                'active_for_sale' => false,
                'manager_id' => $user->id, // Set manager_id
            ]);
    
            return response()->json(['message' => 'Product registered successfully', 'product' => $product], 201);
        } catch (\Exception $e) {
            Log::error('Registration Error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string', 
            'price_per_unit' => 'required|numeric',
            'basic_unit' => 'nullable|string',
            'tax_percentage' => 'nullable|numeric',
            'limited' => 'required|boolean',
            'active_for_sale' => 'required|boolean',
        ]);

        $product->update($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
