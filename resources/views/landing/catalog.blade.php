@extends('landing.app')

@section('title', 'Katalog Produk - Hijabkku')

@section('styles')
    <style>
        .catalog-card {
            background: var(--bg-card);
            overflow: hidden;
            transition: all 0.4s ease;
            text-decoration: none;
            display: block;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .catalog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }

        .catalog-card.out-of-stock {
            opacity: 0.6;
            cursor: not-allowed !important;
        }

        .catalog-card.out-of-stock:hover {
            transform: none;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .catalog-card.out-of-stock .catalog-card-img img {
            filter: grayscale(80%);
        }

        .catalog-card-img {
            aspect-ratio: 4/5;
            background: var(--bg-warm);
            overflow: hidden;
            position: relative;
        }

        .catalog-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .catalog-card:not(.out-of-stock):hover .catalog-card-img img {
            transform: scale(1.05);
        }

        .catalog-card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 26, 26, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .catalog-card:not(.out-of-stock):hover .catalog-card-overlay {
            opacity: 1;
        }

        .catalog-card-tag {
            position: absolute;
            top: 14px;
            left: 14px;
            font-size: 0.62rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 6px 14px;
            background: var(--accent);
            color: white;
            z-index: 2;
        }

        .catalog-card-tag.tag-habis {
            background: var(--text-light);
        }

        .catalog-card-body {
            padding: 16px 16px 20px;
        }

        .catalog-card-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 8px;
            letter-spacing: 0.2px;
            line-height: 1.4;
        }

        .catalog-card-price {
            font-size: 0.88rem;
            color: var(--accent);
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .catalog-card-stock {
            font-size: 0.78rem;
            color: var(--text-light);
            margin-top: 6px;
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 46px !important;
            border: 1px solid var(--border) !important;
            border-radius: 4px !important;
            background: var(--bg-card) !important;
            padding: 0 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.88rem !important;
            color: var(--text) !important;
            line-height: 44px !important;
            padding-left: 4px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            right: 10px !important;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--accent) !important;
        }

        .select2-dropdown {
            border: 1px solid var(--border) !important;
            border-radius: 4px !important;
            background: var(--bg-card) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
        }

        .select2-results__option {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.85rem !important;
            color: var(--text) !important;
            padding: 10px 16px !important;
        }

        .select2-results__option--highlighted {
            background: var(--accent-light) !important;
            color: var(--text) !important;
        }

        .select2-results__option[aria-selected="true"] {
            background: var(--accent) !important;
            color: white !important;
        }

        .select2-search__field {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.88rem !important;
            border: 1px solid var(--border) !important;
            border-radius: 4px !important;
            padding: 8px 12px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--text-light) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 0.88rem !important;
        }

        .offcanvas {
            background: var(--bg-card);
            border-left: 1px solid var(--border);
        }

        .product-modal-thumbnail {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border: 1px solid var(--border);
            cursor: pointer;
            opacity: 0.5;
            transition: all 0.2s ease;
        }

        .product-modal-thumbnail:hover { opacity: 0.8; }
        .product-modal-thumbnail.active { border-color: var(--accent); opacity: 1; }

        @media (max-width: 576px) {
            .catalog-card-body { padding: 12px; }
            .catalog-card-name { font-size: 0.78rem; }
            .catalog-card-price { font-size: 0.82rem; }
            .catalog-card-tag { top: 8px; left: 8px; font-size: 0.60rem; padding: 3px 8px; }
        }
    </style>
@endsection

