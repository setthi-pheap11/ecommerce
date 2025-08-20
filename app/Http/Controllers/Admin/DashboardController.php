<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple statistics without complex queries first
        $totalOrders = 0;
        $totalRevenue = 0;
        $totalProducts = 0;
        $totalUsers = 0;
        
        try {
            $totalOrders = Order::count();
            $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
            $totalProducts = Product::count();
            $totalUsers = User::where('role', 'customer')->count();
        } catch (\Exception $e) {
            // If there's an error, use default values
        }

        // Get recent orders
        $recentOrders = [];
        try {
            $recentOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            // If there's an error, use empty array
        }

        // Get top products (by order count)
        $topProducts = [];
        try {
            $topProducts = Product::with('images')
                ->withCount(['orderItems as orders_count'])
                ->orderBy('orders_count', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            // If there's an error, use empty array
        }

        // Get monthly sales data with PostgreSQL-compatible query
        $salesData = array_fill(0, 12, 0);
        try {
            $monthlySales = Order::select(
                    DB::raw('EXTRACT(MONTH FROM created_at)::integer as month'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->where('created_at', '>=', now()->startOfYear())
                ->where('status', '!=', 'cancelled')
                ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
                ->pluck('total', 'month')
                ->toArray();
                
            // Fill the array with actual data
            for ($i = 1; $i <= 12; $i++) {
                $salesData[$i - 1] = floatval($monthlySales[$i] ?? 0);
            }
        } catch (\Exception $e) {
            // If there's an error, keep the default zero array
            \Log::error('Monthly sales query error: ' . $e->getMessage());
        }

        return view('admin.dashboard.index', [
            'stats' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'total_products' => $totalProducts,
                'total_users' => $totalUsers,
            ],
            'recent_orders' => $recentOrders,
            'recent_products' => $topProducts,
            'monthly_sales' => $salesData
        ]);
    }
}
