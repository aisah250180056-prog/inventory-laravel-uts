@extends('layouts.app')

@section('title', 'Pesan Produk - Portal Pelanggan')

@section('content')
<main class="container" style="max-width: 850px;">
    <h1>Buat Pesanan Pembelian Barang</h1>
    <p style="color: #64748b; margin-top: -15px; margin-bottom: 25px;">
        Pilih barang yang ingin Anda beli. Stok barang akan otomatis dikurangi setelah pesanan berhasil diselesaikan.
    </p>

    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('customer.order.store') }}" method="POST">
            @csrf

            <table border="1" cellpadding="10" cellspacing="0" width="100%" id="customer-order-table">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th width="45%">Produk</th>
                        <th width="15%">Jumlah (Qty)</th>
                        <th width="20%">Harga Satuan</th>
                        <th width="20%">Subtotal</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody id="order-items-body">
                    <tr>
                        <td>
                            <select name="items[0][product_id]" class="product-select" required onchange="handleProductChange(this, 0)" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ (float) ($product->selling_price ?: $product->price) }}"
                                        data-stock="{{ $product->stock }}"
                                    >
                                        {{ $product->name }} (Tersedia: {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input
                                type="number"
                                name="items[0][quantity]"
                                class="qty-input"
                                min="1"
                                value="1"
                                required
                                oninput="calculateRow(0)"
                                style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;"
                            >
                        </td>
                        <td>
                            <span id="price-display-0" style="color: #64748b;">Rp 0</span>
                        </td>
                        <td>
                            <strong id="subtotal-display-0">Rp 0</strong>
                        </td>
                        <td style="text-align: center;">
                            <button type="button" onclick="removeRow(this)" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 4px; cursor: pointer;">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="background: #f1f5f9; font-size: 16px;">
                        <th colspan="3" style="text-align: right; padding: 12px;">Total Pembayaran:</th>
                        <th colspan="2" style="padding: 12px; color: #2563eb;">
                            <span id="grand-total-display">Rp 0</span>
                        </th>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 15px; margin-bottom: 25px;">
                <button type="button" class="btn btn-secondary" onclick="addRow()">+ Tambah Produk Lain</button>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn" style="padding: 10px 24px; font-size: 15px;">
                    Konfirmasi & Bayar Pesanan
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Katalog</a>
            </div>
        </form>
    </div>
</main>

<script>
    let rowIndex = 1;
    const productsData = @json($productsJson);

    function handleProductChange(selectElem, idx) {
        const selectedOption = selectElem.options[selectElem.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

        const priceDisplay = document.getElementById(`price-display-${idx}`);
        if (priceDisplay) {
            priceDisplay.textContent = 'Rp ' + price.toLocaleString('id-ID');
        }

        const qtyInput = selectElem.closest('tr').querySelector('.qty-input');
        if (qtyInput) {
            qtyInput.max = stock;
        }

        calculateRow(idx);
    }

    function calculateRow(idx) {
        const row = document.getElementById(`price-display-${idx}`)?.closest('tr');
        if (!row) return;

        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const subtotalDisplay = document.getElementById(`subtotal-display-${idx}`);

        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption?.getAttribute('data-price')) || 0;
        const qty = parseInt(qtyInput?.value) || 0;

        const subtotal = price * qty;
        if (subtotalDisplay) {
            subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        }

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        const rows = document.querySelectorAll('#order-items-body tr');
        rows.forEach(row => {
            const select = row.querySelector('.product-select');
            const qtyInput = row.querySelector('.qty-input');
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption?.getAttribute('data-price')) || 0;
            const qty = parseInt(qtyInput?.value) || 0;
            grandTotal += (price * qty);
        });

        document.getElementById('grand-total-display').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    function addRow() {
        const tbody = document.getElementById('order-items-body');
        const tr = document.createElement('tr');

        let optionsHtml = '<option value="">-- Pilih Produk --</option>';
        productsData.forEach(p => {
            optionsHtml += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">
                ${p.name} (Tersedia: ${p.stock})
            </option>`;
        });

        tr.innerHTML = `
            <td>
                <select name="items[${rowIndex}][product_id]" class="product-select" required onchange="handleProductChange(this, ${rowIndex})" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;">
                    ${optionsHtml}
                </select>
            </td>
            <td>
                <input
                    type="number"
                    name="items[${rowIndex}][quantity]"
                    class="qty-input"
                    min="1"
                    value="1"
                    required
                    oninput="calculateRow(${rowIndex})"
                    style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px;"
                >
            </td>
            <td>
                <span id="price-display-${rowIndex}" style="color: #64748b;">Rp 0</span>
            </td>
            <td>
                <strong id="subtotal-display-${rowIndex}">Rp 0</strong>
            </td>
            <td style="text-align: center;">
                <button type="button" onclick="removeRow(this)" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 4px 10px; border-radius: 4px; cursor: pointer;">
                    Hapus
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        rowIndex++;
    }

    function removeRow(btn) {
        const tbody = document.getElementById('order-items-body');
        if (tbody.rows.length > 1) {
            btn.closest('tr').remove();
            calculateGrandTotal();
        } else {
            alert('Minimal harus ada 1 barang dalam pesanan.');
        }
    }
</script>
@endsection
