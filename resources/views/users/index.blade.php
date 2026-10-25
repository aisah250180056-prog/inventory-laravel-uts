@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<main class="container">
    <h1>Manajemen Pengguna</h1>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px; margin-top: 20px;">
        <!-- Form Tambah Pengguna -->
        <section style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="margin-top: 0;">Tambah Pengguna</h2>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Nama Lengkap</label>
                    <input name="name" value="{{ old('name') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    @error('name')<p style="color: #dc2626; font-size: 12px;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Username</label>
                    <input name="username" value="{{ old('username') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    @error('username')<p style="color: #dc2626; font-size: 12px;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    @error('email')<p style="color: #dc2626; font-size: 12px;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Password (Min. 8 karakter)</label>
                    <input type="password" name="password" minlength="8" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    @error('password')<p style="color: #dc2626; font-size: 12px;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 4px;">Peran (Role)</label>
                    <select name="role" required style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Kasir / Petugas)</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                        <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer (Pelanggan)</option>
                    </select>
                    @error('role')<p style="color: #dc2626; font-size: 12px;">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="btn" style="width: 100%;">+ Tambah Pengguna</button>
            </form>
        </section>

        <!-- Daftar Pengguna -->
        <section style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h2 style="margin-top: 0;">Daftar Akun Terdaftar</h2>
            <div style="overflow-x: auto;">
                <table border="1" cellpadding="8" cellspacing="0" width="100%">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span style="background: #e0e7ff; color: #3730a3; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Admin</span>
                                    @elseif($user->role === 'staff')
                                        <span style="background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Staff</span>
                                    @else
                                        <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Customer</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 15px;">
                {{ $users->links() }}
            </div>
        </section>
    </div>
</main>
@endsection
