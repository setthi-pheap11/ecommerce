<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Get user's cart
     */
    public function index(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->load(['items.product.primaryImage']);

        return response()->json([
            'cart' => $cart,
            'total_amount' => $cart->getTotalAmount(),
            'total_items' => $cart->getTotalItems(),
        ]);
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check stock
        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 400);
        }

        $cart = $this->getOrCreateCart($request);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update existing item
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'message' => 'Insufficient stock',
                ], 400);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->getCurrentPrice(),
            ]);
        }

        $cart->load(['items.product.primaryImage']);

        return response()->json([
            'message' => 'Item added to cart',
            'cart' => $cart,
            'total_amount' => $cart->getTotalAmount(),
            'total_items' => $cart->getTotalItems(),
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if item belongs to user's cart
        $cart = $this->getOrCreateCart($request);
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check stock
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return response()->json([
                'message' => 'Insufficient stock',
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        $cart->load(['items.product.primaryImage']);

        return response()->json([
            'message' => 'Cart item updated',
            'cart' => $cart,
            'total_amount' => $cart->getTotalAmount(),
            'total_items' => $cart->getTotalItems(),
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, CartItem $cartItem)
    {
        // Check if item belongs to user's cart
        $cart = $this->getOrCreateCart($request);
        if ($cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $cartItem->delete();
        $cart->load(['items.product.primaryImage']);

        return response()->json([
            'message' => 'Item removed from cart',
            'cart' => $cart,
            'total_amount' => $cart->getTotalAmount(),
            'total_items' => $cart->getTotalItems(),
        ]);
    }

    /**
     * Clear cart
     */
    public function clear(Request $request)
    {
        $cart = $this->getOrCreateCart($request);
        $cart->items()->delete();

        return response()->json([
            'message' => 'Cart cleared',
        ]);
    }

    /**
     * Get or create cart for user/session
     */
    private function getOrCreateCart(Request $request)
    {
        if ($request->user()) {
            return Cart::firstOrCreate(['user_id' => $request->user()->id]);
        } else {
            $sessionId = $request->session()->getId();
            return Cart::firstOrCreate(['session_id' => $sessionId]);
        }
    }
}
