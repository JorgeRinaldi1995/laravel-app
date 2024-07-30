<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {

            $user = $request->user();

            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
            $product = Product::findOrFail($request->product_id);

            if ($product->active_for_sale) {
                $cartItem = $cart->items()->where('product_id', $product->id)->first();

                if ($cartItem) {
                    $cartItem->quantity += $request->quantity;
                    $cartItem->save();
                } else {
                    $cartItem = $cart->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $request->quantity,
                    ]);
                }

                return response()->json($cartItem, 201);
            } else {
                return response()->json(['error' => 'Product is not available for sale'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function viewCart(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('items.product')->firstOrFail();

        return response()->json($cart);
    }

    public function updateCart(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $cartItem = $cart->items()->where('product_id', $request->product_id)->firstOrFail();

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json($cartItem);
    }

    public function removeFromCart(Request $request)
    {
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $cartItem = $cart->items()->where('product_id', $request->product_id)->firstOrFail();

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart'], 200);
    }
}