<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders);
    }

    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|array',
            'billing_address' => 'required|array',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 400);
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if (!$item->product->in_stock || $item->product->stock_quantity < $item->quantity) {
                return response()->json([
                    'message' => "Product {$item->product->name} is out of stock or insufficient quantity",
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = $cart->getTotalAmount();
            $taxAmount = $subtotal * 0.1; // 10% tax
            $shippingAmount = $subtotal > 100 ? 0 : 10; // Free shipping over $100
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'payment_method' => $request->payment_method,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Create order items and update stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->quantity * $item->price,
                ]);

                // Update product stock
                $item->product->decrement('stock_quantity', $item->quantity);
                if ($item->product->stock_quantity <= 0) {
                    $item->product->update(['in_stock' => false]);
                }
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            $order->load(['items.product']);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified order
     */
    public function show(Request $request, Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $order->load(['items.product']);

        return response()->json([
            'order' => $order,
        ]);
    }

    /**
     * Cancel order (only if pending)
     */
    public function cancel(Request $request, Order $order)
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Order cannot be cancelled',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Restore product stock
            foreach ($order->items as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
                $item->product->update(['in_stock' => true]);
            }

            $order->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => $order,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