@section('content')
    <!-- Header -->
    <section style="padding: 80px 0 60px; background: var(--bg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 text-center">
                    <span class="section-label">Katalog</span>
                    <h1 class="section-title">{{ $selectedToko->nama_toko ?? 'Hijabkku' }}</h1>
                    <p class="section-desc mx-auto">Temukan koleksi hijab premium terbaik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section class="section border-top" style="padding-top: 60px;">
        <div class="container">
            <!-- Filters -->
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-5">
                    <label class="form-label-sm">Pilih Cabang</label>
                    <select class="form-select select2-toko" id="toko-selector" style="width: 100%;">
                        @foreach ($tokos as $t)
                            <option value="{{ $t->kode }}" {{ $selectedTokoKode == $t->kode ? 'selected' : '' }}>
                                {{ $t->nama_toko }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5">
                    <label class="form-label-sm">Cari Produk</label>
                    <div class="position-relative">
                        <input type="text" id="product-search" class="form-input"
                            style="padding-right: 40px; height: 46px;"
                            placeholder="Ketik nama hijab..." value="{{ request()->query('search') }}">
                        <svg class="position-absolute" style="right: 14px; top: 14px; color: var(--text-light);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                </div>
            </div>

            <div id="dynamic-products-wrapper">
                @if ($stocks->isEmpty())
                    @if (request()->query('search'))
                        <div class="text-center py-5 my-5" style="padding: 80px 40px;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <h3 class="section-title mt-4" style="font-size: 1.6rem;">Tidak Ditemukan</h3>
                            <p style="font-size: 0.92rem; color: var(--text-sub); margin-bottom: 24px;">Tidak ada produk dengan kata kunci "{{ request()->query('search') }}".</p>
                            <button onclick="clearSearch()" class="btn-primary px-4 py-2">Lihat Semua</button>
                        </div>
                    @else
                        <div class="text-center py-5 my-5" style="padding: 80px 40px;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <h3 class="section-title mt-4" style="font-size: 1.6rem;">Stok Kosong</h3>
                            <p style="font-size: 0.92rem; color: var(--text-sub); margin-bottom: 24px;">Persediaan di cabang {{ $selectedToko->nama_toko ?? '' }} sedang kosong.</p>
                            <a href="https://wa.me/6282280783843" target="_blank" class="btn-primary px-4 py-2">Hubungi Kami</a>
                        </div>
                    @endif
                @else
                    <div class="row g-4" id="products-grid">
                        @foreach ($stocks as $stock)
                            @if ($stock->data_barang)
                                @php
                                    $itemName = strtolower($stock->data_barang->nama_barang ?? '');
                                    $fallbackImg = '/img/product_1.png';
                                    $tag = '';
                                    $tagColor = 'var(--accent)';

                                    if (str_contains($itemName, 'bella') || str_contains($itemName, 'square')) {
                                        $fallbackImg = '/img/product_2.png';
                                        $tag = 'Best Seller';
                                        $tagColor = 'var(--accent)';
                                    } elseif (str_contains($itemName, 'khimar') || str_contains($itemName, 'syari') || str_contains($itemName, 'syar\'i')) {
                                        $fallbackImg = '/img/product_3.png';
                                        $tag = 'Premium';
                                        $tagColor = 'var(--rose)';
                                    } elseif (str_contains($itemName, 'paris')) {
                                        $fallbackImg = '/img/product_4.png';
                                        $tag = 'New';
                                        $tagColor = '#8B9E8B';
                                    }

                                    $imgUrl = $stock->data_barang->foto ? '/' . $stock->data_barang->foto : $fallbackImg;

                                    $verifiedPhotos = $stock->data_barang->fotos
                                        ->where('is_verified', true)
                                        ->map(function($f) { return '/' . $f->path; })
                                        ->values()
                                        ->toArray();

                                    if (empty($verifiedPhotos)) {
                                        $verifiedPhotos = [$imgUrl];
                                    }

                                    $avail = $stock->jumlah - $stock->terjual;
                                    if ($avail <= 0) {
                                        $tag = 'Habis';
                                        $tagColor = 'var(--text-light)';
                                    }
                                @endphp

                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="catalog-card h-100 {{ $avail <= 0 ? 'out-of-stock' : '' }} product-detail-trigger"
                                        style="cursor: pointer;"
                                        data-kode="{{ $stock->kode_barang }}"
                                        data-nama="{{ $stock->data_barang->nama_barang ?? 'Hijab' }}"
                                        data-harga="{{ (int) str_replace('.', '', $stock->data_barang->harga_jual ?? '0') }}"
                                        data-img="{{ $imgUrl }}"
                                        data-images="{{ json_encode($verifiedPhotos) }}"
                                        data-avail="{{ $avail }}"
                                        data-category="{{ $stock->data_barang->jenis_barang ?? 'Hijab' }}"
                                        data-tag="{{ $tag }}"
                                        data-desc="{{ $stock->data_barang->deskripsi ?? '' }}">
                                        @if($tag)
                                            <div class="catalog-card-tag" style="background: {{ $tagColor }};">{{ $tag }}</div>
                                        @endif
                                        <div class="catalog-card-img">
                                            <img src="{{ $imgUrl }}" alt="{{ $stock->data_barang->nama_barang ?? 'Hijab' }}">
                                            <div class="catalog-card-overlay">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </div>
                                        </div>
                                        <div class="catalog-card-body">
                                            <h5 class="catalog-card-name">{{ $stock->data_barang->nama_barang ?? 'Hijab' }}</h5>
                                            <p class="catalog-card-price">Rp {{ number_format((int) str_replace('.', '', $stock->data_barang->harga_jual ?? '0'), 0, ',', '.') }}</p>
                                            @if ($avail > 0)
                                                <span class="catalog-card-stock d-none">Tersedia: {{ $avail }} pcs</span>
                                            @else
                                                <span class="catalog-card-stock" style="color: var(--accent);">Stok Habis</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($stocks->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            <nav aria-label="Pagination" class="d-flex align-items-center p-1" style="border: 1px solid var(--border); border-radius: 2px; gap: 2px;">
                                @if ($stocks->onFirstPage())
                                    <span class="d-inline-flex align-items-center justify-content-center disabled" style="width: 36px; height: 36px; color: var(--text-light); opacity: 0.4;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    </span>
                                @else
                                    <a href="{{ $stocks->previousPageUrl() }}" class="d-inline-flex align-items-center justify-content-center text-decoration-none" style="width: 36px; height: 36px; color: var(--text-sub); transition: all 0.2s ease;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                                    </a>
                                @endif

                                @foreach ($stocks->links()->elements as $element)
                                    @if (is_string($element))
                                        <span class="d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; color: var(--text-light); font-size: 0.82rem;">{{ $element }}</span>
                                    @endif
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $stocks->currentPage())
                                                <span class="d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: var(--accent); color: white; font-size: 0.82rem;">{{ $page }}</span>
                                            @else
                                                <a href="{{ $url }}" class="d-inline-flex align-items-center justify-content-center text-decoration-none" style="width: 36px; height: 36px; color: var(--text-sub); font-size: 0.82rem; transition: all 0.2s ease;">{{ $page }}</a>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @if ($stocks->hasMorePages())
                                    <a href="{{ $stocks->nextPageUrl() }}" class="d-inline-flex align-items-center justify-content-center text-decoration-none" style="width: 36px; height: 36px; color: var(--text-sub); transition: all 0.2s ease;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </a>
                                @else
                                    <span class="d-inline-flex align-items-center justify-content-center disabled" style="width: 36px; height: 36px; color: var(--text-light); opacity: 0.4;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <!-- Policies -->
    <section class="section" style="background: var(--bg-warm); border-top: 1px solid var(--border-light);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Kebijakan</span>
                <h2 class="section-title">Ketentuan Layanan</h2>
                <div class="section-line mx-auto"></div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card-clean h-100">
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.92rem; font-weight: 500; color: var(--text); margin-bottom: 16px;">Syarat & Ketentuan</h5>
                        <p style="font-size: 0.85rem; color: var(--text-sub); line-height: 1.7;">
                            1. Pemesanan melalui website dengan metode Store Pickup.<br>
                            2. Pembayaran via QRIS, Virtual Account, atau E-Wallet.<br>
                            3. Harga yang tertera adalah harga final.
                        </p>
                        <a href="{{ route('terms') }}" class="text-accent mt-3 d-inline-block" style="font-size: 0.82rem; font-weight: 500; text-decoration: none;">
                            Baca selengkapnya &rarr;
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-clean h-100">
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.92rem; font-weight: 500; color: var(--text); margin-bottom: 16px;">Kebijakan Refund</h5>
                        <p style="font-size: 0.85rem; color: var(--text-sub); line-height: 1.7;">
                            1. Retur maksimal 2x24 jam dengan video unboxing.<br>
                            2. Barang harus dalam kondisi belum dicuci.<br>
                            3. Refund 100% jika stok pengganti kosong.
                        </p>
                        <a href="{{ route('refund-policy') }}" class="text-accent mt-3 d-inline-block" style="font-size: 0.82rem; font-weight: 500; text-decoration: none;">
                            Baca selengkapnya &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Detail Modal -->
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 4px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-md-6 p-4" style="background: var(--bg-warm); border-right: 1px solid var(--border);">
                        <div class="w-100 mb-3" style="aspect-ratio: 4/5; overflow: hidden;">
                            <img id="modal-product-img" src="" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div id="modal-product-thumbnails" class="d-flex justify-content-center gap-2"></div>
                    </div>
                    <div class="col-md-6 p-4 p-md-5 d-flex flex-column justify-content-between" style="background: var(--bg-card);">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span id="modal-product-badge" class="catalog-card-tag position-static"></span>
                                <span style="font-size: 0.78rem; color: var(--text-light);"><span id="modal-product-category"></span></span>
                            </div>
                            <h3 id="modal-product-title" style="font-size: 1.4rem; color: var(--text); margin-bottom: 8px;"></h3>
                            <h4 id="modal-product-price" style="font-family: 'DM Serif Display', serif; font-size: 1.2rem; color: var(--accent); margin-bottom: 24px;"></h4>

                            <div class="p-3 mb-4" style="background: var(--bg-warm); border: 1px solid var(--border-light);">
                                <span style="font-size: 0.78rem; color: var(--text-light);">Stok: <strong id="modal-product-stock" style="color: var(--text);"></strong></span>
                            </div>

                            <div class="pt-3 mb-4" style="border-top: 1px solid var(--border-light);">
                                <h6 style="font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">Deskripsi</h6>
                                <p id="modal-product-desc" style="font-size: 0.88rem; color: var(--text-sub); line-height: 1.7; margin: 0;"></p>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge" style="background: var(--bg-warm); color: var(--text-sub); border: 1px solid var(--border); font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 400; padding: 6px 12px;">Adem & Nyaman</span>
                                <span class="badge" style="background: var(--bg-warm); color: var(--text-sub); border: 1px solid var(--border); font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 400; padding: 6px 12px;">Mudah Diatur</span>
                                <span class="badge" style="background: var(--bg-warm); color: var(--text-sub); border: 1px solid var(--border); font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 400; padding: 6px 12px;">Premium</span>
                            </div>
                        </div>
                        <div id="modal-action-wrapper" class="mt-4"></div>
                    </div>
                </div>
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close"
                    style="top: 16px; right: 16px; z-index: 10; background: var(--bg-card); border: 1px solid var(--border); padding: 10px; border-radius: 50%;"></button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup Acorn Icons
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }

            // Initialize cart from localStorage
            const savedCart = localStorage.getItem('hijabkku_cart');
            const savedToko = localStorage.getItem('hijabkku_cart_toko');
            const tokoEl = document.getElementById('toko-selector');
            const currentToko = tokoEl ? tokoEl.value : '';

            if (savedCart && savedToko === currentToko) {
                cart = JSON.parse(savedCart);
                updateGlobalCartUI();
            } else {
                cart = [];
                localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                localStorage.setItem('hijabkku_cart_toko', currentToko);
                updateGlobalCartUI();
            }

            updatePickupStoreLabel();

            // Initialize Select2 for store selector
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                const $select2 = $('.select2-toko').select2();

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

                            window.history.pushState({ path: url }, '', url);

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
                    cart = [];
                    localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                    localStorage.setItem('hijabkku_cart_toko', selectedToko);
                    updateCartUI();
                    updatePickupStoreLabel();
                    performSearch();
                });

                let searchTimeout;

                function performSearch() {
                    clearTimeout(searchTimeout);
                    const query = $('#product-search').val().trim();
                    const selectedToko = $('#toko-selector').val() || '';
                    const url = `?toko=${selectedToko}&search=${encodeURIComponent(query)}`;
                    loadCatalog(url);
                }

                $('#product-search').on('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(performSearch, 600);
                });

                $('#product-search').on('keypress', function(e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        performSearch();
                    }
                });

                $(document).on('click', '#dynamic-products-wrapper .page-link', function(e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    if (url) {
                        loadCatalog(url);
                    }
                });
            }
        });

        function clearSearch() {
            const searchInput = document.getElementById('product-search');
            if (searchInput) {
                searchInput.value = '';
                $(searchInput).trigger('input');
            }
        }

        function showToast(message, type = 'success') {
            globalShowToast(message, type);
        }

        function closeToast(btn) {
            globalCloseToast(btn);
        }

        function addToCart(kode, name, price, img, maxStock) {
            globalAddToCart(kode, name, price, img, maxStock);
        }

        function removeFromCart(kode) {
            globalRemoveFromCart(kode);
        }

        function changeQty(kode, delta) {
            globalChangeQty(kode, delta);
        }

        function updatePickupStoreLabel() {
            const selectEl = document.getElementById('toko-selector');
            if (selectEl) {
                const storeName = selectEl.options[selectEl.selectedIndex].text;
                const cartStoreEl = document.getElementById('cart-pickup-store');
                const checkoutStoreEl = document.getElementById('checkout-pickup-store');
                if (cartStoreEl) cartStoreEl.innerText = storeName;
                if (checkoutStoreEl) checkoutStoreEl.innerText = storeName;
            }
        }

        function updateCartUI() {
            updateGlobalCartUI();
        }

        function showProductDetail(kode, name, price, img, maxStock, category, tag, imagesJson, rawDesc = '') {
            let images = [];
            try {
                images = JSON.parse(imagesJson);
            } catch (e) {
                images = [img];
            }
            if (!images || images.length === 0) {
                images = [img];
            }

            const mainImgEl = document.getElementById('modal-product-img');
            mainImgEl.src = images[0];
            mainImgEl.alt = name;

            const thumbnailsContainer = document.getElementById('modal-product-thumbnails');
            thumbnailsContainer.innerHTML = '';

            if (images.length > 1) {
                images.forEach((imagePath, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    thumbnailsContainer.innerHTML += `
                        <img src="${imagePath}" class="product-modal-thumbnail ${activeClass}" data-target-src="${imagePath}" alt="Thumb ${index + 1}">
                    `;
                });
                thumbnailsContainer.style.display = 'flex';
            } else {
                thumbnailsContainer.style.display = 'none';
            }

            const badgeEl = document.getElementById('modal-product-badge');
            badgeEl.innerText = tag;
            badgeEl.style.background = tag === 'Best Seller' ? 'var(--accent)' : (tag === 'Premium' ? 'var(--rose)' : (tag === 'New' ? '#8B9E8B' : 'var(--text-light)'));

            document.getElementById('modal-product-category').innerText = category || 'Hijab';
            document.getElementById('modal-product-title').innerText = name;
            document.getElementById('modal-product-price').innerText = 'Rp ' + price.toLocaleString('id-ID');

            const stockEl = document.getElementById('modal-product-stock');
            if (maxStock > 0) {
                stockEl.innerText = maxStock + ' pcs';
                stockEl.style.color = 'var(--text)';
            } else {
                stockEl.innerText = 'Habis';
                stockEl.style.color = 'var(--accent)';
            }

            let desc = '';
            if (rawDesc && rawDesc.trim().length > 0) {
                desc = rawDesc;
            } else {
                const lowerName = name.toLowerCase();
                if (lowerName.includes('bella') || lowerName.includes('square')) {
                    desc = 'Hijab Bella Square premium berbahan double hycon bertekstur lembut, adem, tidak licin, mudah diatur, dan jatuh dengan cantik saat dikenakan.';
                } else if (lowerName.includes('paris')) {
                    desc = 'Hijab Paris premium kualitas terbaik yang tipis namun tetap tegak sempurna di dahi. Memiliki karakteristik bahan yang adem, lembut, tidak menerawang.';
                } else if (lowerName.includes('khimar') || lowerName.includes('syari') || lowerName.includes('syar\'i')) {
                    desc = 'Khimar Syar\'i anggun berdesain menutup dada dengan sempurna. Menggunakan bahan ceruty/crepe premium ganda yang jatuh dengan anggun, adem.';
                } else {
                    desc = 'Hijab eksklusif dari Hijabkku. Menghadirkan kenyamanan maksimal dengan bahan premium pilihan yang lembut di kulit, sejuk, mudah dibentuk.';
                }
            }
            document.getElementById('modal-product-desc').innerHTML = desc;

            const actionWrapper = document.getElementById('modal-action-wrapper');
            if (maxStock > 0) {
                actionWrapper.innerHTML = `
                    <button class="btn-primary w-100 py-3" onclick="addToCartFromModal('${kode}', '${name.replace(/'/g, "\\'")}', ${price}, '${img}', ${maxStock})">
                        Tambahkan ke Keranjang
                    </button>
                `;
            } else {
                actionWrapper.innerHTML = `
                    <button class="btn-secondary w-100 py-3" style="cursor: not-allowed; opacity: 0.5;" disabled>
                        Stok Habis di Cabang Ini
                    </button>
                `;
            }

            const detailModal = new bootstrap.Modal(document.getElementById('productDetailModal'));
            detailModal.show();
        }

        function addToCartFromModal(kode, name, price, img, maxStock) {
            const detailModalEl = document.getElementById('productDetailModal');
            const bsModal = bootstrap.Modal.getInstance(detailModalEl) || new bootstrap.Modal(detailModalEl);
            if (bsModal) {
                bsModal.hide();
            }
            addToCart(kode, name, price, img, maxStock);
        }

        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.product-detail-trigger');
            if (trigger) {
                const kode = trigger.getAttribute('data-kode');
                const name = trigger.getAttribute('data-nama');
                const price = parseInt(trigger.getAttribute('data-harga'), 10);
                const img = trigger.getAttribute('data-img');
                const avail = parseInt(trigger.getAttribute('data-avail'), 10);
                const category = trigger.getAttribute('data-category');
                const tag = trigger.getAttribute('data-tag');
                const images = trigger.getAttribute('data-images') || '[]';
                const desc = trigger.getAttribute('data-desc') || '';

                showProductDetail(kode, name, price, img, avail, category, tag, images, desc);
            }
        });

        document.addEventListener('click', function(e) {
            const thumb = e.target.closest('.product-modal-thumbnail');
            if (thumb) {
                document.querySelectorAll('.product-modal-thumbnail').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
                const targetSrc = thumb.getAttribute('data-target-src');
                const mainImg = document.getElementById('modal-product-img');
                mainImg.style.opacity = '0';
                setTimeout(function() {
                    mainImg.src = targetSrc;
                    mainImg.style.opacity = '1';
                }, 150);
            }
        });

        // Show simulation mode success notification
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('payment_status') === 'simulation') {
                window.history.replaceState({}, document.title, '/catalog');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Simulasi Pembayaran',
                        text: 'Pembayaran berhasil disimulasikan! (Mode simulasi aktif — stok tidak dikurangi)',
                        icon: 'info',
                        confirmButtonColor: 'var(--accent)',
                        confirmButtonText: 'OK'
                    });
                }
            } else if (urlParams.get('payment_status') === 'failure') {
                window.history.replaceState({}, document.title, '/catalog');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Pembayaran Gagal',
                        text: 'Pembayaran tidak berhasil. Silakan coba lagi.',
                        icon: 'error',
                        confirmButtonColor: 'var(--accent)',
                        confirmButtonText: 'OK'
                    });
                }
            }
        })();
    </script>
@endsection
