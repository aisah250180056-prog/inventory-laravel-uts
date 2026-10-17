<?php
namespace App\Http\Controllers;
use App\Models\Product; use App\Models\StockMovement; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB; use Illuminate\Validation\ValidationException;
class StockController extends Controller {
 public function index(){ $products=Product::orderBy('name')->get(); $movements=StockMovement::with(['product','user'])->latest()->paginate(20); return view('stock.index',compact('products','movements')); }
 public function store(Request $r){ $d=$r->validate(['product_id'=>'required|exists:products,id','type'=>'required|in:IN,OUT','quantity'=>'required|integer|min:1','note'=>'nullable|string|max:255']); DB::transaction(function()use($r,$d){ $product=Product::whereKey($d['product_id'])->lockForUpdate()->firstOrFail(); if($d['type']==='OUT' && $product->stock < $d['quantity']) throw ValidationException::withMessages(['quantity'=>'Stok tidak mencukupi. Stok tersedia: '.$product->stock.'.']); $product->stock += $d['type']==='IN' ? $d['quantity'] : -$d['quantity']; $product->save(); StockMovement::create($d+['user_id'=>$r->user()->id]); }); return back()->with('success','Transaksi stok berhasil disimpan.'); }
}
