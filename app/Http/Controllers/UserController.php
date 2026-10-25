<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Akses khusus Admin.');

        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403, 'Akses khusus Admin.');

        $data = $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff,customer',
        ]);

        User::create($data);

        return back()->with('success', 'Pengguna baru berhasil dibuat.');
    }
}
