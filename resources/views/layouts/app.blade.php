<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventory & Sales Management')</title>
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
                    <strong>{{ auth()->user()->name }}</strong>
                    <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 4px; font-size: 12px; margin-left: 6px;">
                        {{ ucfirst(auth()->user()->role ?? 'staff') }}
                    </span>
                </span>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <nav class="main-nav">
            @if(auth()->user()->isCustomer())
                <!-- Menu Khusus Customer -->
                <a href="{{ route('dashboard') }}">Katalog Produk</a>
                <a href="{{ route('customer.order') }}">Beli / Pesan Barang</a>
                <a href="{{ route('customer.orders') }}">Riwayat Pesanan Saya</a>
            @else
                <!-- Menu Back-Office untuk Staff & Admin -->
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
            @endif
        </nav>
    @endauth

    @if(session('success'))
        <div class="container" style="margin-top: 15px;">
            <p class="success" style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 6px; border: 1px solid #bbf7d0;">
                ✓ {{ session('success') }}
            </p>
        </div>
    @endif

    @if($errors->any())
        <div class="container" style="margin-top: 15px;">
            <div class="error" style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 6px; border: 1px solid #fecdd3;">
                <ul style="margin: 0; padding-left: 20px;">
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
