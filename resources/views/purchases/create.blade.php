@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
<div class="container">
    <h1>Tambah Transaksi Pembelian</h1>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf

        <div>
            <label for="supplier_id">Pemasok</label>
            <select name="supplier_id" id="supplier_id" required>
                <option value="">-- Pilih Pemasok --</option>
                @foreach ($suppliers as $supplier)
                    <option
                        value="{{ $supplier->id }}"
                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                    >
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="purchased_at">Tanggal Pembelian</label>
            <input
                type="datetime-local"
                name="purchased_at"
                id="purchased_at"
                value="{{ old('purchased_at', now()->format('Y-m-d\TH:i')) }}"
            >
        </div>

        <h3>Daftar Barang</h3>

        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Beli Satuan (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="purchase-items">
                <tr>
                    <td>
                        <select name="items[0][product_id]" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input
                            type="number"
                            name="items[0][quantity]"
                            min="1"
                            step="1"
                            value="1"
                            required
                        >
                    </td>
                    <td>
                        <input
                            type="number"
                            name="items[0][unit_cost]"
                            min="0.01"
                            step="0.01"
                            required
                        >
                    </td>
                    <td>
                        <button type="button" onclick="removeRow(this)">Hapus</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <button type="button" onclick="addRow()">+ Tambah Barang</button>
        </p>

        <p>
            Total akhir akan dihitung di server berdasarkan jumlah dan harga
            setiap barang.
        </p>

        <button type="submit">Simpan Pembelian</button>
        <a href="{{ route('purchases.index') }}">Batal</a>
    </form>
</div>

<script>
    let itemIndex = 1;

    function addRow() {
        const tbody = document.getElementById('purchase-items');
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <select name="items[${itemIndex}][product_id]" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} (stok: {{ $product->stock }})
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][quantity]"
                    min="1" step="1" value="1" required>
            </td>
            <td>
                <input type="number" name="items[${itemIndex}][unit_cost]"
                    min="0.01" step="0.01" required>
            </td>
            <td>
                <button type="button" onclick="removeRow(this)">Hapus</button>
            </td>
        `;

        tbody.appendChild(row);
        itemIndex++;
    }

    function removeRow(button) {
        const tbody = document.getElementById('purchase-items');

        if (tbody.rows.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('Minimal harus ada satu barang.');
        }
    }
</script>
@endsection
