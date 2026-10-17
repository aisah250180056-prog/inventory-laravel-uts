<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\Category; use App\Models\StockMovement;
class ReportController extends Controller { public function index(){ $stats=['products'=>Product::count(),'units'=>(int)Product::sum('stock'),'value'=>(float)Product::selectRaw('COALESCE(SUM(price*stock),0) as total')->value('total'),'in'=>(int)StockMovement::where('type','IN')->sum('quantity'),'out'=>(int)StockMovement::where('type','OUT')->sum('quantity')]; $categories=Category::withCount('products')->withSum('products','stock')->get(); return view('reports.index',compact('stats','categories')); } }
