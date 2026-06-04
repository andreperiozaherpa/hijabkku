@extends('landing.app')

@section('title', 'Katalog Produk - Hijabkku')

@section('styles')
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
            color: white !important;
        }

        .badge-tag.tag-new {
            background-color: #ffb6c1 !important;
            color: #2c2c2c !important;
        }

        .badge-tag.tag-premium {
            background-color: #4A3B32 !important;
            color: white !important;
        }

        .badge-tag.tag-hijab {
            background-color: #a8a8a8 !important;
            color: white !important;
        }

        .badge-tag.tag-habis {
            background-color: #7f8c8d !important;
            color: white !important;
        }

        .product-card {
            border: 1px solid #f2e6e6 !important;
            border-radius: 16px !important;
            overflow: hidden;
            background: #ffffff;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), border-color 0.4s ease !important;
            box-shadow: 0 4px 15px rgba(74, 59, 50, 0.03) !important;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 30px rgba(212, 175, 55, 0.08) !important;
            border-color: #D4AF37 !important;
        }

        .product-card.out-of-stock {
            opacity: 0.65;
            cursor: not-allowed !important;
        }

        .product-card.out-of-stock:hover {
            transform: none !important;
            box-shadow: 0 4px 15px rgba(74, 59, 50, 0.03) !important;
            border-color: #f2e6e6 !important;
        }

        .product-card.out-of-stock .product-img img {
            filter: grayscale(80%);
        }

        .product-img {
            position: relative;
            aspect-ratio: 3/4 !important;
            height: auto !important;
            overflow: hidden;
            background: #fdfbf7;
        }

        .product-img img {
            transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .product-card:not(.out-of-stock):hover .product-img img {
            transform: scale(1.06);
        }

        #floating-cart button {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
        }

        #floating-cart button:hover {
            transform: scale(1.1) rotate(-3deg);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4) !important;
        }

        .cart-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
        }

        .offcanvas {
            background-color: #ffffff !important;
            box-shadow: -10px 0 30px rgba(74, 59, 50, 0.15) !important;
        }

        /* Payment Selectors Refinements */
        .payment-card-label {
            border: 2px solid #f0eee9;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: white;
        }

        .payment-card-label:hover {
            border-color: #ffd1dc;
            transform: translateY(-2px);
        }

        .btn-check:checked+.payment-card-label {
            border-color: #D4AF37 !important;
            background-color: #faf6f0 !important;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.1) !important;
        }

        .btn-check:checked+.payment-card-label .text-muted {
            color: #D4AF37 !important;
        }

        /* Shimmer loading animations */
        .shimmer-pulse {
            animation: pulse-shimmer 1.5s ease-in-out infinite;
        }

        @keyframes pulse-shimmer {
            0% {
                opacity: 0.4;
            }

            50% {
                opacity: 0.75;
            }

            100% {
                opacity: 0.4;
            }
        }

        /* Custom Toast CSS */
        #toast-container {
            z-index: 9999 !important;
        }

        .toast-item {
            min-width: 280px;
            max-width: 380px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-left: 5px solid #D4AF37 !important;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            color: #2c2c2c;
            padding: 14px 18px;
            margin-bottom: 10px;
            animation: toastSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            transition: all 0.3s ease;
        }

        .toast-item.toast-danger {
            border-left-color: #dc3545 !important;
        }

        .toast-item.toast-warning {
            border-left-color: #ffc107 !important;
        }

        .toast-item.toast-success {
            border-left-color: #28a745 !important;
        }

        @keyframes toastSlideIn {
            from {
                transform: translateX(120%) translateY(-10px);
                opacity: 0;
            }

            to {
                transform: translateX(0) translateY(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(120%);
                opacity: 0;
            }
        }

        /* Floating badge animation */
        @keyframes badgePulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }

            100% {
                transform: scale(1);
            }
        }

        .badge-updated {
            animation: badgePulse 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
@endsection

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
                                <a href="https://wa.me/6282280783843" target="_blank"
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

                                @php
                                    $avail = $stock->jumlah - $stock->terjual;
                                    if ($avail <= 0) {
                                        $tag = 'HABIS';
                                    }
                                @endphp

                                <div class="col-6 col-md-4 col-lg-3 product-card-wrapper">
                                    <div class="card product-card h-100 position-relative {{ $avail <= 0 ? 'out-of-stock' : '' }}"
                                        style="cursor: pointer;"
                                        onclick="showProductDetail('{{ $stock->kode_barang }}', '{{ addslashes($stock->data_barang->nama_barang ?? 'Hijab') }}', {{ (int) str_replace('.', '', $stock->data_barang->harga_jual ?? '0') }}, '{{ $imgUrl }}', {{ $avail }}, '{{ addslashes($stock->data_barang->jenis_barang ?? 'Hijab') }}', '{{ $tag }}')">
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

                                            @if ($avail > 0)
                                                <span class="d-none small text-gold mt-2 fw-bold"
                                                    style="font-size: 0.8rem; font-family: 'Montserrat', sans-serif;">
                                                    Tersedia: {{ $avail }} pcs
                                                </span>
                                            @else
                                                <span class="d-block small text-danger mt-2 fw-bold"
                                                    style="font-size: 0.8rem; font-family: 'Montserrat', sans-serif;">
                                                    Stok Habis
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Custom Classic Numbered Pagination Links (1 2 3 ... 10 > >>) -->
                    @if ($stocks->hasPages())
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
                <h2 class="fw-bold font-serif text-dark">Kebijakan & Ketentuan Layanan</h2>
                <p class="text-muted">Kebijakan operasional toko online Hijabkku</p>
                <div class="mx-auto mt-2" style="width: 50px; height: 2px; background-color: #D4AF37;"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="policy-section h-100 p-4 rounded-3 bg-light border border-light">
                        <h4 class="fw-bold text-gold mb-3 font-serif"><i data-acorn-icon="check" class="me-2"></i>Syarat & Ketentuan</h4>
                        <p class="text-muted small mb-3">
                            1. Seluruh pemesanan produk dilakukan secara langsung melalui kontak resmi toko kami atau via website dengan metode Store Pickup.<br>
                            2. Pembayaran dapat dilakukan menggunakan QRIS, Virtual Account, atau E-Wallet melalui payment gateway terintegrasi.<br>
                            3. Harga produk yang tertera di website adalah harga mutlak dan sudah terverifikasi.
                        </p>
                        <a href="{{ route('terms') }}" class="text-gold small fw-bold text-decoration-none hover-gold">
                            Baca Syarat & Ketentuan Selengkapnya <i data-acorn-icon="arrow-right" class="ms-1" style="width: 12px; height: 12px;"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-section h-100 p-4 rounded-3 bg-light border border-light">
                        <h4 class="fw-bold text-gold mb-3 font-serif"><i data-acorn-icon="multiply" class="me-2"></i>Kebijakan Pengembalian (Refund)</h4>
                        <p class="text-muted small mb-3">
                            1. Kami menjamin kualitas terbaik. Jika barang yang diterima cacat produksi atau salah kirim, Anda berhak mengajukan retur dalam 2x24 jam.<br>
                            2. Wajib menyertakan video unboxing utuh tanpa jeda sebagai syarat utama pengajuan retur.<br>
                            3. Dana refund akan dikembalikan secara penuh 100% apabila stok pengganti kosong.
                        </p>
                        <a href="{{ route('refund-policy') }}" class="text-gold small fw-bold text-decoration-none hover-gold">
                            Baca Kebijakan Refund Selengkapnya <i data-acorn-icon="arrow-right" class="ms-1" style="width: 12px; height: 12px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Cart Offcanvas Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel"
        style="border-top-left-radius: 20px; border-bottom-left-radius: 20px; width: 450px; max-width: 100%;">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title fw-bold font-serif" id="cartOffcanvasLabel"><i data-acorn-icon="cart"
                    class="text-gold me-2"></i>Keranjang Belanja</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <!-- Store pickup info header -->
            <div class="alert alert-info rounded-3 border-0 bg-light p-3 mb-3 d-flex align-items-center">
                <i data-acorn-icon="shop" class="text-gold me-3 fs-4"></i>
                <div>
                    <span class="d-block small text-muted font-weight-bold">CABANG PENGAMBILAN</span>
                    <strong id="cart-pickup-store" class="text-dark"></strong>
                </div>
            </div>

            <!-- Cart Items List -->
            <div id="cart-items-container" class="flex-grow-1 overflow-auto pe-1">
                <!-- Dynamic Cart Items here -->
            </div>

            <!-- Cart Empty State -->
            <div id="cart-empty-state" class="text-center py-5 my-5">
                <i data-acorn-icon="cart" class="text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="text-muted fw-bold">Keranjang belanja Anda kosong</p>
                <button class="btn btn-outline-gold rounded-pill px-4 btn-sm" data-bs-dismiss="offcanvas">Mulai
                    Belanja</button>
            </div>

            <!-- Cart Footer -->
            <div id="cart-footer" class="border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted font-weight-bold">Subtotal</span>
                    <span id="cart-subtotal" class="fw-bold fs-5 text-gold">Rp 0</span>
                </div>
                <button class="btn btn-gold w-100 py-3 text-uppercase font-weight-bold" style="letter-spacing: 1px;"
                    onclick="openCheckoutModal()">Lanjutkan ke Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden; background: #ffffff;">
                <div class="position-relative">
                    <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal"
                        aria-label="Close"
                        style="top: 20px; right: 20px; z-index: 10; background-color: rgba(255,255,255,0.85); padding: 10px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: none;"></button>
                    <div class="product-modal-img-wrapper"
                        style="aspect-ratio: 4/3; overflow: hidden; background: #fdfbf7;">
                        <img id="modal-product-img" src="" alt=""
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span id="modal-product-badge"
                            class="badge-tag position-static px-3 py-1 font-serif text-uppercase"
                            style="font-size: 0.65rem; border-radius: 20px;"></span>
                        <span class="small text-muted">Kategori: <strong id="modal-product-category"
                                class="text-dark"></strong></span>
                    </div>
                    <h3 id="modal-product-title" class="fw-bold font-serif mb-2 text-dark"
                        style="font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px;"></h3>
                    <h4 id="modal-product-price" class="text-gold fw-bold mb-3"
                        style="font-family: 'Montserrat', sans-serif; font-size: 1.3rem;"></h4>

                    <div class="mb-3 p-3 bg-light rounded-3 d-flex align-items-center justify-content-between d-none">
                        <span class="small text-muted"><i class="bi bi-shop me-1"></i> Stok Cabang Tersedia:</span>
                        <strong id="modal-product-stock" class="text-dark"></strong>
                    </div>

                    <div class="border-top border-light pt-3 mb-4">
                        <h6 class="fw-bold text-dark font-serif mb-2" style="letter-spacing: 0.5px;">Deskripsi Produk</h6>
                        <p id="modal-product-desc" class="text-muted small mb-0" style="line-height: 1.6;"></p>
                    </div>

                    <div id="modal-action-wrapper">
                        <!-- Dynamic add to cart button -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="row g-0">
                    <!-- Left Column: Form -->
                    <div class="col-lg-7 p-4 p-md-5 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold font-serif mb-0">Detail Store Pickup & Pembayaran</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        @if (($xenditSimulationMode ?? 'false') === 'true')
                            <div class="alert alert-info rounded-3 border-0 p-3 mb-4 d-flex align-items-start"
                                style="color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb; font-size: 0.85rem;">
                                <div class="me-2 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Mode Simulasi Pembayaran Aktif</h6>
                                    <p class="mb-0">Aplikasi saat ini berjalan dalam mode simulasi pembayaran.
                                        Checkout/pembayaran tidak akan diproses ke sistem ril.</p>
                                </div>
                            </div>
                        @endif

                        <div class="alert alert-warning rounded-3 border-0 bg-light p-3 mb-4"
                            style="color: #856404; font-size: 0.85rem;">
                            <h6 class="fw-bold mb-1"><i data-acorn-icon="warning-hexagon" class="me-2 text-gold"></i>
                                Catatan Store Pickup:</h6>
                            <p class="mb-0">Pesanan ini wajib diambil sendiri oleh pembeli di cabang toko terpilih
                                setelah status pembayaran lunas.
                            </p>
                        </div>

                        <form id="landingCheckoutForm" onsubmit="submitCheckout(event)">
                            <h6 class="fw-bold mb-3 mt-4 text-gold">Informasi Pelanggan</h6>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nama Lengkap</label>
                                    <input type="text" id="cust-name" class="form-control form-control-lg rounded-3"
                                        style="font-size: 0.9rem;" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Nomor WhatsApp/HP</label>
                                    <input type="tel" id="cust-phone" class="form-control form-control-lg rounded-3"
                                        style="font-size: 0.9rem;" placeholder="Contoh: 081234567890" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Alamat Email (Opsional)</label>
                                <input type="email" id="cust-email" class="form-control form-control-lg rounded-3"
                                    style="font-size: 0.9rem;" placeholder="customer@email.com">
                                <div class="form-text text-muted small">Email digunakan untuk menerima link invoice
                                    pembayaran resmi.</div>
                            </div>

                            <h6 class="fw-bold mb-3 text-gold">Metode Pembayaran (Xendit Gateway)</h6>
                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-qris"
                                        value="QRIS" checked onchange="updatePaymentFees()">
                                    <label
                                        class="payment-card-label w-100 py-3 px-2 d-flex flex-column align-items-center justify-content-center h-100"
                                        for="pay-qris">
                                        <span class="fw-bold mb-1"
                                            style="font-size: 1.05rem; font-family: 'Montserrat', sans-serif; color: #2c2c2c;">QRIS</span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Fee 0.7%</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-va"
                                        value="VA" onchange="updatePaymentFees()">
                                    <label
                                        class="payment-card-label w-100 py-3 px-2 d-flex flex-column align-items-center justify-content-center h-100"
                                        for="pay-va">
                                        <span class="fw-bold mb-1"
                                            style="font-size: 1.05rem; font-family: 'Montserrat', sans-serif; color: #2c2c2c;">VA</span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Fee Rp. 5.040</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-ewallet"
                                        value="EWALLET" onchange="updatePaymentFees()">
                                    <label
                                        class="payment-card-label w-100 py-3 px-2 d-flex flex-column align-items-center justify-content-center h-100"
                                        for="pay-ewallet">
                                        <span class="fw-bold mb-1"
                                            style="font-size: 1.05rem; font-family: 'Montserrat', sans-serif; color: #2c2c2c;">E-Wallet</span>
                                        <span class="text-muted" style="font-size: 0.65rem;">Fee 1.665%</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Google reCAPTCHA v2 Checkbox -->
                            <div class="mb-3 mt-4 d-flex justify-content-center">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}">
                                </div>
                            </div>

                            <button type="submit" id="btn-submit-checkout"
                                class="btn btn-gold w-100 mt-4 py-3 text-uppercase fw-bold" style="letter-spacing: 1px;">
                                Bayar Sekarang
                            </button>
                        </form>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="col-lg-5 p-4 p-md-5" style="background-color: #faf9f6; border-left: 1px solid #f0eee9;">
                        <h4 class="fw-bold font-serif mb-4 text-gold">Ringkasan Pesanan</h4>

                        <!-- Selected Branch Pickup Alert -->
                        <div class="mb-4">
                            <span class="d-block small text-muted font-weight-bold">CABANG PENGAMBILAN:</span>
                            <span id="checkout-pickup-store" class="fw-bold text-dark fs-6"></span>
                        </div>

                        <!-- Product items list container -->
                        <div id="checkout-items-list" class="mb-4 overflow-auto" style="max-height: 250px;">
                            <!-- Dynamic items list here -->
                        </div>

                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted font-weight-bold">Subtotal Produk</span>
                            <strong id="checkout-subtotal" class="text-dark">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted font-weight-bold">Biaya Transaksi (<span
                                    id="fee-type-label">QRIS</span>)</span>
                            <strong id="checkout-fee" class="text-dark">Rp 0</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-light">
                            <span class="fs-5 fw-bold font-serif">Total Bayar</span>
                            <strong class="fs-4 text-gold" id="checkout-total">Rp 0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
