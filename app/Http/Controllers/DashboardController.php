<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Jika yang login adalah Customer, arahkan ke tampilan portal Customer
        if ($request->user()->isCustomer()) {
            return app(CustomerController::class)->dashboard($request);
        }

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $stats = [
            'products' => Product::count(),
            'categories' => Category::count(),
            'suppliers' => Supplier::count(),
            'units' => (int) Product::sum('stock'),
            
            // Total penjualan selesai
            'total_sales' => (float) Sale::where('status', 'completed')->sum('total'),
            'sales_today' => (float) Sale::where('status', 'completed')->whereDate('sold_at', $today)->sum('total'),
            'sales_this_month' => (float) Sale::where('status', 'completed')->where('sold_at', '>=', $startOfMonth)->sum('total'),
            
            // Jumlah transaksi
            'sales_count' => Sale::count(),
            'purchases_count' => Purchase::count(),
            'total_transactions' => Sale::count() + Purchase::count(),
            
            // Total pembelian
            'total_purchases' => (float) Purchase::where('status', 'completed')->sum('total'),
            
            // Jumlah produk dengan stok di bawah atau sama dengan batas minimum
            'low_stock_count' => Product::whereColumn('stock', '<=', 'minimum_stock')->count(),
        ];

        // Daftar produk yang stoknya menipis (stock <= minimum_stock)
        $lowStock = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // Mutasi stok terbaru
        $recent = StockMovement::with(['product', 'user'])
            ->latest()
            ->limit(8)
            ->get();

        // Transaksi penjualan terbaru
        $recentSales = Sale::with('user')
            ->latest('sold_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'lowStock', 'recent', 'recentSales'));
    }
}
