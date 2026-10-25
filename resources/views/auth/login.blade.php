@extends('layouts.app')

@section('title', 'Login - Inventory & Sales System')

@section('content')
<main class="container narrow" style="max-width: 450px; margin-top: 40px;">
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <h1 style="text-align: center; margin-bottom: 8px; font-size: 24px;">Masuk ke Sistem</h1>
        <p style="text-align: center; color: #64748b; margin-bottom: 25px;">
            Silakan masuk dengan akun Admin, Staff, atau Customer.
        </p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Username atau Email</label>
                <input
                    type="text"
                    name="identity"
                    value="{{ old('identity') }}"
                    placeholder="admin / staff / customer"
                    required
                    autofocus
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kata Sandi</label>
                <input
                    type="password"
                    name="password"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 12px; font-size: 15px;">
                Login
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
            Belum memiliki akun Customer? <a href="{{ route('register') }}" style="font-weight: bold;">Daftar di sini</a>
        </div>

        <!-- Bantuan Akun Demo -->
        <div style="margin-top: 20px; background: #f8fafc; padding: 12px; border-radius: 6px; font-size: 12px; color: #475569;">
            <strong>Akun Demo Tersedia:</strong><br>
            • Admin: <code>admin</code> / <code>Admin123!</code><br>
            • Staff: <code>staff</code> / <code>Staff123!</code><br>
            • Customer: <code>customer</code> / <code>Customer123!</code>
        </div>
    </div>
</main>
@endsection
