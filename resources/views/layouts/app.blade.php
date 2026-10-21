<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory Management System')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    @auth
        <header class="topbar">
            <a class="brand" href="{{ route('dashboard') }}">
                Inventory <span>Management</span>
            </a>

            <div class="topbar-user">
                <span>
                    {{ auth()->user()->name }}
                    ({{ ucfirst(auth()->user()->role ?? 'staff') }})
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <nav class="main-nav">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}">Produk</a>
            <a href="{{ route('categories.index') }}">Kategori</a>
            <a href="{{ route('suppliers.index') }}">Pemasok</a>
            <a href="{{ route('stock.index') }}">Transaksi Stok</a>
            <a href="{{ route('purchases.index') }}">Pembelian</a>
            <a href="{{ route('sales.index') }}">Penjualan</a>
            <a href="{{ route('reports.index') }}">Laporan</a>

            @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}">Pengguna</a>
            @endif
        </nav>
    @endauth

    @if(session('success'))
        <div class="container">
            <p class="success">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="container">
            <div class="error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')
</body>
</html>
