<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Category; use App\Models\Supplier; use App\Models\StockMovement;
class DashboardController extends Controller {
 public function index() { $stats=['products'=>Product::count(),'categories'=>Category::count(),'suppliers'=>Supplier::count(),'units'=>(int)Product::sum('stock'),'value'=>(float)Product::selectRaw('COALESCE(SUM(price * stock),0) as total')->value('total')]; $lowStock=Product::with('category')->where('stock','<=',5)->orderBy('stock')->limit(10)->get(); $recent=StockMovement::with(['product','user'])->latest()->limit(8)->get(); return view('dashboard',compact('stats','lowStock','recent')); }
}
