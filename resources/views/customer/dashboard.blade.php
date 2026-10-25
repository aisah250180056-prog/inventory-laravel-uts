@extends('layouts.app')

@section('title', 'Katalog & Portal Pelanggan')

@section('content')
<main class="container">
    <div style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: #fff; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(37,99,235,0.2);">
        <h1 style="color: #fff; margin: 0 0 10px; font-size: 28px;">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p style="margin: 0; font-size: 15px; opacity: 0.9;">
            Jelajahi produk sembako, makanan, minuman segar, dan kebutuhan harian dengan stok real-time.
        </p>
        <div style="margin-top: 20px;">
            <a href="{{ route('customer.order') }}" style="background: #fff; color: #1e3a8a; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-block;">
                + Mulai Pesan Barang
            </a>
            <a href="{{ route('customer.orders') }}" style="background: rgba(255,255,255,0.2); color: #fff; padding: 10px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; display: inline-block; margin-left: 10px;">
                Lihat Pesanan Saya ({{ $stats['order_count'] }})
            </a>
        </div>
    </div>

    <!-- Ringkasan Akun Customer -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <small style="color: #64748b; font-weight: bold;">TOTAL PESANAN SAYA</small>
            <h2 style="margin: 8px 0 0; color: #1e293b; font-size: 26px;">{{ $stats['order_count'] }} Transaksi</h2>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <small style="color: #64748b; font-weight: bold;">TOTAL BELANJA SELESAI</small>
            <h2 style="margin: 8px 0 0; color: #166534; font-size: 26px;">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</h2>
        </div>
    </div>

    <!-- Katalog Produk dengan Foto Produk -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2>Katalog Produk Tersedia</h2>
        <span style="color: #64748b; font-size: 13px;">Stok diperbarui secara real-time</span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 22px;">
        @forelse($products as $product)
            <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 4px 12px rgba(15,23,42,0.04); transition: transform 0.2s, box-shadow 0.2s;">
                <!-- Gambar Produk -->
                <div style="width: 100%; height: 190px; background: #f1f5f9; position: relative; overflow: hidden;">
                    <img
                        src="{{ $product->image_url }}"
                        alt="{{ $product->name }}"
                        style="width: 100%; height: 100%; object-fit: cover;"
                        loading="lazy"
                        onerror="this.src='https://placehold.co/400x300/e2e8f0/1e293b?text={{ urlencode($product->name) }}'"
                    >
                    <span style="position: absolute; top: 10px; left: 10px; background: rgba(255,255,255,0.92); color: #2563eb; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        {{ $product->category?->name ?? 'Umum' }}
                    </span>
                </div>

                <!-- Informasi Produk -->
                <div style="padding: 18px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <small style="color: #64748b; font-size: 11px; font-weight: 600;">SKU: {{ $product->sku ?? '-' }}</small>
                        <h3 style="margin: 6px 0 10px; font-size: 16px; color: #1e293b; line-height: 1.4;">{{ $product->name }}</h3>
                    </div>

                    <div>
                        <div style="margin-bottom: 14px; padding-top: 10px; border-top: 1px dashed #e2e8f0;">
                            <span style="font-size: 20px; font-weight: 800; color: #1e3a8a; display: block;">
                                Rp {{ number_format((float) ($product->selling_price ?: $product->price), 0, ',', '.') }}
                            </span>
                            <div style="color: #166534; font-size: 12px; font-weight: 600; margin-top: 4px;">
                                ✓ Sisa Stok: {{ $product->stock }} unit
                            </div>
                        </div>

                        <a href="{{ route('customer.order') }}" class="btn" style="display: block; text-align: center; width: 100%; box-sizing: border-box; padding: 10px; border-radius: 6px; font-weight: bold;">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; background: #fff; padding: 40px; text-align: center; color: #64748b; border-radius: 8px;">
                Saat ini belum ada produk dengan stok tersedia di katalog.
            </div>
        @endforelse
    </div>

    <div style="margin-top: 30px;">
        {{ $products->links() }}
    </div>

    <!-- Pesanan Terbaru Saya -->
    @if($recentOrders->count() > 0)
        <div style="margin-top: 45px;">
            <h2>Pesanan Terakhir Saya</h2>
            <div style="overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <table border="1" cellpadding="10" cellspacing="0" width="100%">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Nomor Nota</th>
                            <th>Tanggal</th>
                            <th>Total Belanja</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->sale_number }}</strong></td>
                                <td>{{ $order->sold_at?->format('d/m/Y H:i') }}</td>
                                <td>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($order->status === 'completed')
                                        <span style="background: #dcfce7; color: #166534; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Selesai</span>
                                    @else
                                        <span style="background: #fee2e2; color: #991b1b; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">Dibatalkan</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-secondary" style="padding: 4px 10px; font-size: 12px;">
                                        Lihat Nota
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</main>
@endsection
