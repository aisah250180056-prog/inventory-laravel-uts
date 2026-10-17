<?php
namespace App\Http\Controllers;
use App\Models\Supplier; use Illuminate\Http\Request;
class SupplierController extends Controller {
 public function index(){ $suppliers=Supplier::withCount('products')->orderBy('name')->paginate(10); return view('suppliers.index',compact('suppliers')); }
 public function store(Request $r){ abort_unless($r->user()->isAdmin(),403); $d=$r->validate(['name'=>'required|string|max:150','phone'=>'nullable|string|max:30','email'=>'nullable|email|max:150','address'=>'nullable|string|max:2000']); Supplier::create($d); return back()->with('success','Pemasok berhasil ditambahkan.'); }
 public function update(Request $r,Supplier $supplier){ abort_unless($r->user()->isAdmin(),403); $d=$r->validate(['name'=>'required|string|max:150','phone'=>'nullable|string|max:30','email'=>'nullable|email|max:150','address'=>'nullable|string|max:2000']); $supplier->update($d); return back()->with('success','Pemasok diperbarui.'); }
 public function destroy(Supplier $supplier){ abort_unless(auth()->user()?->isAdmin(), 403); if($supplier->products()->exists()) return back()->withErrors(['supplier'=>'Pemasok masih digunakan produk.']); $supplier->delete(); return back()->with('success','Pemasok dihapus.'); }
}
