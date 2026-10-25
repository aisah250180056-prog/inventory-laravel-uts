@extends('layouts.app')

@section('title', 'Daftar Akun Pelanggan (Customer)')

@section('content')
<main class="container" style="max-width: 480px; margin-top: 40px;">
    <div style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <h1 style="text-align: center; margin-bottom: 8px; font-size: 24px;">Pendaftaran Customer</h1>
        <p style="text-align: center; color: #64748b; margin-bottom: 25px;">
            Daftar akun untuk mulai melihat katalog dan memesan produk.
        </p>

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Nama Lengkap</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('name')<p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Username</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Contoh: budi123"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('username')<p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="budi@example.com"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('email')<p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Kata Sandi (Min. 8 karakter)</label>
                <input
                    type="password"
                    name="password"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
                @error('password')<p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>@enderror
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Konfirmasi Kata Sandi</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 12px; font-size: 15px;">
                Daftar Sebagai Customer
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</main>
@endsection
