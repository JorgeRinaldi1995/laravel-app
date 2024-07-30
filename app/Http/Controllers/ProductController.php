<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductResources\StoreProductRequest;
use App\Http\Requests\ProductResources\UpdateProductRequest;
use App\Http\Resources\ProductResource;
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
        try {
            $user = Auth::user();
        
            if($user->role === 0){
                $products = Product::all();
            } else if($user->role === 1){
                $products = Product::where('manager_id', $user->id)->get();
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Registration Error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            $user = Auth::user();
            if($user->role === 0 || $user->role === 1){
                $validatedData = $request->validated();
                $validatedData['active_for_sale'] = false;
                $validatedData['manager_id'] = $user->id;

                $product = Product::create($validatedData);

                return response()->json(['message' => 'Product registered successfully', 'product' => $product], 201);
            }

            return response()->json(['error' => 'Unauthorized'], 403);
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
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $product_id)
    {
        try {
            $user = Auth::user();
            $product = Product::find($product_id);
        
            if(!$product) {
                return response()->json(['error'=> 'Product not found or unauthorized'],404);
            }

            $validatedData = $request->validated();

            if ($user->role === 0) {
                // Admins can update the active_for_sale attribute
                if (array_key_exists('active_for_sale', $validatedData)) {
                    $product->active_for_sale = $validatedData['active_for_sale'];
                } else {
                    return response()->json(['error' => 'Only active_for_sale attribute can be updated by admin'], 422);
                }
            } elseif ($user->role === 1) {
                // Managers can update other product attributes but not active_for_sale
                if (array_key_exists('active_for_sale', $validatedData)) {
                    return response()->json(['error' => 'Unauthorized to update active_for_sale'], 403);
                }
                $product->fill($validatedData);
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
    
            $product->save();
    
            // Removing 'active_for_sale' from response if user is not admin
            $responseData = $product->toArray();
            if ($user->role !== 0) {
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
