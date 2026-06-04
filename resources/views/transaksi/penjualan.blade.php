@extends('layouts.main')
@section('main')
    <style>
        /* ── POS Layout ── */
        .pos-container {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .pos-left {
            flex: 1;
            min-width: 0;
        }

        .pos-right {
            width: 380px;
            position: sticky;
            top: 100px;
            background: var(--foreground);
            border-radius: 1rem;
            border: 1px solid var(--separator);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 130px);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        /* ── Tablet Optimization (Medium Screens) ── */
        @media (min-width: 768px) and (max-width: 1199.98px) {
            .pos-right {
                width: 310px;
            }

            .cart-item-row {
                padding: 0.65rem;
            }

            .cart-item-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.35rem;
            }

            .cart-item-meta>div {
                width: 100%;
                justify-content: space-between !important;
            }
        }

        /* ── Phone Optimization (Small Screens) ── */
        @media (max-width: 767.98px) {
            .pos-container {
                flex-direction: column-reverse;
            }

            .pos-right {
                width: 100%;
                position: static;
                max-height: none;
                margin-bottom: 1.5rem;
            }
        }

        /* ── Product Grid & Cards ── */
        .product-grid-scroll {
            max-height: calc(100vh - 280px);
            overflow-y: auto;
            padding-right: 5px;
        }

        @media (max-width: 767.98px) {
            .product-grid-scroll {
                max-height: none;
                overflow-y: visible;
            }
        }

        .product-card {
            background: var(--foreground);
            border: 1px solid var(--separator);
            border-radius: 0.85rem;
            transition: all 0.22s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }

        .product-card .btn-transparent {
            width: 100%;
            text-align: left;
            padding: 1rem;
            background: transparent;
            border: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        .product-card .nama-barang {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--body);
            line-height: 1.4;
            margin-bottom: 0.5rem;
            display: block;
            overflow: visible;
            height: auto;
            white-space: normal;
        }

        .product-card .price-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            margin-bottom: 0.75rem;
        }

        .price-badge {
            font-size: 0.72rem;
            padding: 0.2rem 0.45rem;
            border-radius: 0.35rem;
            font-weight: 500;
        }

        .price-badge.eceran {
            background: rgba(var(--primary-rgb), 0.08);
            color: var(--primary);
        }

        .price-badge.grosir {
            background: rgba(var(--secondary-rgb), 0.08);
            color: var(--secondary);
        }

        .product-card .stock-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
            font-weight: 600;
            border-top: 1px solid var(--separator);
            padding-top: 0.5rem;
            margin-top: auto;
            width: 100%;
        }

        .stock-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .stock-ok {
            color: var(--success);
        }

        .stock-ok .stock-indicator {
            background-color: var(--success);
        }

        .stock-low {
            color: var(--warning);
        }

        .stock-low .stock-indicator {
            background-color: var(--warning);
        }

        .stock-empty {
            opacity: 0.4;
            pointer-events: none;
        }

        .stock-empty .stock-indicator {
            background-color: var(--danger);
        }

        /* ── Right Panel Checkout ── */
        .cart-header {
            padding: 1.25rem;
            border-bottom: 1px solid var(--separator);
            background: rgba(var(--primary-rgb), 0.02);
        }

        .cart-items-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            min-height: 180px;
        }

        .cart-item-row {
            background: var(--background);
            border: 1px solid var(--separator);
            border-left: 3px solid var(--primary);
            border-radius: 0.65rem;
            padding: 0.85rem;
            margin-bottom: 0.75rem;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }

        .cart-item-row:hover {
            border-color: var(--primary);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
            transform: scale(1.01);
        }

        .cart-item-title {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--alternate);
            margin-bottom: 0.5rem;
            padding-right: 20px;
        }

        .cart-item-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .cart-item-price {
            font-weight: 800;
            color: var(--primary);
            font-size: 0.92rem;
        }

        .cart-footer {
            padding: 1.25rem;
            border-top: 1px solid var(--separator);
            background: var(--foreground);
        }

        /* ── Modern Form Control Inputs ── */
        .pos-search-wrapper {
            position: relative !important;
            width: 100% !important;
            display: block !important;
        }

        .pos-search-wrapper input.form-control {
            border-radius: 0.75rem !important;
            font-size: 0.9rem !important;
            padding: 0.75rem 1.25rem 0.75rem 2.75rem !important;
            background: var(--foreground) !important;
            border: 1px solid var(--separator) !important;
            transition: all 0.2s ease !important;
            height: 48px !important;
            box-shadow: none !important;
            width: 100% !important;
        }

        .pos-search-wrapper input.form-control:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.12) !important;
            background: var(--foreground) !important;
        }

        .pos-search-wrapper .search-magnifier-icon {
            position: absolute !important;
            left: 1rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: var(--muted) !important;
            pointer-events: none !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            z-index: 10 !important;
        }

        .payment-input {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            text-align: right;
            letter-spacing: 0.05em;
        }

        .change-input {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            text-align: right;
            background-color: rgba(var(--success-rgb), 0.05) !important;
            color: var(--success) !important;
        }

        .change-input.kurang {
            background-color: rgba(var(--danger-rgb), 0.05) !important;
            color: var(--danger) !important;
        }

        /* ── Info Bar ── */
        .pos-info-bar {
            background: var(--foreground);
            border-radius: 1rem;
            border: 1px solid var(--separator);
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .pos-info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pos-info-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(var(--primary-rgb), 0.08);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pos-info-label {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
        }

        .pos-info-val {
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--body);
        }
    </style>

    <main class="py-4">
        <div class="container-fluid px-lg-4">
            <!-- Header -->
            <div class="page-title-container mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <h1 class="mb-0 pb-0 display-4 fw-bold text-alternate" id="title">Point of Sale (POS)</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Penjualan</li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Right side clock or status -->
                    <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                        <span class="badge bg-outline-primary py-2 px-3 fs-7" id="posClock"></span>
                    </div>
                </div>
            </div>

            <!-- Top Info Bar -->
            <div class="pos-info-bar mb-4">
                <div class="row g-4 align-items-center">
                    <div class="col-6 col-md-4">
                        <div class="pos-info-item">
                            <div class="pos-info-icon"><i data-acorn-icon="shop" data-acorn-size="18"></i></div>
                            <div>
                                <div class="pos-info-label">Toko / Cabang</div>
                                @if(Auth::user()->role === 'admin')
                                    <select class="form-select form-select-sm mt-1" id="switchToko" style="min-width: 150px; font-weight: 700; color: var(--body);">
                                        @foreach($all_tokos as $t)
                                            <option value="{{ $t->kode }}" {{ $data_toko->kode == $t->kode ? 'selected' : '' }}>
                                                {{ $t->nama_toko }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <div class="pos-info-val">{{ $data_toko->nama_toko }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="pos-info-item">
                            <div class="pos-info-icon"><i data-acorn-icon="user" data-acorn-size="18"></i></div>
                            <div>
                                <div class="pos-info-label">Petugas Kasir</div>
                                <div class="pos-info-val">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <span class="text-muted d-block small mb-1">Kode Invoice</span>
                        <h4 class="fw-bold text-primary mb-0" id="invoiceLabel">GENERATING...</h4>
                    </div>
                </div>
            </div>

            <!-- Main Layout Split Screen -->
            <div class="pos-container">
                <!-- Left Side: Product Catalogue -->
                <div class="pos-left">
                    <!-- Search Controls -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="pos-search-wrapper">
                                <input class="form-control" placeholder="Cari berdasarkan Jenis, Merek, atau Bahan..."
                                    id="cariProduk" name="cariProduk">
                                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="pos-search-wrapper">
                                <input class="form-control" placeholder="Cari berdasarkan Model, Variasi, atau Packaging..."
                                    id="cariProdukOptional" name="cariProdukOptional">
                                <span class="search-magnifier-icon"><i data-acorn-icon="search"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid Scrollable Container -->
                    <div class="product-grid-scroll">
                        <div
                            class="stock row row-cols-2 row-cols-sm-3 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 g-3 p-0 m-0">
                            <!-- Pre-rendered by AJAX -->
                        </div>
                    </div>
                </div>

                <!-- Right Side: Sticky Checkout Panel -->
                <div class="pos-right">
                    <!-- Cart Header -->
                    <div class="cart-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i data-acorn-icon="basket" class="text-primary"></i>
                            <h5 class="fw-bold text-alternate mb-0">Keranjang</h5>
                        </div>
                        <span class="badge bg-primary rounded-pill px-3 py-1 fs-8" id="cartCount">0 Item</span>
                    </div>

                    <!-- Cart Item Rows Wrapper -->
                    <div class="cart-items-wrapper">
                        <!-- Table structure kept inside to preserve standard JS selectors -->
                        <table class="transaksi w-100 d-none">
                            <thead>
                                <tr class="codeTransaksi-row"></tr>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jml</th>
                                    <th>Metode</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!-- Gorgeous visual flex container mapped to old table rows -->
                        <div id="cartVisualList">
                            <div class="text-center py-5 text-muted" id="emptyCartMessage">
                                <i data-acorn-icon="shopping-bag" data-acorn-size="48" class="mb-3 opacity-30"></i>
                                <p class="mb-0">Keranjang masih kosong.<br>Silakan pilih produk di sebelah kiri.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary & Payments Footer -->
                    <div class="cart-footer">
                        <!-- Total Price Display -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-semibold">Total Belanja</span>
                            <div class="totals">
                                <table>
                                    <tbody></tbody>
                                </table>
                                <h3 class="fw-bold text-alternate mb-0" id="totalBayarDisplay">Rp 0</h3>
                            </div>
                        </div>

                        <!-- Real POS Checkout Form -->
                        <form id="formValid" class="tooltip-label-end" novalidate>
                            <div class="mb-2">
                                <label class="form-label small text-muted mb-1">Metode Pembayaran</label>
                                <select class="form-select form-select-sm" id="metodePembayaran" required>
                                    <option value="TUNAI" selected>Tunai / Cash</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="VA">Virtual Account (VA)</option>
                                    <option value="EWALLET">E-Wallet (OVO, DANA, dll)</option>
                                </select>
                            </div>
                            <div id="tunaiInputContainer">
                                <div class="mb-2">
                                    <label class="form-label small text-muted mb-1">Jumlah Uang Diterima</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                        <input id="jumlahUang" type="text" class="form-control payment-input"
                                            placeholder="0" required>
                                        <button class="btn btn-outline-secondary fw-semibold" type="button"
                                            id="button_pas">Pas</button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted mb-1">Uang Kembalian</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                                        <input id="kembalian" type="text" class="form-control change-input"
                                            placeholder="0" readonly>
                                    </div>
                                </div>
                            </div>
                            <div id="digitalPaymentInfo" class="d-none mb-3 p-2 bg-light rounded border border-info">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Subtotal:</span>
                                    <span class="small fw-bold" id="digitalSubtotal">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">Fee+Tax:</span>
                                    <span class="small fw-bold text-danger" id="digitalFee">Rp 0</span>
                                </div>
                                <hr class="my-1 border-secondary">
                                <div class="d-flex justify-content-between">
                                    <span class="small text-muted fw-bold">Total Tagihan:</span>
                                    <span class="small fw-bold text-primary" id="digitalGrandTotal"
                                        style="font-size:1.1em;">Rp 0</span>
                                </div>
                            </div>
                        </form>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button type="button" class="simpan btn btn-primary py-2 fw-bold fs-6 shadow-sm">
                                <i data-acorn-icon="check" class="me-1"></i> PROSES BAYAR
                            </button>
                            <button type="button" class="closed btn btn-outline-muted btn-sm py-1 fw-semibold">
                                <i data-acorn-icon="rotate-left" class="me-1"></i> Reset Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Clock Real-time POS
            function updateClock() {
                const now = new Date();
                const options = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                };
                $('#posClock').text(now.toLocaleDateString('id-ID', options));
            }
            setInterval(updateClock, 1000);
            updateClock();


            // Generate invoice ID
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            function genStr(len) {
                return Array.from({
                    length: len
                }, () => chars[Math.floor(Math.random() * chars.length)]).join('').toLowerCase();
            }
            const currentInvoice = 'TRHJ_' + genStr(4) + new Date().getFullYear();
            $('#invoiceLabel').text(currentInvoice);

            function ajaxQuery(method, url, data) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        dataStock(response);
                        if (method == 'post') {
                            simpan(response);
                        } else {
                            loadCartFromStorage();
                        }
                    }
                });
            }
            window.ajaxQuery = ajaxQuery;

            function simpan(res) {
                if (res.icon === 'success') {
                    localStorage.removeItem('hijabkku_pos_cart');
                }
                Swal.fire({
                    position: 'center',
                    icon: res.icon,
                    title: res.cek_data,
                    showConfirmButton: true,
                    timer: 2500
                }).then(() => location.reload());
            }

            function rupiah(param) {
                if (param === undefined || param === null) return '0';
                var str = param.toString().trim();

                // If it ends with .00 (laravel decimal), strip it
                if (str.endsWith('.00')) {
                    str = str.slice(0, -3);
                }

                // If it already has thousands separators, just return it
                if (str.includes('.') && !str.includes('.00')) {
                    return str;
                }

                // Otherwise format it
                var clean = str.replace(/\D/g, '');
                if (clean === '') return '0';

                var sisa = clean.length % 3,
                    r = clean.substr(0, sisa),
                    ribuan = clean.substr(sisa).match(/\d{3}/g);
                if (ribuan) {
                    r += (sisa ? '.' : '') + ribuan.join('.');
                }
                return r;
            }

            function saveCartToStorage() {
                var cartItems = [];
                $('.transaksi tbody tr').each(function() {
                    var kode_barang = this.id.replaceAll('transaksi_', '');
                    var nama_barang = $(this).find('.namaBarangLabel').text();
                    var qty = parseInt($(this).find('.counter').text()) || 0;
                    var method = $(this).find('.metode').text();
                    var harga_jual = $(this).find('.hargaJual').text().replaceAll('.', '');
                    var total_jual = $(this).find('.totalJual').text().replaceAll('.', '');

                    var visualRow = $('#visual_transaksi_' + kode_barang);
                    var priceRetail = (visualRow.attr('data-harga_jual') || harga_jual).toString().replaceAll('.', '');
                    var priceGrosir = (visualRow.attr('data-harga_grosir') || harga_jual).toString().replaceAll('.', '');

                    cartItems.push({
                        kode_barang: kode_barang,
                        nama_barang: nama_barang,
                        qty: qty,
                        method: method,
                        harga_jual: harga_jual,
                        total_jual: total_jual,
                        priceRetail: priceRetail,
                        priceGrosir: priceGrosir
                    });
                });
                localStorage.setItem('hijabkku_pos_cart', JSON.stringify(cartItems));
            }

            function loadCartFromStorage() {
                var stored = localStorage.getItem('hijabkku_pos_cart');
                if (!stored) {
                    recalculatePosTotal();
                    return;
                }

                var cartItems = [];
                try {
                    cartItems = JSON.parse(stored);
                } catch (e) {
                    console.error("Failed to parse cart storage", e);
                }

                if (!Array.isArray(cartItems) || cartItems.length === 0) {
                    recalculatePosTotal();
                    return;
                }

                $('.transaksi tbody').html('');
                $('#cartVisualList').html('');

                $.each(cartItems, function(idx, item) {
                    var kode_barang = item.kode_barang;
                    var nama_barang = item.nama_barang;
                    var qty = item.qty;
                    var method = item.method || 'umum';
                    var harga_jual_retail = parseInt((item.priceRetail || '0').toString().replaceAll('.', '')) || 0;
                    var harga_grosir = parseInt((item.priceGrosir || '0').toString().replaceAll('.', '')) || 0;

                    var unitPrice = method === 'grosir' ? harga_grosir : harga_jual_retail;
                    var totalJual = unitPrice * qty;

                    var productBtn = $('button.selected[data-kode_barang="' + kode_barang + '"]');
                    if (productBtn.length) {
                        var el = productBtn.find('.sisaStock');
                        var originalStock = parseInt(el.attr('data-jumlah')) || 0;
                        var newSisa = originalStock - qty;

                        el.attr('data-jumlah', newSisa).text(getStockText(newSisa));
                        productBtn.closest('.product-card').removeClass('stock-ok stock-low stock-empty')
                            .addClass(stockClass(newSisa));
                        if (newSisa <= 0) {
                            productBtn.prop('disabled', true).css({
                                'opacity': '0.5',
                                'pointer-events': 'none'
                            });
                        }
                    }

                    $('.transaksi tbody').append(`
                    <tr id="transaksi_${kode_barang}">
                        <td class="col-4 namaBarangLabel">${nama_barang}</td>
                        <td class="counter col-1 text-center">${qty}</td>
                        <td class="metode col-2">${method}</td>
                        <td class="hargaJual col-2">${unitPrice}</td>
                        <td class="totalJual col-2">${totalJual}</td>
                        <td class="col-1">
                            <button type="button" data-kode_barang="${kode_barang}" class="kurangi"></button>
                        </td>
                    </tr>`);

                    $('#cartVisualList').append(`
                    <div class="cart-item-row" id="visual_transaksi_${kode_barang}"
                         data-harga_jual="${harga_jual_retail}"
                         data-harga_grosir="${harga_grosir}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="cart-item-title me-2">${nama_barang}</div>
                            <button type="button" data-kode_barang="${kode_barang}"
                                    class="hapus-item btn btn-link text-danger p-0 border-0"
                                    style="line-height:1; font-size:1.2rem; position: absolute; top: 8px; right: 8px;"
                                    title="Hapus dari keranjang">
                                <i data-acorn-icon="close" data-acorn-size="14"></i>
                            </button>
                        </div>
                        <div class="cart-item-meta">
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex align-items-center border rounded-md bg-light overflow-hidden" style="height: 24px; padding: 0 2px;">
                                    <button type="button" data-kode_barang="${kode_barang}" class="kurangi btn btn-link text-body p-0 px-2 border-0 fw-bold" style="font-size:0.9rem; text-decoration:none; line-height:1;">−</button>
                                    <span class="visual-counter fw-bold px-1 text-primary" style="font-size:0.8rem; min-width:18px; text-align:center;">${qty}</span>
                                    <button type="button" data-kode_barang="${kode_barang}" class="tambah-qty btn btn-link text-body p-0 px-2 border-0 fw-bold" style="font-size:0.9rem; text-decoration:none; line-height:1;">+</button>
                                </div>
                                <span class="text-muted fs-8">x Rp <span class="visual-unit-price">${rupiah(unitPrice)}</span></span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select form-select-xs py-0 px-1 border-separator change-item-method" data-kode_barang="${kode_barang}" style="font-size:0.68rem; height:22px; width:75px; cursor:pointer; border-radius:0.35rem;">
                                    <option value="umum" ${method === 'umum' ? 'selected' : ''}>Umum</option>
                                    <option value="grosir" ${method === 'grosir' ? 'selected' : ''}>Grosir</option>
                                </select>
                                <span class="cart-item-price visual-total">Rp ${rupiah(totalJual)}</span>
                            </div>
                        </div>
                    </div>`);
                });

                if (typeof AcornIcons !== 'undefined') {
                    new AcornIcons().replace();
                }

                recalculatePosTotal();
            }

            var globHideStock = false;

            function getStockText(sisa) {
                if (sisa <= 0) return 'Habis';
                return globHideStock ? 'Tersedia' : 'Stok: ' + sisa;
            }

            function stockClass(jumlah) {
                if (globHideStock) {
                    if (jumlah <= 0) return 'stock-empty';
                    return 'stock-ok';
                }
                if (jumlah <= 0) return 'stock-empty';
                return jumlah <= 3 ? 'stock-low' : 'stock-ok';
            }

            function dataStock(params) {
                $('.stock').html('');
                globHideStock = params.hide_stock || false;
                $.each(params.stock, function(index, value) {
                    var sisa = value.jumlah - value.terjual;
                    var cls = stockClass(sisa);
                    var stateAttr = sisa <= 0 ? 'disabled style="opacity: 0.5; pointer-events: none;"' : '';

                    $('.stock').append(`
                    <div class="col">
                        <div class="card product-card h-100 ${sisa <= 0 ? 'stock-empty' : ''}">
                            <button id="${value.id}" class="selected btn-transparent" ${stateAttr}
                                data-nama_barang="${value.nama_barang}"
                                data-kode_barang="${value.kode_barang}"
                                data-kode_toko="${value.kode_toko}"
                                data-harga_grosir="${value.harga_grosir}"
                                data-harga_jual="${value.harga_jual}">
                                <div class="nama-barang" title="${value.nama_barang}">${value.nama_barang}</div>
                                <div class="price-badges">
                                    <span class="price-badge eceran">E: Rp ${rupiah(value.harga_jual)}</span>
                                    <span class="price-badge grosir">G: Rp ${rupiah(value.harga_grosir)}</span>
                                </div>
                                <div class="stock-bar ${cls}">
                                    <div class="sisaStock" data-jumlah="${sisa}">
                                        ${getStockText(sisa)}
                                    </div>
                                    <span class="stock-indicator"></span>
                                </div>
                            </button>
                        </div>
                    </div>`);
                });
            }

            ajaxQuery('get', '/transaksi/penjualan/create', {
                param: 'all',
                kode_toko: '{{ $data_toko->kode }}'
            });

            $('input[name=cariProduk], input[name=cariProdukOptional]').on('keyup', function() {
                ajaxQuery('get', '/transaksi/penjualan/create', {
                    key1: $('input[name=cariProduk]').val(),
                    key2: $('input[name=cariProdukOptional]').val(),
                    kode_toko: '{{ $data_toko->kode }}'
                });
            });

            $('#switchToko').on('change', function() {
                var selectedToko = $(this).val();
                localStorage.removeItem('hijabkku_pos_cart');
                window.location.href = '/transaksi/penjualan?kode_toko=' + selectedToko;
            });

            var c = 1;

            // Recalculate POS Total
            function recalculatePosTotal() {
                var sum = 0;
                $('.totalJual').each(function() {
                    var combat = $(this).text().replaceAll('.', '');
                    if (!isNaN(combat) && combat.length !== 0) {
                        sum += parseFloat(combat);
                    }
                });

                // Update UI Display
                $('#totalBayarDisplay').text('Rp ' + rupiah(sum));

                // Keep the totalBayar element updated for standard selectors
                if ($('#totalBayar').length <= 0) {
                    $('.totals tbody').html(`
                    <tr id="totalBayar">
                        <td class="totalRupiah d-none">${sum}</td>
                    </tr>`);
                } else {
                    $('.totals .totalRupiah').text(sum);
                }

                // Update Kembalian dynamically
                var val = $('#jumlahUang').val().replaceAll('.', '');
                if (val) {
                    var all = +val - sum;
                    if (all >= 0) {
                        $('#kembalian').val(rupiah(all)).removeClass('kurang');
                    } else {
                        $('#kembalian').val('kurang').addClass('kurang');
                    }
                }

                $('#metodePembayaran').trigger('change');

                // Cart Count update
                var totalItems = 0;
                $('.transaksi tbody tr').each(function() {
                    totalItems += parseInt($(this).find('.counter').text()) || 0;
                });
                $('#cartCount').text(totalItems + ' Item');
                $('.baskeds').text(totalItems > 0 ? totalItems : '');

                if (totalItems > 0) {
                    $('#emptyCartMessage').addClass('d-none');
                } else {
                    $('#emptyCartMessage').removeClass('d-none');
                }
            }

            $(document).on('click', '.selected', function() {
                var id = $(this).attr('id');
                var nama_barang = $(this).attr('data-nama_barang');
                var kode_barang = $(this).attr('data-kode_barang');
                var sisaStock = parseInt($(this).find('.sisaStock').attr('data-jumlah'));
                var exists = $('.transaksi #transaksi_' + kode_barang).length;
                var harga_jual_retail = parseInt(($(this).attr('data-harga_jual') || '0').toString().replaceAll('.', '')) || 0;
                var harga_grosir = parseInt(($(this).attr('data-harga_grosir') || '0').toString().replaceAll('.', '')) || 0;

                var metode = 'umum';
                if (exists >= 1) {
                    metode = $('#visual_transaksi_' + kode_barang + ' .change-item-method').val();
                }

                var harga_jual = metode == 'grosir' ?
                    harga_grosir :
                    harga_jual_retail;

                if (sisaStock > 0) {
                    // Update Stock visual counter
                    var newSisa = sisaStock - 1;
                    $(this).find('.sisaStock')
                        .attr('data-jumlah', newSisa)
                        .text(getStockText(newSisa));

                    $(this).closest('.product-card').removeClass('stock-ok stock-low stock-empty').addClass(
                        stockClass(newSisa));
                    if (newSisa <= 0) {
                        $(this).prop('disabled', true).css({
                            'opacity': '0.5',
                            'pointer-events': 'none'
                        });
                    }

                    if (exists < 1) {
                        // Standard Table Row (invisible but used by backend scripts)
                        $('.transaksi tbody').append(`
                        <tr id="transaksi_${kode_barang}">
                            <td class="col-4 namaBarangLabel">${nama_barang}</td>
                            <td class="counter col-1 text-center">${c}</td>
                            <td class="metode col-2">${metode}</td>
                            <td class="hargaJual col-2">${harga_jual}</td>
                            <td class="totalJual col-2">${harga_jual}</td>
                            <td class="col-1">
                                <button type="button" data-kode_barang="${kode_barang}" class="kurangi"></button>
                            </td>
                        </tr>`);

                        // Dynamic POS Visual Card
                        $('#cartVisualList').append(`
                        <div class="cart-item-row" id="visual_transaksi_${kode_barang}"
                             data-harga_jual="${harga_jual_retail}"
                             data-harga_grosir="${harga_grosir}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="cart-item-title me-2">${nama_barang}</div>
                                <button type="button" data-kode_barang="${kode_barang}"
                                        class="hapus-item btn btn-link text-danger p-0 border-0"
                                        style="line-height:1; font-size:1.2rem; position: absolute; top: 8px; right: 8px;"
                                        title="Hapus dari keranjang">
                                    <i data-acorn-icon="close" data-acorn-size="14"></i>
                                </button>
                            </div>
                            <div class="cart-item-meta">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Compact Qty Controls -->
                                    <div class="d-flex align-items-center border rounded-md bg-light overflow-hidden" style="height: 24px; padding: 0 2px;">
                                        <button type="button" data-kode_barang="${kode_barang}" class="kurangi btn btn-link text-body p-0 px-2 border-0 fw-bold" style="font-size:0.9rem; text-decoration:none; line-height:1;">−</button>
                                        <span class="visual-counter fw-bold px-1 text-primary" style="font-size:0.8rem; min-width:18px; text-align:center;">1</span>
                                        <button type="button" data-kode_barang="${kode_barang}" class="tambah-qty btn btn-link text-body p-0 px-2 border-0 fw-bold" style="font-size:0.9rem; text-decoration:none; line-height:1;">+</button>
                                    </div>
                                    <span class="text-muted fs-8">x Rp <span class="visual-unit-price">${rupiah(harga_jual)}</span></span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <select class="form-select form-select-xs py-0 px-1 border-separator change-item-method" data-kode_barang="${kode_barang}" style="font-size:0.68rem; height:22px; width:75px; cursor:pointer; border-radius:0.35rem;">
                                        <option value="umum" ${metode === 'umum' ? 'selected' : ''}>Umum</option>
                                        <option value="grosir" ${metode === 'grosir' ? 'selected' : ''}>Grosir</option>
                                    </select>
                                    <span class="cart-item-price visual-total">Rp ${rupiah(harga_jual)}</span>
                                </div>
                            </div>
                        </div>`);

                        // Instantly render new icons inside dynamically created elements
                        if (typeof AcornIcons !== 'undefined') {
                            new AcornIcons().replace();
                        }
                    } else {
                        var counter = $('#transaksi_' + kode_barang + ' .counter');
                        var jual = $('#transaksi_' + kode_barang + ' .totalJual');
                        var newCount = parseInt(counter.text()) + c;
                        var newTotal = parseInt(harga_jual.toString().replaceAll('.', '')) + parseInt(jual
                            .text().replaceAll('.', ''));

                        counter.text(newCount);
                        jual.text(newTotal);

                        // Update POS Visual row
                        var visRow = $('#visual_transaksi_' + kode_barang);
                        visRow.find('.visual-counter').text(newCount);
                        visRow.find('.visual-total').text('Rp ' + rupiah(newTotal));
                    }

                    recalculatePosTotal();
                    saveCartToStorage();
                }
            });

            // Handle inline price method change
            $(document).on('change', '.change-item-method', function() {
                var kode_barang = $(this).attr('data-kode_barang');
                var newMethod = $(this).val();

                var visualRow = $('#visual_transaksi_' + kode_barang);
                var priceRetail = parseInt((visualRow.attr('data-harga_jual') || '0').toString().replaceAll('.', ''));
                var priceGrosir = parseInt((visualRow.attr('data-harga_grosir') || '0').toString().replaceAll('.', ''));

                var newUnitPrice = newMethod === 'grosir' ? priceGrosir : priceRetail;
                var count = parseInt(visualRow.find('.visual-counter').text());
                var newTotal = newUnitPrice * count;

                // Update POS Visual row
                visualRow.find('.visual-unit-price').text(rupiah(newUnitPrice));
                visualRow.find('.visual-total').text('Rp ' + rupiah(newTotal));

                // Update standard hidden table row (used by backend)
                var hiddenRow = $('#transaksi_' + kode_barang);
                hiddenRow.find('.metode').text(newMethod);
                hiddenRow.find('.hargaJual').text(newUnitPrice);
                hiddenRow.find('.totalJual').text(newTotal);

                recalculatePosTotal();
                saveCartToStorage();
            });

            // Increment quantity directly inside POS cart
            $(document).on('click', '.tambah-qty', function() {
                var barang = $(this).attr('data-kode_barang');
                $('button.selected[data-kode_barang="' + barang + '"]').trigger('click');
            });

            // Remove whole item from POS cart
            $(document).on('click', '.hapus-item', function() {
                var barang = $(this).attr('data-kode_barang');
                Swal.fire({
                    title: 'Hapus Item?',
                    text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Restore all stock on card
                        var counter = $('#transaksi_' + barang + ' .counter');
                        var jumlahLama = parseInt(counter.text()) || 0;

                        $('button.selected[data-kode_barang="' + barang + '"]').each(function() {
                            var el = $(this).find('.sisaStock');
                            var cur = parseInt(el.attr('data-jumlah')) + jumlahLama;
                            el.attr('data-jumlah', cur).text(getStockText(cur));
                            $(this).prop('disabled', false).css({
                                'opacity': '',
                                'pointer-events': ''
                            });
                            $(this).closest('.product-card').removeClass(
                                'stock-ok stock-low stock-empty').addClass(stockClass(
                                cur));
                        });

                        $('#transaksi_' + barang).remove();
                        $('#visual_transaksi_' + barang).remove();
                        recalculatePosTotal();
                        saveCartToStorage();
                    }
                });
            });

            // Reset / Clear cart
            $('.closed').click(function() {
                Swal.fire({
                    title: 'Reset Keranjang?',
                    text: 'Tindakan ini akan mengosongkan seluruh isi keranjang!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Reset',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        localStorage.removeItem('hijabkku_pos_cart');
                        location.reload();
                    }
                });
            });

            $('#metodePembayaran').change(function() {
                var method = $(this).val();
                var total = parseInt($('.totals .totalRupiah').text()) || 0;

                if (method === 'TUNAI') {
                    $('#tunaiInputContainer').removeClass('d-none');
                    $('#digitalPaymentInfo').addClass('d-none');
                    $('#jumlahUang').prop('required', true);
                } else {
                    $('#tunaiInputContainer').addClass('d-none');
                    $('#digitalPaymentInfo').removeClass('d-none');
                    $('#jumlahUang').prop('required', false);

                    var fee = 0;
                    var grandTotal = total;
                    if (total > 0) {
                        if (method === 'QRIS') {
                            var qrisRate = 0.007; // 0.7% flat
                            grandTotal = Math.ceil(total / (1 - qrisRate));
                            fee = grandTotal - total;
                        } else if (method === 'VA') {
                            var vaFeeFlat = 4500;
                            var ppnRate = 0.12; // 12% PPN
                            fee = vaFeeFlat + (vaFeeFlat * ppnRate);
                            grandTotal = total + fee;
                        } else if (method === 'EWALLET') {
                            var ewalletRate = 0.015; // 1.5%
                            var ppnRateOfFee = 0.11; // 11% PPN on top of fee
                            var effectiveRate = ewalletRate * (1 + ppnRateOfFee); // 1.665%
                            grandTotal = Math.ceil(total / (1 - effectiveRate));
                            fee = grandTotal - total;
                        }
                    }

                    $('#digitalSubtotal').text('Rp ' + rupiah(total));
                    $('#digitalFee').text('Rp ' + rupiah(fee));
                    $('#digitalGrandTotal').text('Rp ' + rupiah(grandTotal));
                }
            });

            // Need to update the digital fee every time cart changes
            function updateDigitalPaymentInfo() {
                if ($('#metodePembayaran').val() !== 'TUNAI') {
                    $('#metodePembayaran').trigger('change');
                }
            }

            $('.simpan').click(function(e) {
                e.preventDefault();
                if (!$('#formValid').valid()) return;

                var method = $('#metodePembayaran').val();
                var trCheck = $('.transaksi tbody tr');
                var invoice = $('#invoiceLabel').text();
                var total_harga = $('.totals .totalRupiah').text();

                var arr = {};
                trCheck.each(function(index) {
                    arr[index] = {
                        nomor_paket: this.id.replaceAll('transaksi_', ''),
                        nama_barang: $(this).find('.namaBarangLabel').text(),
                        jumlah_barang: $(this).find('.counter').text(),
                        method: $(this).find('.metode').text(),
                        harga_item: $(this).find('.hargaJual').text().replaceAll('.', ''),
                        harga_jual: $(this).find('.totalJual').text().replaceAll('.', ''),
                    };
                });

                if (!arr[0]) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Keranjang masih kosong',
                        timer: 2000
                    });
                    return;
                }

                if (method === 'TUNAI') {
                    var kembalianCek = $('#kembalian').val();
                    if (kembalianCek == 'kurang') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Pastikan Uang Cukup',
                            timer: 2500
                        });
                        return;
                    }
                    var kembali = $('#kembalian').val().replaceAll('.', '');
                    var pembayaran = $('#jumlahUang').val().replaceAll('.', '');

                    Swal.fire({
                        title: 'Konfirmasi Pembayaran',
                        html: `
                            <div class="text-start fs-7 p-2 rounded bg-light border" style="font-family: inherit;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Belanja:</span>
                                    <strong class="text-body">Rp ${rupiah(total_harga)}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Uang Diterima:</span>
                                    <strong class="text-success">Rp ${rupiah(pembayaran)}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Kembalian:</span>
                                    <strong class="text-primary">Rp ${rupiah(kembali)}</strong>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-center fw-bold text-muted" style="font-size:0.82rem;">Apakah data pembayaran sudah benar?</p>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Bayar Sekarang',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(".simpan").attr("disabled", true);
                            ajaxQuery('post', '/transaksi/penjualan/store', {
                                invoice,
                                kembali,
                                pembayaran,
                                total_harga,
                                data: arr,
                                kode_toko: '{{ $data_toko->kode }}'
                            });
                        }
                    });
                } else {
                    // Gateway Payment (QRIS, VA, E-WALLET)
                    var grandTotal = $('#digitalGrandTotal').text().replace('Rp ', '').replaceAll('.', '');
                    var fee = $('#digitalFee').text().replace('Rp ', '').replaceAll('.', '');

                    Swal.fire({
                        title: 'Konfirmasi ' + method,
                        html: `
                            <div class="text-start fs-7 p-2 rounded bg-light border" style="font-family: inherit;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Belanja:</span>
                                    <strong class="text-body">Rp ${rupiah(total_harga)}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Fee+Tax:</span>
                                    <strong class="text-danger">+ Rp ${rupiah(fee)}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>Total Tagihan:</span>
                                    <strong class="text-primary fs-6">Rp ${rupiah(grandTotal)}</strong>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-center fw-bold text-muted" style="font-size:0.82rem;">Buat tagihan Xendit sekarang?</p>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#0ea5e9',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Buat Tagihan',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return $.ajax({
                                url: '/transaksi/penjualan/xendit/create',
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                        'content')
                                },
                                data: {
                                    invoice: invoice,
                                    total_harga: total_harga,
                                    pembayaran: method,
                                    data: arr,
                                    kode_toko: '{{ $data_toko->kode }}'
                                }
                            }).catch(error => {
                                Swal.showValidationMessage('Gagal membuat tagihan: ' +
                                    error.responseJSON?.message);
                            });
                        },
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var resp = result.value;
                            if (resp.success) {
                                // Show Checkout URL Iframe or QR code
                                Swal.fire({
                                    title: 'Selesaikan Pembayaran',
                                    html: `
                                        <div class="mb-2 text-center text-muted fs-7">Silahkan selesaikan pembayaran. Pop-up ini akan tertutup otomatis jika berhasil dibayar.</div>
                                        <div style="height: 400px; width: 100%; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
                                            <iframe src="${resp.checkout_url}" style="width: 100%; height: 100%; border: none;"></iframe>
                                        </div>
                                    `,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    width: '450px'
                                });

                                // Start listening to Firebase for Payment Success
                                window.listenToPaymentSuccess(invoice, function(status, data) {
                                    if (status === 'PAID') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Pembayaran Berhasil!',
                                            text: 'Transaksi telah lunas dan stok diperbarui.',
                                            timer: 3000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            localStorage.removeItem(
                                                'hijabkku_pos_cart');
                                            location.reload();
                                        });
                                    } else if (status === 'EXPIRED') {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Waktu Pembayaran Habis!',
                                            text: 'Tagihan ini telah dibatalkan karena melewati batas waktu pembayaran.',
                                            confirmButtonText: 'Tutup'
                                        }).then(() => {
                                            location.reload();
                                        });
                                    }
                                });
                            } else {
                                Swal.fire('Error', resp.message, 'error');
                            }
                        }
                    });
                }
            });

            var jumlahUangMask = IMask(document.getElementById('jumlahUang'), {
                mask: 'num',
                blocks: {
                    num: {
                        mask: Number,
                        thousandsSeparator: '.'
                    }
                }
            });

            $('#jumlahUang').on('keyup', function() {
                var jumlah = $(this).val().replaceAll('.', '');
                var total = $('.totals .totalRupiah').text();
                if (!total) total = 0;
                var all = +jumlah - +total;
                if (all >= 0) {
                    $('#kembalian').val(rupiah(all)).removeClass('kurang');
                } else {
                    $('#kembalian').val('kurang').addClass('kurang');
                }
            });

            $('#button_pas').click(function() {
                var total = $('.totals .totalRupiah').text();
                if (total > 0) {
                    $('#jumlahUang').val(rupiah(total));
                    jumlahUangMask.updateValue();
                    $('#kembalian').val(0).removeClass('kurang');
                }
            });

            $(document).on('click', '.kurangi', function() {
                var barang = $(this).attr('data-kode_barang');
                var counter = $('#transaksi_' + barang + ' .counter');
                var jual = $('#transaksi_' + barang + ' .totalJual');
                var hargaItem = parseInt($('#transaksi_' + barang + ' .hargaJual').text().replaceAll('.',
                    ''));
                var jumlahLama = parseInt(counter.text());
                var newCount = jumlahLama - 1;

                if (newCount <= 0) {
                    $('#transaksi_' + barang).remove();
                    $('#visual_transaksi_' + barang).remove();
                } else {
                    counter.text(newCount);
                    var newTotal = parseInt(jual.text().replaceAll('.', '')) - hargaItem;
                    jual.text(newTotal);

                    // Update POS Visual row
                    var visRow = $('#visual_transaksi_' + barang);
                    visRow.find('.visual-counter').text(newCount);
                    visRow.find('.visual-total').text('Rp ' + rupiah(newTotal));
                }

                // Restore stock on card
                $('button.selected[data-kode_barang="' + barang + '"]').each(function() {
                    var el = $(this).find('.sisaStock');
                    var cur = parseInt(el.attr('data-jumlah')) + 1;
                    el.attr('data-jumlah', cur).text(getStockText(cur));
                    $(this).prop('disabled', false).css({
                        'opacity': '',
                        'pointer-events': ''
                    });
                    $(this).closest('.product-card').removeClass('stock-ok stock-low stock-empty')
                        .addClass(stockClass(cur));
                });

                recalculatePosTotal();
                saveCartToStorage();
            });
        });
    </script>

    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import {
            getDatabase,
            ref,
            onValue
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        const updatesRef = ref(db, 'hijabkku/updates/sales');
        let isInitialLoad = true;

        onValue(updatesRef, (snapshot) => {
            if (isInitialLoad) {
                isInitialLoad = false;
                return;
            }
            const data = snapshot.val();
            if (data && data.toko === "{{ $data_toko->kode }}") {
                // Trigger Ajax reload preserving current search criteria
                if (typeof window.ajaxQuery === 'function') {
                    window.ajaxQuery('get', '/transaksi/penjualan/create', {
                        key1: $('input[name=cariProduk]').val(),
                        key2: $('input[name=cariProdukOptional]').val(),
                        kode_toko: '{{ $data_toko->kode }}'
                    });
                }
            }
        });

        window.listenToPaymentSuccess = function(invoice, callback) {
            const paymentRef = ref(db, 'hijabkku/updates/payment_success_' + invoice);
            onValue(paymentRef, (snapshot) => {
                const data = snapshot.val();
                if (data && (data.status === 'PAID' || data.status === 'EXPIRED')) {
                    callback(data.status, data);
                }
            });
        };
    </script>
@endpush
