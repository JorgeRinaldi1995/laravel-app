<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|unique:products',
            'price_per_unit' => 'required|numeric',
            'basic_unit' => 'nullable|string',
            'tax_percentage' => 'nullable|numeric',
            'limited' => 'required|boolean',
            'active_for_sale' => 'required|boolean',
        ]);

        return Product::create($request->all());
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
            'name' => 'required|string|unique:products,name,' . $product->id,
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
