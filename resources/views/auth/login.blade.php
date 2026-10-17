@extends('layouts.app')
@section('title','Login')
@section('content')<main class="container narrow"><h1>Login Inventory</h1><p>Masuk untuk mengelola persediaan.</p><form method="POST" action="{{ route('login.store') }}">@csrf<label>Username atau email</label><input name="identity" value="{{ old('identity') }}" required autofocus><label>Kata sandi</label><input type="password" name="password" required><label><input type="checkbox" name="remember" value="1"> Ingat saya</label><button type="submit">Login</button></form></main>@endsection
