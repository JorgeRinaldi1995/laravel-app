<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = $request->user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $product = Product::findOrFail($request->product_id);

        $cartItem = $cart->items()->updateOrCreate(
            ['product_id'=> $product->id],
            ['quantity' => DB::raw('quantity + ' . $request->quantity)],
        );

        return response()->json($cartItem, 201);
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