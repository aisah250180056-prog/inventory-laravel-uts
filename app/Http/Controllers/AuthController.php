<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class AuthController extends Controller {
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){$input=$request->validate(['identity'=>['required','string'],'password'=>['required','string']]);$field=filter_var($input['identity'],FILTER_VALIDATE_EMAIL)?'email':'username'; if(Auth::attempt([$field=>$input['identity'],'password'=>$input['password']],$request->boolean('remember'))){$request->session()->regenerate();return redirect()->intended(route('dashboard'))->with('success','Login berhasil. Selamat datang!');}return back()->withErrors(['identity'=>'Username/email atau kata sandi tidak sesuai.'])->onlyInput('identity');}
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect()->route('login')->with('success','Anda berhasil logout.');}
}
