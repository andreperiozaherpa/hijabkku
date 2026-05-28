@extends('landing.app')

@section('title', 'Katalog Produk - Hijabkku')

@section('content')
    <!-- Header Banner -->
    <section class="py-5 text-center" style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%);">
        <div class="container py-4">
            <h1 class="fw-bold display-5 mb-2" style="color: #2c2c2c;">Katalog
                {{ $selectedToko->nama_toko ?? 'Hijabku' }}</h1>
            <p class="text-muted lead">Temukan koleksi hijab premium terbaik di
                {{ $selectedToko->nama_toko ?? 'Hijabku' }}.</p>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-5">
        <div class="container">

            <!-- Store Selector & Live Product Search -->
            <div class="row justify-content-center mb-5 g-4">
                <!-- Store Selector -->
                <div class="col-12 col-md-5 text-start">
                    <label for="toko-selector" class="form-label fw-bold text-muted mb-2"><i data-acorn-icon="shop"
                            class="text-gold me-2"></i>PILIH CABANG TOKO</label>
                    <select class="form-select select2-toko" id="toko-selector" style="width: 100%;"
                        aria-label="Pilih cabang toko">
                        @foreach ($tokos as $t)
                            <option value="{{ $t->kode }}" {{ $selectedTokoKode == $t->kode ? 'selected' : '' }}>
                                {{ $t->nama_toko }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Live Product Search -->
                <div class="col-12 col-md-5 text-start">
                    <label for="product-search" class="form-label fw-bold text-muted mb-2"><i data-acorn-icon="search"
                            class="text-gold me-2"></i>CARI HIJAB DI CABANG INI</label>
                    <div class="position-relative">
                        <input type="text" id="product-search" class="form-control rounded-pill px-4"
                            style="height: 50px; border: 2px solid #ffe4e1; font-weight: 500;"
                            placeholder="Ketik nama hijab..." aria-label="Cari hijab"
                            value="{{ request()->query('search') }}">
                        <i data-acorn-icon="search" class="position-absolute text-muted"
                            style="right: 20px; top: 16px; font-size: 18px;"></i>
                    </div>
                </div>
            </div>

            <div id="dynamic-products-wrapper">
                @if ($stocks->isEmpty())
                    @if (request()->query('search'))
                        <div id="no-results-alert"
                            class="text-center py-5 my-5 rounded-4 shadow-sm bg-white border border-light"
                            style="border-radius: 20px;">
                            <div class="p-4 py-5">
                                <i data-acorn-icon="search" class="text-gold mb-3" style="font-size: 4rem;"></i>
                                <h3 class="fw-bold mb-2">Produk Tidak Ditemukan</h3>
                                <p class="text-muted mb-4">Maaf, tidak ada hijab dengan kata kunci
                                    "{{ request()->query('search') }}" di cabang ini.</p>
                                <button onclick="clearSearch()" class="btn btn-gold rounded-pill px-4">Lihat Semua
                                    Produk</button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5 my-5 rounded-4 shadow-sm bg-white border border-light"
                            style="border-radius: 20px;">
                            <div class="p-4 py-5">
                                <i data-acorn-icon="info-circle" class="text-gold mb-3" style="font-size: 4rem;"></i>
                                <h3 class="fw-bold mb-2">Stok Sedang Kosong</h3>
                                <p class="text-muted mb-4">Maaf, persediaan hijab di cabang
                                    {{ $selectedToko->nama_toko ?? '' }} sedang kosong atau dalam pembaruan.</p>
                                <a href="https://wa.me/6281234567890" target="_blank"
                                    class="btn btn-gold rounded-pill px-4">Hubungi Admin via WhatsApp</a>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="row g-2 g-md-4" id="products-grid">
                        @foreach ($stocks as $stock)
                            @if ($stock->data_barang)
                                @php
                                    $itemName = strtolower($stock->data_barang->nama_barang ?? '');
                                    $imgUrl = '/img/product_1.png'; // default fallback
                                    $tag = 'HIJAB';

                                    if (str_contains($itemName, 'bella') || str_contains($itemName, 'square')) {
                                        $imgUrl = '/img/product_2.png';
                                        $tag = 'BEST SELLER';
                                    } elseif (
                                        str_contains($itemName, 'khimar') ||
                                        str_contains($itemName, 'syari') ||
                                        str_contains($itemName, 'syar\'i')
                                    ) {
                                        $imgUrl = '/img/product_3.png';
                                        $tag = 'PREMIUM';
                                    } elseif (str_contains($itemName, 'paris')) {
                                        $imgUrl = '/img/product_4.png';
                                        $tag = 'NEW';
                                    }
                                @endphp

                                <div class="col-6 col-md-4 col-lg-3 product-card-wrapper">
                                    <div class="card product-card h-100 position-relative" style="cursor: pointer;"
                                        onclick="checkout('{{ addslashes($stock->data_barang->nama_barang ?? 'Hijab') }}', {{ (int) str_replace('.', '', $stock->data_barang->harga_jual ?? '0') }}, '{{ $imgUrl }}')">
                                        <div class="badge-tag tag-{{ strtolower(str_replace(' ', '-', $tag)) }}">
                                            {{ $tag }}</div>
                                        <div class="product-img overflow-hidden">
                                            <img src="{{ $imgUrl }}"
                                                alt="{{ $stock->data_barang->nama_barang ?? 'Hijab' }}"
                                                style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="card-body p-3 d-flex flex-column text-center">
                                            <h5 class="fw-bold mb-1 product-title font-serif"
                                                style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                                {{ $stock->data_barang->nama_barang ?? 'Hijab Premium' }}</h5>
                                            <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp
                                                {{ number_format((int) str_replace('.', '', $stock->data_barang->harga_jual ?? '0'), 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Custom Classic Numbered Pagination Links (1 2 3 ... 10 > >>) -->
                    @if ($stocks->hasPages())
                        <style>
                            .badge-tag {
                                position: absolute;
                                top: 15px;
                                left: 15px;
                                background-color: #D4AF37;
                                color: white;
                                padding: 5px 15px;
                                border-radius: 20px;
                                font-size: 0.75rem;
                                font-weight: bold;
                                z-index: 2;
                                letter-spacing: 1px;
                                text-transform: uppercase;
                            }

                            .badge-tag.tag-best-seller {
                                background-color: #D4AF37 !important;
                                /* Gold */
                                color: white !important;
                            }

                            .badge-tag.tag-new {
                                background-color: #ffb6c1 !important;
                                /* Pastel Pink */
                                color: #2c2c2c !important;
                            }

                            .badge-tag.tag-premium {
                                background-color: #4A3B32 !important;
                                /* Charcoal Brown */
                                color: white !important;
                            }

                            .badge-tag.tag-hijab {
                                background-color: #a8a8a8 !important;
                                /* Pastel Grey */
                                color: white !important;
                            }

                            .product-img {
                                aspect-ratio: 3/4 !important;
                                height: auto !important;
                            }

                            @media (max-width: 576px) {
                                .product-card .card-body {
                                    padding: 0.75rem !important;
                                }

                                .product-card .product-title {
                                    font-size: 0.9rem !important;
                                    letter-spacing: 0.5px !important;
                                    margin-bottom: 2px !important;
                                }

                                .product-card p {
                                    font-size: 0.85rem !important;
                                }

                                .badge-tag {
                                    top: 10px !important;
                                    left: 10px !important;
                                    padding: 3px 10px !important;
                                    font-size: 0.6rem !important;
                                    letter-spacing: 0.5px !important;
                                }

                                .pagination-wrapper nav {
                                    padding: 0.25rem 0.35rem !important;
                                }

                                .pagination-wrapper .btn,
                                .pagination-wrapper span {
                                    width: 30px !important;
                                    height: 30px !important;
                                    font-size: 0.75rem !important;
                                    margin-left: 1px !important;
                                    margin-right: 1px !important;
                                }

                                .pagination-wrapper .me-3 {
                                    margin-right: 0.25rem !important;
                                }

                                .pagination-wrapper .ms-3 {
                                    margin-left: 0.25rem !important;
                                }
                            }
                        </style>
                        <div class="d-flex justify-content-center align-items-center mt-5 pagination-wrapper"
                            id="pagination-container">
                            <nav aria-label="Pagination Navigation"
                                class="d-flex align-items-center bg-white shadow-sm p-2 rounded-pill border border-light"
                                style="border-radius: 50px;">
                                {{-- First Page Link << --}}
                                @if ($stocks->onFirstPage())
                                    <span
                                        class="btn btn-link text-muted disabled rounded-circle d-none d-md-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none"
                                        style="width: 36px; height: 36px; cursor: not-allowed; opacity: 0.3;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                            <path fill-rule="evenodd"
                                                d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $stocks->url(1) }}"
                                        class="btn btn-link text-gold page-link rounded-circle d-none d-md-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none"
                                        style="width: 36px; height: 36px; color: #D4AF37;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-double-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                            <path fill-rule="evenodd"
                                                d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Previous Page Link < --}}
                                @if ($stocks->onFirstPage())
                                    <span
                                        class="btn btn-link text-muted disabled rounded-circle d-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none me-3"
                                        style="width: 36px; height: 36px; cursor: not-allowed; opacity: 0.3;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $stocks->previousPageUrl() }}"
                                        class="btn btn-link text-gold page-link rounded-circle d-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none me-3"
                                        style="width: 36px; height: 36px; color: #D4AF37;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Page Number Links --}}
                                @foreach ($stocks->links()->elements as $element)
                                    {{-- "Three Dots" Separator --}}
                                    @if (is_string($element))
                                        <span
                                            class="d-inline-flex align-items-center justify-content-center mx-1 text-muted fw-bold"
                                            style="width: 36px; height: 36px; font-family: 'Montserrat', sans-serif;">{{ $element }}</span>
                                    @endif

                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $stocks->currentPage())
                                                <span
                                                    class="btn bg-gold text-white rounded-circle mx-1 fw-bold d-inline-flex align-items-center justify-content-center p-0"
                                                    style="width: 36px; height: 36px; font-size: 0.85rem; font-family: 'Montserrat', sans-serif; background-color: #D4AF37 !important; border-color: #D4AF37 !important; color: white !important;">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}"
                                                    class="btn btn-link text-dark page-link rounded-circle d-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none"
                                                    style="width: 36px; height: 36px; font-size: 0.85rem; font-family: 'Montserrat', sans-serif;">{{ $page }}</a>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link > --}}
                                @if ($stocks->hasMorePages())
                                    <a href="{{ $stocks->nextPageUrl() }}"
                                        class="btn btn-link text-gold page-link rounded-circle d-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none ms-3"
                                        style="width: 36px; height: 36px; color: #D4AF37;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="btn btn-link text-muted disabled rounded-circle d-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none ms-3"
                                        style="width: 36px; height: 36px; cursor: not-allowed; opacity: 0.3;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                @endif

                                {{-- Last Page Link >> --}}
                                @if ($stocks->hasMorePages())
                                    <a href="{{ $stocks->url($stocks->lastPage()) }}"
                                        class="btn btn-link text-gold page-link rounded-circle d-none d-md-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none"
                                        style="width: 36px; height: 36px; color: #D4AF37;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                            <path fill-rule="evenodd"
                                                d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="btn btn-link text-muted disabled rounded-circle d-none d-md-inline-flex align-items-center justify-content-center p-0 mx-1 text-decoration-none"
                                        style="width: 36px; height: 36px; cursor: not-allowed; opacity: 0.3;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                                            <path fill-rule="evenodd"
                                                d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <!-- Policies / Xendit Verification Requirements Section -->
    <section id="kebijakan" class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Kebijakan & Ketentuan Layanan</h2>
                <p class="text-muted">Kebijakan operasional toko online Hijabkku</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="policy-section h-100">
                        <h4 class="fw-bold text-gold mb-3"><i data-acorn-icon="check" class="me-2"></i>Syarat &
                            Ketentuan</h4>
                        <p class="text-muted small">
                            1. Seluruh pemesanan produk dilakukan secara langsung melalui kontak resmi toko kami
                            (WhatsApp/Instagram @hijabkku).<br>
                            2. Pembayaran dapat dilakukan menggunakan metode transfer bank atau e-wallet resmi yang
                            disediakan saat invoice diterbitkan.<br>
                            3. Harga produk yang tertera di website adalah harga mutlak dan belum termasuk ongkos kirim
                            dari kota pengiriman kami.
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-section h-100">
                        <h4 class="fw-bold text-gold mb-3"><i data-acorn-icon="multiply" class="me-2"></i>Kebijakan
                            Pengembalian (Refund)</h4>
                        <p class="text-muted small">
                            1. Kami menjamin kualitas terbaik. Jika barang yang diterima cacat produksi atau salah kirim
                            variasi, Anda berhak mengajukan retur dalam 2x24 jam sejak barang diterima.<br>
                            2. Wajib menyertakan video unboxing utuh tanpa jeda sebagai syarat utama pengajuan
                            pengembalian barang atau dana.<br>
                            3. Dana refund akan dikembalikan secara penuh 100% apabila stok pengganti kosong.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Checkout Simulator Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="row g-0">
                    <!-- Left Column: Form -->
                    <div class="col-lg-7 p-5 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold font-serif mb-0">Informasi Pengiriman</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="alert alert-warning rounded-3 border-0 bg-light p-4 mb-4" style="color: #856404;">
                            <h6 class="fw-bold mb-2"><i data-acorn-icon="warning-hexagon" class="me-2"
                                    style="color: #D4AF37;"></i> Informasi Penting:</h6>
                            <p class="small mb-0">Untuk keamanan Anda, seluruh komunikasi resmi hanya melalui
                                <strong>0812-3456-7890</strong>. Mohon berhati-hati terhadap pesan dari nomor lain yang
                                mengatasnamakan Hijabkku.
                            </p>
                        </div>

                        <form id="orderForm" onsubmit="submitOrder(event)">
                            <h6 class="fw-bold mb-3 mt-4">Kontak</h6>
                            <div class="mb-4">
                                <input type="tel" class="form-control form-control-lg rounded-3"
                                    placeholder="Nomor WhatsApp" style="font-size: 0.9rem;" required>
                            </div>

                            <h6 class="fw-bold mb-3">Alamat Pengiriman</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-lg rounded-3"
                                        placeholder="Nama Depan" style="font-size: 0.9rem;" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control form-control-lg rounded-3"
                                        placeholder="Nama Belakang" style="font-size: 0.9rem;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control form-control-lg rounded-3" rows="3"
                                    placeholder="Alamat lengkap (Nama jalan, gedung, no. rumah)" style="font-size: 0.9rem;" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-gold w-100 mt-4 py-3 text-uppercase"
                                style="letter-spacing: 1px;">Kirim Pesanan (via WhatsApp)</button>
                        </form>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="col-lg-5 p-5" style="background-color: #faf9f6; border-left: 1px solid #f0eee9;">
                        <h4 class="fw-bold font-serif mb-4">Ringkasan Pesanan</h4>

                        <div class="d-flex align-items-center mb-4 pb-4 border-bottom border-light">
                            <div class="position-relative me-3">
                                <div class="rounded-3 overflow-hidden"
                                    style="width: 80px; height: 80px; border: 1px solid #ffe4e1;">
                                    <img id="modal-product-img" src="/img/product_1.png" alt="Product"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">1</span>
                            </div>
                            <div class="flex-grow-1">
                                <strong id="modal-product-name" class="d-block text-dark font-serif"
                                    style="font-size: 1.1rem;"></strong>
                                <span class="small text-muted">Kuantitas: 1</span>
                            </div>
                            <div class="text-end">
                                <strong id="modal-product-price" class="text-dark"></strong>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal</span>
                            <strong id="modal-subtotal"></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-4 small pb-4 border-bottom border-light">
                            <span class="text-muted">Pengiriman</span>
                            <span class="text-muted">Dihitung otomatis</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5">Total</span>
                            <strong class="fs-4 text-gold" id="modal-total"></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof acorn !== 'undefined' && acorn.Icons) {
                acorn.Icons.replace();
            }

            // Initialize Select2 for store selector with accessibility focus helper
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                const $select2 = $('.select2-toko').select2();

                // Automatically focus the search field when Select2 opens (A11y best practice)
                $select2.on('select2:open', function() {
                    setTimeout(function() {
                        const searchField = document.querySelector('.select2-search__field');
                        if (searchField) {
                            searchField.focus();
                        }
                    }, 50);
                });

                // Unified function to load catalog content dynamically via AJAX
                function loadCatalog(url) {
                    const wrapper = $('#dynamic-products-wrapper');
                    if (wrapper.length) {
                        wrapper.css('opacity', 0.5);
                    }

                    fetch(url)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newWrapper = doc.getElementById('dynamic-products-wrapper');

                            if (newWrapper) {
                                $('#dynamic-products-wrapper').replaceWith(newWrapper);
                            }

                            // Update browser address bar query params without reloading
                            window.history.pushState({
                                path: url
                            }, '', url);

                            // Re-initialize Acorn Icons for dynamic content
                            if (typeof acorn !== 'undefined' && acorn.Icons) {
                                acorn.Icons.replace();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            if (wrapper.length) {
                                wrapper.css('opacity', 1);
                            }
                        });
                }

                $('#toko-selector').on('change', function() {
                    performSearch();
                });

                // Live product search implementation (Debounced AJAX HTML-replacement)
                let searchTimeout;

                function performSearch() {
                    clearTimeout(searchTimeout);
                    const query = $('#product-search').val().trim();
                    const selectedToko = $('#toko-selector').val() || '';
                    const url = `?toko=${selectedToko}&search=${encodeURIComponent(query)}`;
                    loadCatalog(url);
                }

                // Debounce search: triggers only when the user stops typing (600ms)
                $('#product-search').on('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 600);
                });

                // Instant search on Enter key press
                $('#product-search').on('keypress', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        performSearch();
                    }
                });

                // Intercept pagination clicks for smooth AJAX loading
                $(document).on('click', '#dynamic-products-wrapper .page-link', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    if (url) {
                        loadCatalog(url);
                    }
                });
            }
        });

        // Function to clear search field and reload complete catalog
        // Function to clear search field and reload complete catalog
        function clearSearch() {
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                searchInput.value = '';
                // Trigger input event to fire the AJAX live search and reset
                $(searchInput).trigger('input');
            }
        }

        let selectedProduct = '';
        let selectedPrice = 0;

        function checkout(name, price, img) {
            selectedProduct = name;
            selectedPrice = price;

            document.getElementById('modal-product-name').innerText = name;

            const formattedPrice = 'Rp ' + price.toLocaleString('id-ID');
            document.getElementById('modal-product-price').innerText = formattedPrice;
            document.getElementById('modal-subtotal').innerText = formattedPrice;
            document.getElementById('modal-total').innerText = formattedPrice;

            if (img) {
                document.getElementById('modal-product-img').src = img;
            }

            const myModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            myModal.show();
        }

        function submitOrder(e) {
            e.preventDefault();
            // Simulating checkout. Redirects to WhatsApp with pre-filled message
            const message =
                `Halo Hijabkku, saya ingin memesan produk "${selectedProduct}" seharga Rp ${selectedPrice.toLocaleString('id-ID')}.`;
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/6281234567890?text=${encodedMessage}`, '_blank');

            const myModal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal'));
            myModal.hide();
        }
    </script>
@endsection
