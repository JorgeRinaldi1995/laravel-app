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
        $user = Auth::user();
        
        if($user->role === 'admin'){
            $products = Product::all();
        } else if($user->role === 'manager'){
            $products = Product::where('manager_id', $user->id)->get();
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($products, 200);
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
                'stock' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = Auth::user();

            $product = Product::create([
                'name' => $request->name,
                'price_per_unit' => $request->price_per_unit,
                'basic_unit' => $request->basic_unit,
                'tax_percentage' => $request->tax_percentage,
                'limited' => $request->limited,
                'stock' => $request->stock,
                'active_for_sale' => false,
                'manager_id' => $user->id,
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
    public function update(Request $request, $product_id)
    {
        try {
            $user = Auth::user();
            $product = Product::find($product_id);
        
            if(!$product) {
                return response()->json(['error'=> 'Product not found or unauthorized'],404);
            }

            if ($user->role === 'manager' && $product->manager_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|unique:products,name,' . $product_id,
                'price_per_unit' => 'sometimes|required|numeric',
                'basic_unit' => 'sometimes|nullable|string',
                'tax_percentage' => 'sometimes|nullable|numeric',
                'limited' => 'sometimes|required|boolean',
                'stock' => 'sometimes|nullable|numeric',
                'active_for_sale' => 'sometimes|required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            if ($user->role === 'admin') {
                // Admins can only update the active_for_sale attribute
                if ($request->has('active_for_sale')) {
                    $product->active_for_sale = $request->input('active_for_sale');
                } else {
                    return response()->json(['error' => 'Only active_for_sale attribute can be updated by admin'], 422);
                }
            } else {
                // Managers can update other product attributes but not active_for_sale
                $product->name = $request->input('name', $product->name);
                $product->price_per_unit = $request->input('price_per_unit', $product->price_per_unit);
                $product->basic_unit = $request->input('basic_unit', $product->basic_unit);
                $product->tax_percentage = $request->input('tax_percentage', $product->tax_percentage);
                $product->limited = $request->input('limited', $product->limited);
                $product->stock = $request->input('stock', $product->stock);

                if ($request->has('active_for_sale')) {
                    return response()->json(['error' => 'Unauthorized to update active_for_sale'], 403);
                }
            }

            $product->save();

            $responseData = $product->toArray();
            if ($user->role !== 'admin') {
                unset($responseData['active_for_sale']);
            }

            return response()->json(['message' => 'Product updated successfully', 'product' => $responseData], 200);
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
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
