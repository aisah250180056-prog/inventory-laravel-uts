<?php
namespace App\Http\Controllers;
use App\Models\Category; use Illuminate\Http\Request; use Illuminate\Database\QueryException;
class CategoryController extends Controller {
 public function index(){ $categories=Category::withCount('products')->orderBy('name')->paginate(10); return view('categories.index',compact('categories')); }
 public function store(Request $r){ abort_unless($r->user()->isAdmin(),403); $data=$r->validate(['name'=>'required|string|max:100|unique:categories,name','description'=>'nullable|string|max:2000']); Category::create($data); return back()->with('success','Kategori berhasil ditambahkan.'); }
 public function update(Request $r, Category $category){ abort_unless($r->user()->isAdmin(),403); $data=$r->validate(['name'=>'required|string|max:100|unique:categories,name,'.$category->id,'description'=>'nullable|string|max:2000']); $category->update($data); return back()->with('success','Kategori berhasil diperbarui.'); }
 public function destroy(Category $category){ abort_unless(auth()->user()?->isAdmin(), 403); if($category->products()->exists()) return back()->withErrors(['category'=>'Kategori masih digunakan produk.']); $category->delete(); return back()->with('success','Kategori dihapus.'); }
}
