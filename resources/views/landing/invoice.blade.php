@extends('landing.app')

@section('title', 'Invoice ' . $pembayaran->kode_invoice . ' - Hijabkku')

@section('styles')
    <style>
        .invoice-container {
            max-width: 720px;
            margin: 0 auto;
            background: var(--bg-card);
            border: 1px solid var(--border);
        }

        .invoice-header {
            padding: 40px 48px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .invoice-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .invoice-logo img {
            height: 28px;
        }

        .invoice-logo-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 2px;
            color: var(--text);
            text-transform: uppercase;
        }

        .invoice-badge {
            display: inline-block;
            padding: 6px 16px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: #d4edda;
            color: #155724;
            border-radius: 4px;
        }

        .invoice-body {
            padding: 36px 48px;
        }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 36px;
        }

        .invoice-meta-block label {
            display: block;
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .invoice-meta-block span {
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 500;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }

        .invoice-table th {
            font-family: 'Inter', sans-serif;
            font-size: 0.68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .invoice-table th:last-child,
        .invoice-table td:last-child {
            text-align: right;
        }

        .invoice-table td {
            font-size: 0.88rem;
            color: var(--text);
            padding: 14px 0;
            border-bottom: 1px solid var(--border-light);
            vertical-align: top;
        }

        .invoice-table .item-name {
            font-weight: 500;
        }

        .invoice-table .item-qty {
            color: var(--text-sub);
            font-size: 0.82rem;
        }

        .invoice-totals {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 36px;
        }

        .invoice-totals-box {
            width: 280px;
        }

        .invoice-totals-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 0.85rem;
            color: var(--text-sub);
        }

        .invoice-totals-row.grand-total {
            border-top: 1px solid var(--border);
            margin-top: 8px;
            padding-top: 14px;
            font-family: 'DM Serif Display', serif;
            font-size: 1.15rem;
            color: var(--text);
            font-weight: 400;
        }

        .invoice-pickup {
            background: var(--bg-warm);
            border: 1px solid var(--border);
            padding: 24px;
            margin-bottom: 32px;
        }

        .invoice-pickup h5 {
            font-size: 0.85rem;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 12px;
        }

        .invoice-pickup p {
            font-size: 0.82rem;
            color: var(--text-sub);
            margin-bottom: 6px;
        }

        .invoice-pickup .status-badge {
            display: inline-block;
            padding: 4px 12px;
            font-size: 0.72rem;
            font-weight: 600;
            border-radius: 4px;
            margin-top: 8px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .invoice-footer {
            padding: 28px 48px;
            border-top: 1px solid var(--border);
            text-align: center;
        }

        .invoice-footer p {
            font-size: 0.78rem;
            color: var(--text-light);
            margin-bottom: 6px;
        }

        .invoice-footer .note {
            font-size: 0.82rem;
            color: var(--text-sub);
            font-weight: 500;
        }

        .invoice-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }

        @media print {

            .nav-main,
            .footer,
            .invoice-actions,
            .back-to-shop {
                display: none !important;
            }

            .invoice-container {
                border: none;
                box-shadow: none;
                margin: 0;
            }

            body {
                background: white;
            }
        }

        @media (max-width: 767.98px) {

            .invoice-header,
            .invoice-body,
            .invoice-footer {
                padding-left: 24px;
                padding-right: 24px;
            }

            .invoice-meta {
                flex-direction: column;
                gap: 16px;
            }
        }
    </style>
@endsection

@section('content')
    <section style="padding: 80px 0 60px; background: var(--bg); min-height: 80vh;">
        <div class="container">
            <div class="invoice-container" style="box-shadow: 0 4px 24px rgba(0,0,0,0.04);">

                {{-- Header --}}
                <div class="invoice-header">
                    <div class="invoice-logo">
                        <img src="/img/speerlogo.png" alt="Hijabkku">
                        <span class="invoice-logo-text">Hijabkku</span>
                    </div>
                    <div class="invoice-badge">Lunas</div>
                </div>

                {{-- Body --}}
                <div class="invoice-body">

                    {{-- Invoice Meta --}}
                    <div class="invoice-meta">
                        <div class="invoice-meta-block">
                            <label>Invoice</label>
                            <span>{{ $pembayaran->kode_invoice }}</span>
                        </div>
                        <div class="invoice-meta-block">
                            <label>Tanggal</label>
                            <span>{{ $pembayaran->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                        <div class="invoice-meta-block">
                            <label>Metode Pembayaran</label>
                            <span>{{ $paymentMethodLabel }}</span>
                        </div>
                    </div>

                    {{-- Customer & Store Info --}}
                    <div class="row mb-4" style="margin-bottom: 32px;">
                        <div class="col-6">
                            <div
                                style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-light); margin-bottom: 10px;">
                                Informasi Pelanggan</div>
                            <p style="font-size: 0.88rem; color: var(--text); margin-bottom: 4px; font-weight: 500;">
                                {{ $pesananPickup?->customer_name ?? '-' }}</p>
                            <p style="font-size: 0.82rem; color: var(--text-sub); margin-bottom: 2px;">
                                {{ $pesananPickup?->customer_phone ?? '-' }}</p>
                            @if ($pesananPickup?->customer_email)
                                <p style="font-size: 0.82rem; color: var(--text-sub);">{{ $pesananPickup->customer_email }}
                                </p>
                            @endif
                        </div>
                        <div class="col-6">
                            <div
                                style="font-size: 0.68rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-light); margin-bottom: 10px;">
                                Cabang Pengambilan</div>
                            <p style="font-size: 0.88rem; color: var(--text); margin-bottom: 4px; font-weight: 500;">
                                {{ $toko?->nama_toko ?? '-' }}</p>
                            @if ($toko?->alamat_toko)
                                <p style="font-size: 0.82rem; color: var(--text-sub);">{{ $toko->alamat_toko }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th style="text-align: center;">Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $item)
                                <tr>
                                    <td class="item-name">{{ $item->nama_barang }}</td>
                                    <td class="item-qty" style="text-align: center;">{{ $item->jumlah }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->harga_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Totals --}}
                    <div class="invoice-totals">
                        <div class="invoice-totals-box">
                            <div class="invoice-totals-row">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($pembayaran->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="invoice-totals-row">
                                <span>Biaya ({{ $paymentMethodLabel }})</span>
                                <span>Rp {{ number_format($fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="invoice-totals-row grand-total">
                                <span>Total Dibayar</span>
                                <span>Rp {{ number_format($pembayaran->pembayaran, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pickup Info --}}
                    @if ($pesananPickup)
                        <div class="invoice-pickup">
                            <h5>Informasi Pengambilan</h5>
                            <p>Tunjukkan invoice ini kepada petugas saat pengambilan pesanan di cabang toko.</p>
                            <p>Status: <span
                                    class="status-badge status-pending">{{ $pesananPickup->status_pengambilan }}</span></p>
                        </div>
                    @endif

                </div>

                {{-- Footer --}}
                <div class="invoice-footer">
                    <p class="note">Terima kasih telah berbelanja di Hijabkku.</p>
                    <p>Pertanyaan? Hubungi kami melalui WhatsApp atau email.</p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="invoice-actions">
                <button onclick="window.print()" class="btn-primary"
                    style="padding: 12px 32px; font-size: 0.85rem; letter-spacing: 0.5px;">
                    Cetak Invoice
                </button>
                <a href="{{ route('catalog') }}" class="back-to-shop"
                    style="padding: 12px 32px; font-size: 0.85rem; letter-spacing: 0.5px; border: 1px solid var(--border); background: var(--bg-card); color: var(--text); text-decoration: none; display: inline-block; transition: all 0.2s ease;">
                    Kembali ke Katalog
                </a>
            </div>

        </div>
    </section>
@endsection
