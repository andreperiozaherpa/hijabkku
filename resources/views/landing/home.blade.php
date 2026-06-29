@extends('landing.app')

@section('title', 'Hijabkku - Elegansi dalam Berhijab')

@section('content')
    <!-- Hero Section -->
    <section style="padding: 30px 0 120px; background: var(--bg);">
        <div class="container">
            <div class="row align-items-center g-5">
                <!-- Left: Text Content -->
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="section-label">Koleksi Hijab Premium</span>
                    <h1 class="section-title" style="font-size: 3.4rem; line-height: 1.1; margin-bottom: 24px;">
                        Tampil Anggun,<br>Lebih <span style="color: var(--accent);">Percaya Diri</span>
                    </h1>
                    <p class="section-desc" style="margin-bottom: 40px; max-width: 440px;">
                        Koleksi hijab dengan berbagai varian, didesain khusus untuk menemani setiap momen spesial di hari-harimu.
                    </p>
                    <div class="d-flex flex-wrap gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('catalog') }}" class="btn-primary">
                            Lihat Katalog
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('contact') }}" class="btn-secondary">Hubungi Kami</a>
                    </div>
                </div>

                <!-- Right: Hero Carousel -->
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                            <div class="carousel-inner" style="aspect-ratio: 4/5; overflow: hidden;">
                                <div class="carousel-item active">
                                    <img src="/img/hero.jpg" alt="Hijabkku Collection 1" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="carousel-item">
                                    <img src="/img/hero-2.jpg" alt="Hijabkku Collection 2" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="carousel-item">
                                    <img src="/img/hero-3.jpg" alt="Hijabkku Collection 3" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>

                        <!-- Floating Cards -->
                        <div class="d-none d-md-block" style="position: absolute; bottom: 24px; left: -24px; background: var(--bg-card); border: 1px solid var(--border); padding: 20px 28px; z-index: 10;">
                            <div style="font-size: 2rem; font-family: 'DM Serif Display', serif; color: var(--accent); line-height: 1;">10k+</div>
                            <div style="font-size: 0.78rem; color: var(--text-light); margin-top: 4px;">Pelanggan Setia</div>
                        </div>
                        <div class="d-none d-md-block" style="position: absolute; top: 24px; right: -24px; background: var(--bg-card); border: 1px solid var(--border); padding: 16px 24px; z-index: 10;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--accent-light); display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <div>
                                    <div style="font-size: 0.82rem; font-weight: 600; color: var(--text);">Premium Quality</div>
                                    <div style="font-size: 0.72rem; color: var(--text-light);">Bahan katun pilihan</div>
                                </div>
                            </div>
                        </div>

                        <!-- Carousel Indicators -->
                        <div style="position: absolute; bottom: -30px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" style="width: 8px; height: 8px; border-radius: 50%; border: 1px solid var(--accent); background: var(--accent); cursor: pointer;"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" style="width: 8px; height: 8px; border-radius: 50%; border: 1px solid var(--border); background: transparent; cursor: pointer;"></button>
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" style="width: 8px; height: 8px; border-radius: 50%; border: 1px solid var(--border); background: transparent; cursor: pointer;"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Signals -->
    <section class="section-sm" style="background: var(--bg-warm); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="card-icon mx-auto">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 8px;">Bahan Premium</h5>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin: 0;">Katun pilihan yang adem dan nyaman.</p>
                </div>
                <div class="col-md-4">
                    <div class="card-icon mx-auto">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 8px;">Pembayaran Aman</h5>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin: 0;">QRIS, E-Wallet, dan VA.</p>
                </div>
                <div class="col-md-4">
                    <div class="card-icon mx-auto">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 8px;">Store Pickup</h5>
                    <p style="font-size: 0.85rem; color: var(--text-sub); margin: 0;">Ambil langsung di outlet.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-label">Favorit Pelanggan</span>
                <h2 class="section-title">Koleksi Terlaris</h2>
                <div class="section-line mx-auto"></div>
            </div>

            <div class="row g-4">
                @forelse ($featuredProducts as $index => $product)
                    @php
                        $itemName = strtolower($product->data_barang->nama_barang ?? '');
                        $imgUrl = '/img/product_1.png';
                        $tag = '';
                        $tagColor = '';

                        if (str_contains($itemName, 'bella') || str_contains($itemName, 'square')) {
                            $imgUrl = '/img/product_2.png';
                            $tag = 'Best Seller';
                            $tagColor = 'var(--accent)';
                        } elseif (str_contains($itemName, 'khimar') || str_contains($itemName, 'syari') || str_contains($itemName, 'syar\'i')) {
                            $imgUrl = '/img/product_3.png';
                            $tag = 'Premium';
                            $tagColor = 'var(--rose)';
                        } elseif (str_contains($itemName, 'paris')) {
                            $imgUrl = '/img/product_4.png';
                            $tag = 'New';
                            $tagColor = '#8B9E8B';
                        }
                    @endphp
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-card-img">
                                @if($tag)
                                    <span class="product-card-tag" style="background: {{ $tagColor }};">{{ $tag }}</span>
                                @endif
                                <img src="{{ $imgUrl }}" alt="{{ $product->data_barang->nama_barang ?? 'Hijab' }}">
                                <div class="product-card-overlay">
                                    <button class="product-card-overlay-btn" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button class="product-card-overlay-btn" title="Tambah ke Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('catalog', ['search' => $product->data_barang->nama_barang]) }}" class="product-card-name" style="text-decoration: none;">{{ $product->data_barang->nama_barang ?? 'Hijab Premium' }}</a>
                                <p class="product-card-price">Rp {{ number_format(floatval(str_replace('.', '', $product->data_barang->harga_jual ?? '0')), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-card-img">
                                <span class="product-card-tag" style="background: var(--accent);">Best Seller</span>
                                <img src="/img/product_1.png" alt="Pashmina Silk Gold">
                                <div class="product-card-overlay">
                                    <button class="product-card-overlay-btn" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button class="product-card-overlay-btn" title="Tambah ke Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('catalog') }}" class="product-card-name" style="text-decoration: none;">Pashmina Silk Gold</a>
                                <p class="product-card-price">Rp 85.000</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-card-img">
                                <span class="product-card-tag" style="background: #8B9E8B;">New</span>
                                <img src="/img/product_2.png" alt="Bella Square Pink">
                                <div class="product-card-overlay">
                                    <button class="product-card-overlay-btn" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button class="product-card-overlay-btn" title="Tambah ke Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('catalog') }}" class="product-card-name" style="text-decoration: none;">Bella Square Pink</a>
                                <p class="product-card-price">Rp 45.000</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="product-card">
                            <div class="product-card-img">
                                <img src="/img/product_3.png" alt="Khimar Syar'i Rose">
                                <div class="product-card-overlay">
                                    <button class="product-card-overlay-btn" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button class="product-card-overlay-btn" title="Tambah ke Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('catalog') }}" class="product-card-name" style="text-decoration: none;">Khimar Syar'i Rose</a>
                                <p class="product-card-price">Rp 120.000</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-3 d-none d-md-block">
                        <div class="product-card">
                            <div class="product-card-img">
                                <span class="product-card-tag" style="background: var(--rose);">Premium</span>
                                <img src="/img/product_4.png" alt="Pashmina Luxury">
                                <div class="product-card-overlay">
                                    <button class="product-card-overlay-btn" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    <button class="product-card-overlay-btn" title="Tambah ke Wishlist">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="product-card-body">
                                <a href="{{ route('catalog') }}" class="product-card-name" style="text-decoration: none;">Pashmina Luxury</a>
                                <p class="product-card-price">Rp 95.000</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('catalog') }}" class="btn-primary">
                    Lihat Semua Produk
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section" style="background: var(--bg-warm); border-top: 1px solid var(--border-light);">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 order-2 order-lg-1">
                    <div class="row g-3">
                        <div class="col-6">
                            <div style="height: 260px; overflow: hidden; border: 1px solid var(--border);">
                                <img src="/img/product_1.png" alt="Hijab" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="height: 180px; overflow: hidden; margin-top: 12px; border: 1px solid var(--border);">
                                <img src="/img/product_2.png" alt="Hijab" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-6" style="padding-top: 48px;">
                            <div style="height: 200px; overflow: hidden; border: 1px solid var(--border);">
                                <img src="/img/product_3.png" alt="Hijab" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div style="height: 240px; overflow: hidden; margin-top: 12px; border: 1px solid var(--border);">
                                <img src="/img/product_4.png" alt="Hijab" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2 mb-4 mb-lg-0">
                    <span class="section-label">Cerita Kami</span>
                    <h2 class="section-title">Lebih dari sekadar Hijab</h2>
                    <div class="section-line"></div>
                    <p style="font-size: 0.95rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 16px;">
                        Berawal dari keinginan untuk memberikan pilihan hijab yang nyaman namun tetap elegan, Hijabkku hadir dengan dedikasi pada kualitas bahan terbaik.
                    </p>
                    <p style="font-size: 0.95rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 32px;">
                        Setiap helai hijab kami dikurasi dengan cinta untuk memastikan Anda tampil percaya diri setiap saat.
                    </p>
                    <a href="{{ route('about') }}" class="btn-secondary">
                        Selengkapnya
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Location Section -->
    <section class="section border-top">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-5">
                    <span class="section-label">Outlet Kami</span>
                    <h2 class="section-title">Kunjungi Toko Terdekat</h2>
                    <div class="section-line"></div>
                    <p style="font-size: 0.95rem; color: var(--text-sub); line-height: 1.8; margin-bottom: 32px;">
                        Ambil pesanan langsung di outlet utama kami atau beli langsung di tempat.
                    </p>
                    <a href="{{ route('contact') }}" class="btn-secondary">
                        Lihat Alamat
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="col-lg-7">
                    <div class="card-clean">
                        <h5 style="font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 500; color: var(--text); margin-bottom: 24px;">Informasi Usaha</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start py-3" style="border-bottom: 1px solid var(--border-light);">
                                <span class="text-accent me-3 mt-1">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                </span>
                                <span style="font-size: 0.88rem; color: var(--text-sub); line-height: 1.6;">Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat, Lampung</span>
                            </li>
                            <li class="d-flex align-items-center py-3" style="border-bottom: 1px solid var(--border-light);">
                                <span class="text-accent me-3">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </span>
                                <a href="mailto:andreperiozaherpa@gmail.com" style="font-size: 0.88rem; color: var(--text-sub); text-decoration: none;">andreperiozaherpa@gmail.com</a>
                            </li>
                            <li class="d-flex align-items-center py-3" style="border-bottom: 1px solid var(--border-light);">
                                <span class="text-accent me-3">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                                </span>
                                <a href="https://wa.me/6282280783843" target="_blank" style="font-size: 0.88rem; color: var(--text-sub); text-decoration: none;">0822 8078 3843</a>
                            </li>
                            <li class="d-flex align-items-center py-3">
                                <span class="text-accent me-3">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                </span>
                                <span style="font-size: 0.88rem; color: var(--text-sub);">Senin - Sabtu, 08.00 - 20.00 WIB</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof AcornIcons !== 'undefined') {
                new AcornIcons().replace();
            }

            // Update carousel indicators
            var heroCarousel = document.getElementById('heroCarousel');
            if (heroCarousel) {
                var indicators = heroCarousel.parentElement.querySelectorAll('[data-bs-slide-to]');
                heroCarousel.addEventListener('slide.bs.carousel', function(e) {
                    indicators.forEach(function(ind) {
                        ind.style.background = 'transparent';
                        ind.style.borderColor = 'var(--border)';
                    });
                    var activeInd = heroCarousel.parentElement.querySelector('[data-bs-slide-to="' + e.to + '"]');
                    if (activeInd) {
                        activeInd.style.background = 'var(--accent)';
                        activeInd.style.borderColor = 'var(--accent)';
                    }
                });
            }
        });
    </script>
@endsection
