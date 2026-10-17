<?php
namespace App\Http\Controllers;
use App\Models\User; use Illuminate\Http\Request;
class UserController extends Controller {
 public function index(){ abort_unless(auth()->user()->isAdmin(),403); $users=User::latest()->paginate(15); return view('users.index',compact('users')); }
 public function store(Request $r){ abort_unless($r->user()->isAdmin(),403); $d=$r->validate(['name'=>'required|string|max:100','username'=>'required|string|max:50|alpha_dash|unique:users,username','email'=>'required|email|max:255|unique:users,email','password'=>'required|string|min:10','role'=>'required|in:admin,staff']); User::create($d); return back()->with('success','Pengguna berhasil dibuat.'); }
}
