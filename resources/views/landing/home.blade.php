@extends('landing.app')

@section('title', 'Hijabkku - Elegansi dalam Berhijab')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section py-5 text-center text-lg-start bg-light"
        style="background: linear-gradient(135deg, #ffd1dc 0%, #fff 100%);">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                    <span class="text-gold fw-bold tracking-wide text-uppercase"
                        style="letter-spacing: 2px; font-size: 0.9rem;">KOLEKSI PASTEL PREMIUM</span>
                    <h1 class="display-4 fw-bold mb-4 hero-title mt-2 font-serif text-dark" style="line-height: 1.2;">Tampil
                        Anggun, <br>Lebih <span class="text-gold">Percaya Diri</span></h1>
                    <p class="lead mb-4 text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                        Temukan koleksi hijab premium dengan sentuhan warna pastel yang lembut,
                        didesain khusus untuk menemani setiap momen spesial di hari-harimu.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                        <a href="{{ route('catalog') }}"
                            class="btn btn-gold shadow-sm px-4 py-3 rounded-pill text-uppercase"
                            style="letter-spacing: 0.5px;">Lihat Katalog</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline-dark rounded-pill px-4 py-3 text-uppercase"
                            style="letter-spacing: 0.5px;">Hubungi Kami</a>
                    </div>
                </div>
                <div class="col-lg-6 position-relative text-center">
                    <!-- Decorative background element -->
                    <div class="position-absolute top-50 start-50 translate-middle rounded-circle d-none d-md-block"
                        style="width: 450px; height: 450px; background: rgba(255,255,255,0.4); filter: blur(40px); z-index: -1;">
                    </div>

                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-lg position-relative overflow-hidden border border-white"
                        style="width: 100%; max-width: 420px; aspect-ratio: 1/1; border-width: 15px !important; border-style: solid; border-color: #fff0f5 !important;">
                        <img src="/img/logo.png" alt="Hijabku Logo" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Signals Section -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="p-3">
                        <span
                            class="d-inline-flex align-items-center justify-content-center bg-light text-gold rounded-circle mb-3"
                            style="width: 50px; height: 50px;">
                            <i data-acorn-icon="check" style="font-size: 20px;"></i>
                        </span>
                        <h5 class="fw-bold font-serif mb-2">Bahan Katun Premium</h5>
                        <p class="text-muted small mb-0">Diproduksi menggunakan bahan katun pilihan yang adem, nyaman
                            dipakai seharian, dan tidak mudah kusut.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <span
                            class="d-inline-flex align-items-center justify-content-center bg-light text-gold rounded-circle mb-3"
                            style="width: 50px; height: 50px;">
                            <i data-acorn-icon="shield" style="font-size: 20px;"></i>
                        </span>
                        <h5 class="fw-bold font-serif mb-2">Pembayaran Aman</h5>
                        <p class="text-muted small mb-0">Mendukung metode pembayaran instan terverifikasi dan aman
                            menggunakan QRIS, E-Wallet, dan VA.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3">
                        <span
                            class="d-inline-flex align-items-center justify-content-center bg-light text-gold rounded-circle mb-3"
                            style="width: 50px; height: 50px;">
                            <i data-acorn-icon="shop" style="font-size: 20px;"></i>
                        </span>
                        <h5 class="fw-bold font-serif mb-2">Sistem Store Pickup</h5>
                        <p class="text-muted small mb-0">Pesan secara online dan ambil pesanan Anda langsung di cabang
                            outlet fisik resmi terdekat kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="produk" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="text-gold fw-bold tracking-wide text-uppercase"
                    style="letter-spacing: 2px; font-size: 0.9rem;">Favorit Pelanggan</span>
                <h2 class="fw-bold mt-2 mb-3 display-6 font-serif text-dark">Koleksi Terlaris</h2>
                <div class="mx-auto" style="width: 60px; height: 3px; background-color: #D4AF37;"></div>
            </div>

            <div class="row g-4">
                <!-- Product 1 -->
                <div class="col-6 col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="badge-tag"
                                style="position: absolute; top: 15px; left: 15px; background-color: #D4AF37; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; z-index: 2;">
                                BEST SELLER</div>
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_1.png" alt="Pashmina Silk Gold"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif text-uppercase"
                                    style="font-size: 1rem; letter-spacing: 1px;">
                                    Pashmina Silk Gold</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 85.000</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Product 2 -->
                <div class="col-6 col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="badge-tag"
                                style="position: absolute; top: 15px; left: 15px; background-color: #ffb6c1; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; z-index: 2;">
                                NEW</div>
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_2.png" alt="Bella Square Pink Pastel"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif text-uppercase"
                                    style="font-size: 1rem; letter-spacing: 1px;">
                                    Bella Square Pink Pastel</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 45.000</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Product 3 -->
                <div class="col-12 col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_3.png" alt="Khimar Syar'i Rose"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif text-uppercase"
                                    style="font-size: 1rem; letter-spacing: 1px;">
                                    Khimar Syar'i Rose</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 120.000</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('catalog') }}"
                    class="btn btn-outline-gold rounded-pill px-5 py-3 fw-bold text-uppercase">
                    Mulai Belanja di Katalog
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-5 bg-white">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 mb-4 mb-lg-0 order-2 order-lg-1">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="rounded mb-2 overflow-hidden shadow-sm" style="height: 200px;">
                                <img src="/img/product_1.png" alt="Hijab Premium 1"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="rounded overflow-hidden shadow-sm" style="height: 150px;">
                                <img src="/img/product_2.png" alt="Hijab Premium 2"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-6 mt-4">
                            <div class="rounded mb-2 overflow-hidden shadow-sm" style="height: 180px;">
                                <img src="/img/product_3.png" alt="Hijab Premium 3"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="rounded overflow-hidden shadow-sm" style="height: 220px;">
                                <img src="/img/product_4.png" alt="Hijab Premium 4"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2 mb-5 mb-lg-0">
                    <span class="text-gold fw-bold tracking-wide text-uppercase"
                        style="letter-spacing: 2px; font-size: 0.9rem;">Cerita Kami</span>
                    <h2 class="fw-bold mt-2 mb-4 display-6 font-serif text-dark">Lebih dari sekadar Hijab</h2>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Berawal dari keinginan untuk memberikan pilihan hijab yang nyaman namun tetap elegan,
                        Hijabkku hadir dengan dedikasi pada kualitas bahan dan perpaduan warna pastel yang manis.
                    </p>
                    <p class="text-muted mb-5" style="line-height: 1.8;">
                        Setiap helai hijab kami dikurasi dengan cinta untuk memastikan Anda tampil percaya diri
                        setiap saat. Karena kecantikan sejati berasal dari hati yang nyaman.
                    </p>
                    <div class="d-flex align-items-center mb-4">
                        {{-- <div class="me-4 text-center">
                            <h3 class="fw-bold text-gold mb-0">10k+</h3>
                            <span class="small text-muted">Pelanggan Setia</span>
                        </div>
                        <div class="text-center">
                            <h3 class="fw-bold text-gold mb-0">50+</h3>
                            <span class="small text-muted">Varian Warna</span>
                        </div> --}}
                    </div>
                    <a href="{{ route('about') }}" class="btn btn-gold px-4 py-2.5 rounded-pill text-uppercase small"
                        style="font-size: 0.8rem; letter-spacing: 0.5px;">Selengkapnya</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Contact / Location Section (KYC Compliance) -->
    <section class="py-5 bg-light border-top">
        <div class="container py-4">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <span class="text-gold fw-bold tracking-wide text-uppercase"
                        style="letter-spacing: 2px; font-size: 0.9rem;">OUTLET FISIK KAMI</span>
                    <h2 class="fw-bold mt-2 mb-3 font-serif text-dark">Kunjungi Toko Terdekat</h2>
                    <p class="text-muted mb-4 pe-lg-4" style="line-height: 1.8;">
                        Ambil pesanan Anda langsung di outlet utama kami setelah melakukan checkout secara online, atau beli
                        langsung di tempat untuk melihat katalog lengkap kami secara langsung.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-2">
                        <a href="{{ route('contact') }}"
                            class="btn btn-outline-gold rounded-pill px-4 py-2.5 text-uppercase small"
                            style="font-size: 0.8rem; letter-spacing: 0.5px;">Lihat Alamat Detail</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm p-4 rounded-4"
                        style="border-radius: 16px; background-color: white;">
                        <h5 class="fw-bold font-serif mb-3 text-dark">Informasi Usaha</h5>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-3 d-flex align-items-start">
                                <span class="me-3 text-gold"><i data-acorn-icon="shop"
                                        style="width: 16px; height: 16px;"></i></span>
                                <span>Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat, Lampung
                                    (Depan Masjid Baitul Sobur)</span>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <span class="me-3 text-gold"><i data-acorn-icon="email"
                                        style="width: 16px; height: 16px;"></i></span>
                                <a href="mailto:andreperiozaherpa@gmail.com"
                                    class="text-muted text-decoration-none hover-gold">andreperiozaherpa@gmail.com</a>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <span class="me-3 text-gold"><i data-acorn-icon="phone"
                                        style="width: 16px; height: 16px;"></i></span>
                                <a href="https://wa.me/6282280783843" target="_blank"
                                    class="text-muted text-decoration-none hover-gold">0822 8078 3843 (WhatsApp CS)</a>
                            </li>
                            <li class="d-flex align-items-center">
                                <span class="me-3 text-gold"><i data-acorn-icon="clock"
                                        style="width: 16px; height: 16px;"></i></span>
                                <span>Operasional: Senin – Minggu, 08.00 – 20.00 WIB</span>
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
        });
    </script>
@endsection