@endsection

@section('scripts')
    <script>
        // Shopping Cart state
        let cart = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Setup Acorn Icons
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }

            // Initialize cart from localStorage if exists
            const savedCart = localStorage.getItem('hijabkku_cart');
            const savedToko = localStorage.getItem('hijabkku_cart_toko');
            const currentToko = document.getElementById('toko-selector').value;

            if (savedCart && savedToko === currentToko) {
                cart = JSON.parse(savedCart);
                updateCartUI();
            } else {
                // Clear cart if store selection changed
                cart = [];
                localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                localStorage.setItem('hijabkku_cart_toko', currentToko);
                updateCartUI();
            }

            // Update pickup labels
            updatePickupStoreLabel();

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
                        wrapper.addClass('shimmer-pulse');
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
                            if (typeof AcornIcons !== 'undefined') {
                                new AcornIcons().replace();
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            if (wrapper.length) {
                                wrapper.removeClass('shimmer-pulse');
                            }
                        });
                }

                $('#toko-selector').on('change', function() {
                    const selectedToko = $(this).val();
                    // Clear cart if branch changed to maintain correct branch stock selection
                    cart = [];
                    localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                    localStorage.setItem('hijabkku_cart_toko', selectedToko);
                    updateCartUI();
                    updatePickupStoreLabel();
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
        function clearSearch() {
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                searchInput.value = '';
                // Trigger input event to fire the AJAX live search and reset
                $(searchInput).trigger('input');
            }
        }

        // Elegant Toast notification system
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type} d-flex align-items-center`;

            let iconSvg = '';
            if (type === 'success') {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#28a745" class="bi bi-check-circle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;">
                             <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                           </svg>`;
            } else if (type === 'warning') {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffc107" class="bi bi-exclamation-triangle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;">
                             <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                           </svg>`;
            } else {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" class="bi bi-x-circle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;">
                             <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                           </svg>`;
            }

            toast.innerHTML = `
                ${iconSvg}
                <div class="flex-grow-1 small fw-bold" style="line-height: 1.4;">${message}</div>
                <button type="button" class="btn-close ms-2" style="font-size: 0.7rem; filter: brightness(0.2); flex-shrink: 0;" onclick="closeToast(this)"></button>
            `;

            container.appendChild(toast);

            // Auto dismiss toast after 3.5s
            setTimeout(() => {
                closeToast(toast.querySelector('.btn-close'));
            }, 3500);
        }

        window.showToast = showToast; // expose to global for inline onclick handlers

        function closeToast(btn) {
            if (!btn) return;
            const item = btn.closest('.toast-item');
            if (item) {
                item.style.animation = 'toastSlideOut 0.3s cubic-bezier(0.175, 0.885, 0.32, 1) forwards';
                setTimeout(() => item.remove(), 300);
            }
        }

        // Add to shopping cart function
        function addToCart(kode, name, price, img, maxStock) {
            if (maxStock <= 0) {
                showToast('Maaf, stok produk ini sedang kosong.', 'danger');
                return;
            }

            const existingItem = cart.find(item => item.kode_barang === kode);
            if (existingItem) {
                if (existingItem.jumlah + 1 > maxStock) {
                    showToast(`Maaf, stok ${name} terbatas. Hanya tersedia ${maxStock} pcs.`, 'warning');
                    return;
                }
                existingItem.jumlah++;
                showToast(`Jumlah ${name} ditambahkan.`, 'success');
            } else {
                cart.push({
                    kode_barang: kode,
                    nama_barang: name,
                    harga: price,
                    img: img,
                    jumlah: 1,
                    maxStock: maxStock
                });
                showToast(`${name} berhasil ditambahkan ke keranjang.`, 'success');
            }

            // Save cart to localstorage
            localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
            updateCartUI();

            // Trigger floating badge pulse animation
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.classList.remove('badge-updated');
                void badge.offsetWidth; // trigger reflow
                badge.classList.add('badge-updated');
            }

            // Open the cart offcanvas drawer automatically to show addition feedback
            const cartOffcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(cartOffcanvasEl) || new bootstrap.Offcanvas(
            cartOffcanvasEl);
            bsOffcanvas.show();
        }

        // Remove from cart
        function removeFromCart(kode) {
            cart = cart.filter(item => item.kode_barang !== kode);
            localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
            updateCartUI();
        }

        // Change quantity
        function changeQty(kode, delta) {
            const item = cart.find(item => item.kode_barang === kode);
            if (item) {
                if (delta > 0 && item.jumlah + delta > item.maxStock) {
                    showToast(`Maaf, stok terbatas. Hanya tersedia ${item.maxStock} pcs untuk produk ini.`, 'warning');
                    return;
                }
                item.jumlah += delta;
                if (item.jumlah <= 0) {
                    const itemName = item.nama_barang;
                    removeFromCart(kode);
                    showToast(`${itemName} dihapus dari keranjang.`, 'warning');
                } else {
                    localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                    updateCartUI();
                }
            }
        }

        // Update branch name labels
        function updatePickupStoreLabel() {
            const selectEl = document.getElementById('toko-selector');
            if (selectEl) {
                const storeName = selectEl.options[selectEl.selectedIndex].text;
                document.getElementById('cart-pickup-store').innerText = storeName;
                document.getElementById('checkout-pickup-store').innerText = storeName;
            }
        }

        // Calculate and update Cart UI elements
        function updateCartUI() {
            const container = document.getElementById('cart-items-container');
            const emptyState = document.getElementById('cart-empty-state');
            const footer = document.getElementById('cart-footer');
            const floatingCartBtn = document.getElementById('floating-cart');
            const badge = document.getElementById('cart-badge');

            if (cart.length === 0) {
                container.style.display = 'none';
                emptyState.style.display = 'block';
                footer.style.display = 'none';
                if (floatingCartBtn) floatingCartBtn.style.display = 'none';
                if (badge) badge.innerText = '0';
                return;
            }

            container.style.display = 'block';
            emptyState.style.display = 'none';
            footer.style.display = 'block';
            if (floatingCartBtn) floatingCartBtn.style.display = 'block';

            let totalItems = 0;
            let subtotal = 0;
            let htmlContent = '';

            cart.forEach(item => {
                totalItems += item.jumlah;
                const itemTotal = item.harga * item.jumlah;
                subtotal += itemTotal;

                htmlContent += `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                        <img src="${item.img}" class="cart-item-img me-3 border border-light" alt="${item.nama_barang}">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-dark font-serif" style="font-size: 0.95rem; text-transform: uppercase;">${item.nama_barang}</h6>
                            <span class="small text-gold fw-bold">Rp ${item.harga.toLocaleString('id-ID')}</span>
                            <div class="d-flex align-items-center mt-2">
                                <button class="btn btn-sm btn-outline-secondary rounded-circle py-0 px-2 font-weight-bold" style="width: 25px; height: 25px;" onclick="changeQty('${item.kode_barang}', -1)">-</button>
                                <span class="mx-3 fw-bold small">${item.jumlah}</span>
                                <button class="btn btn-sm btn-outline-secondary rounded-circle py-0 px-2 font-weight-bold" style="width: 25px; height: 25px;" onclick="changeQty('${item.kode_barang}', 1)">+</button>
                            </div>
                        </div>
                        <button class="btn text-danger p-2" onclick="removeFromCart('${item.kode_barang}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.054 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                            </svg>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = htmlContent;
            document.getElementById('cart-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (badge) badge.innerText = totalItems;

            // Re-initialize Acorn Icons inside dynamic cart
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }
        }

        // Open Product Detail Modal (Popup) with dynamic description mapping
        function showProductDetail(kode, name, price, img, maxStock, category, tag) {
            document.getElementById('modal-product-img').src = img;
            document.getElementById('modal-product-img').alt = name;

            const badgeEl = document.getElementById('modal-product-badge');
            badgeEl.innerText = tag;
            badgeEl.className = 'badge-tag position-static px-3 py-1 font-serif text-uppercase tag-' + tag.toLowerCase()
                .replace(' ', '-');

            document.getElementById('modal-product-category').innerText = category || 'Hijab';
            document.getElementById('modal-product-title').innerText = name;
            document.getElementById('modal-product-price').innerText = 'Rp ' + price.toLocaleString('id-ID');

            const stockEl = document.getElementById('modal-product-stock');
            if (maxStock > 0) {
                stockEl.innerText = maxStock + ' pcs';
                stockEl.className = 'text-success fw-bold';
            } else {
                stockEl.innerText = 'Habis';
                stockEl.className = 'text-danger fw-bold';
            }

            // Dynamic description generator based on product attributes
            let desc = '';
            const lowerName = name.toLowerCase();
            if (lowerName.includes('bella') || lowerName.includes('square')) {
                desc =
                    'Hijab Bella Square premium berbahan double hycon bertekstur lembut, adem, tidak licin, mudah diatur, dan jatuh dengan cantik saat dikenakan. Sangat cocok digunakan untuk aktivitas sehari-hari maupun acara semiformal Anda.';
            } else if (lowerName.includes('paris')) {
                desc =
                    'Hijab Paris premium kualitas terbaik yang tipis namun tetap tegak sempurna di dahi. Memiliki karakteristik bahan yang adem, lembut, tidak menerawang ketika dilipat dua, serta sangat populer sebagai hijab harian yang simpel dan elegan.';
            } else if (lowerName.includes('khimar') || lowerName.includes('syari') || lowerName.includes('syar\'i')) {
                desc =
                    'Khimar Syar\'i anggun berdesain menutup dada dengan sempurna. Menggunakan bahan ceruty/crepe premium ganda (double layer) yang jatuh dengan anggun, adem, serta nyaman digunakan sepanjang hari.';
            } else {
                desc =
                    'Hijab eksklusif edisi terbatas dari Hijabku. Menghadirkan kenyamanan maksimal dengan bahan premium pilihan yang lembut di kulit, sejuk, mudah dibentuk, serta hadir dalam variasi warna pastel yang mewah untuk melengkapi penampilan Anda.';
            }
            document.getElementById('modal-product-desc').innerText = desc;

            const actionWrapper = document.getElementById('modal-action-wrapper');
            if (maxStock > 0) {
                actionWrapper.innerHTML = `
                    <button class="btn btn-gold w-100 py-3 text-uppercase fw-bold" style="letter-spacing: 1px;" onclick="addToCartFromModal('${kode}', '${name.replace(/'/g, "\\'")}', ${price}, '${img}', ${maxStock})">
                        Tambahkan ke Keranjang
                    </button>
                `;
            } else {
                actionWrapper.innerHTML = `
                    <button class="btn btn-secondary w-100 py-3 text-uppercase fw-bold" style="cursor: not-allowed;" disabled>
                        Stok Habis di Cabang Ini
                    </button>
                `;
            }

            const detailModal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            detailModal.show();
        }

        // Helper to add item to cart from modal and close modal
        function addToCartFromModal(kode, name, price, img, maxStock) {
            // Dismiss detail modal
            const detailModalEl = document.getElementById('productDetailModal');
            const bsModal = bootstrap.Modal.getInstance(detailModalEl) || new bootstrap.Modal(detailModalEl);
            if (bsModal) {
                bsModal.hide();
            }

            // Add to cart
            addToCart(kode, name, price, img, maxStock);
        }

        // Open checkout modal
        function openCheckoutModal() {
            // Dismiss Cart offcanvas
            const cartOffcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(cartOffcanvasEl);
            if (bsOffcanvas) {
                bsOffcanvas.hide();
            }

            // Populate checkout modal
            const itemsContainer = document.getElementById('checkout-items-list');
            let html = '';
            let subtotal = 0;

            cart.forEach(item => {
                const totalItemPrice = item.harga * item.jumlah;
                subtotal += totalItemPrice;
                html += `
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom border-light">
                        <div class="position-relative me-3">
                            <div class="rounded-3 overflow-hidden" style="width: 60px; height: 60px; border: 1px solid #ffe4e1;">
                                <img src="${item.img}" alt="${item.nama_barang}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark" style="font-size: 0.7rem;">${item.jumlah}</span>
                        </div>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark font-serif" style="font-size: 0.9rem; text-transform: uppercase;">${item.nama_barang}</strong>
                            <span class="small text-muted">${item.jumlah} x Rp ${item.harga.toLocaleString('id-ID')}</span>
                        </div>
                        <div class="text-end">
                            <strong class="text-dark small">Rp ${totalItemPrice.toLocaleString('id-ID')}</strong>
                        </div>
                    </div>
                `;
            });

            itemsContainer.innerHTML = html;
            document.getElementById('checkout-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

            // Recalculate payment fees & grand total
            updatePaymentFees();

            // Show Checkout Modal
            const checkModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            checkModal.show();
        }

        // Recalculate Xendit payment fees & grand total on checkout modal
        function updatePaymentFees() {
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += (item.harga * item.jumlah);
            });

            const methodVal = document.querySelector('input[name="payment_method"]:checked').value;
            let fee = 0;
            let total = subtotal;
            let feeLabel = '';

            if (methodVal === 'QRIS') {
                feeLabel = 'QRIS 0.7%';
                const qrisRate = 0.007;
                total = Math.ceil(subtotal / (1 - qrisRate));
                fee = total - subtotal;
            } else if (methodVal === 'VA') {
                feeLabel = 'VA Flat + PPN';
                const vaFeeFlat = 4500;
                const ppnRate = 0.12;
                fee = vaFeeFlat + (vaFeeFlat * ppnRate);
                total = subtotal + fee;
            } else if (methodVal === 'EWALLET') {
                feeLabel = 'E-Wallet 1.665%';
                const effectiveRate = 0.01665;
                total = Math.ceil(subtotal / (1 - effectiveRate));
                fee = total - subtotal;
            }

            document.getElementById('fee-type-label').innerText = feeLabel;
            document.getElementById('checkout-fee').innerText = 'Rp ' + fee.toLocaleString('id-ID');
            document.getElementById('checkout-total').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Handle Xendit checkout submission
        function submitCheckout(e) {
            e.preventDefault();

            try {
                const nameEl = document.getElementById('cust-name');
                const name = nameEl ? nameEl.value.trim() : '';

                const phoneEl = document.getElementById('cust-phone');
                const phone = phoneEl ? phoneEl.value.trim() : '';

                const emailEl = document.getElementById('cust-email');
                const email = emailEl ? emailEl.value.trim() : '';

                const methodEl = document.querySelector('input[name="payment_method"]:checked');
                const method = methodEl ? methodEl.value : 'QRIS';

                const branchEl = document.getElementById('toko-selector');
                const branch = branchEl ? branchEl.value : '';

                if (!name || !phone) {
                    showToast('Mohon lengkapi data Nama Lengkap dan Nomor WhatsApp/HP!', 'warning');
                    return;
                }

                // Google reCAPTCHA Check
                let recaptchaResponse = '';
                if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.getResponse === 'function') {
                    try {
                        recaptchaResponse = grecaptcha.getResponse();
                    } catch (recaptchaErr) {
                        console.warn("grecaptcha.getResponse error:", recaptchaErr);
                    }
                }

                if (document.querySelector('.g-recaptcha') && !recaptchaResponse) {
                    showToast('Harap verifikasi bahwa Anda bukan robot!', 'warning');
                    return;
                }

                const btn = document.getElementById('btn-submit-checkout');
                if (!btn) {
                    console.error("Button btn-submit-checkout not found!");
                    return;
                }
                const originalBtnText = btn.innerHTML;

                const isSimulation = "{{ $xenditSimulationMode ?? 'false' }}";
                if (isSimulation === 'true') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Mode Simulasi Pembayaran',
                            text: 'Ini adalah simulasi bayar dan belum aktif sepenuhnya. Lanjutkan pembuatan tagihan simulasi?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#f59e0b',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Lanjutkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method,
                                    recaptchaResponse);
                            }
                        });
                    } else {
                        if (confirm(
                                'Mode Simulasi Pembayaran: Ini adalah simulasi bayar dan belum aktif sepenuhnya. Lanjutkan pembuatan tagihan simulasi?'
                                )) {
                            proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method,
                            recaptchaResponse);
                        }
                    }
                } else {
                    proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse);
                }
            } catch (err) {
                console.error("Error in submitCheckout handler:", err);
                alert("Kesalahan script checkout: " + err.message);
            }
        }

        function proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse) {
            try {
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses Tagihan Pembayaran...';
                btn.disabled = true;

                const cartPayload = cart.map(item => {
                    return {
                        kode_barang: item.kode_barang,
                        jumlah: item.jumlah
                    };
                });

                // Send AJAX call to backend checkout API
                fetch('/api/landing/checkout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            kode_toko: branch,
                            customer_name: name,
                            customer_phone: phone,
                            customer_email: email || null,
                            payment_method: method,
                            cart: cartPayload,
                            'g-recaptcha-response': recaptchaResponse
                        })
                    })
                    .then(response => response.json().then(data => ({
                        status: response.status,
                        body: data
                    })))
                    .then(res => {
                        if (res.status === 200 && res.body.success) {
                            // Success! Empty cart & redirect to checkout URL
                            cart = [];
                            localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                            updateCartUI();

                            // Close checkout modal
                            const checkoutModalEl = document.getElementById('checkoutModal');
                            if (checkoutModalEl) {
                                const checkoutModal = bootstrap.Modal.getInstance(checkoutModalEl);
                                if (checkoutModal) {
                                    checkoutModal.hide();
                                }
                            }

                            // Redirect to Xendit Invoice checkout page
                            window.location.href = res.body.checkout_url;
                        } else {
                            showToast('Gagal memproses Checkout: ' + (res.body.message ||
                                'Kesalahan sistem tidak dikenal.'), 'danger');
                            btn.innerHTML = originalBtnText;
                            btn.disabled = false;
                            if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                                try {
                                    grecaptcha.reset();
                                } catch (e) {}
                            }
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('Terjadi kesalahan koneksi saat memproses checkout. Silakan coba kembali.', 'danger');
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                            try {
                                grecaptcha.reset();
                            } catch (e) {}
                        }
                    });
            } catch (err) {
                console.error("Error in proceedWithCheckout:", err);
                alert("Kesalahan saat memproses checkout: " + err.message);
                btn.innerHTML = originalBtnText;
                btn.disabled = false;
            }
        }
    </script>
@endsection
