@extends('landing.app')

@section('title', 'Hijabkku - Elegansi dalam Berhijab')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 pe-lg-5">
                    <h1 class="display-4 fw-bold mb-4 hero-title">Tampil Anggun, <br>Lebih <span>Percaya Diri</span></h1>
                    <p class="lead mb-5 text-muted" style="font-size: 1.1rem; line-height: 1.8;">
                        Temukan koleksi hijab premium dengan sentuhan warna pastel yang lembut,
                        didesain khusus untuk menemani setiap momen spesial di hari-harimu.
                    </p>
                    <a href="{{ route('catalog') }}" class="btn btn-gold shadow-sm me-3">Lihat Koleksi Terbaru</a>
                    <a href="https://instagram.com/hijabkku" target="_blank"
                        class="btn btn-outline-dark rounded-pill px-4 py-2 mt-3 mt-sm-0">
                        Follow Instagram
                    </a>
                </div>
                <div class="col-lg-6 position-relative">
                    <!-- Decorative background element -->
                    <div class="position-absolute top-50 start-50 translate-middle rounded-circle"
                        style="width: 450px; height: 450px; background: rgba(255,255,255,0.4); filter: blur(40px); z-index: -1;">
                    </div>

                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-lg position-relative overflow-hidden"
                        style="width: 420px; height: 420px; border: 15px solid #fff0f5;">
                        <img src="/img/logo.png" alt="Hijabku Logo"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section id="produk" class="py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="text-gold fw-bold tracking-wide text-uppercase"
                    style="letter-spacing: 2px; font-size: 0.9rem;">Favorit Pelanggan</span>
                <h2 class="fw-bold mt-2 mb-3 display-6" style="color: #2c2c2c;">Koleksi Terlaris</h2>
                <div class="mx-auto" style="width: 60px; height: 3px; background-color: #D4AF37;"></div>
            </div>

            <div class="row g-5">
                <!-- Product 1 -->
                <div class="col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="badge-tag" style="position: absolute; top: 15px; left: 15px; background-color: #D4AF37; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; z-index: 2;">BEST SELLER</div>
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_1.png" alt="Pashmina Silk Gold"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif" style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                    Pashmina Silk Gold</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 85.000</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Product 2 -->
                <div class="col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="badge-tag" style="position: absolute; top: 15px; left: 15px; background-color: #ffb6c1; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; z-index: 2;">NEW</div>
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_2.png" alt="Bella Square Pink Pastel"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif" style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                    Bella Square Pink Pastel</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 45.000</p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Product 3 -->
                <div class="col-md-4">
                    <a href="{{ route('catalog') }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 position-relative">
                            <div class="product-img overflow-hidden">
                                <img src="/img/product_3.png" alt="Khimar Syar'i Rose"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h5 class="fw-bold mb-1 product-title font-serif" style="font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                    Khimar Syar'i Rose</h5>
                                <p class="text-muted mb-0 mt-auto" style="font-size: 0.95rem;">Rp 120.000</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="text-center mt-5 pt-4">
                <a href="https://instagram.com/hijabkku" target="_blank"
                    class="btn btn-outline-dark rounded-pill px-5 py-3 fw-bold">
                    <i data-acorn-icon="instagram" class="me-2"></i>Katalog Lengkap di Instagram
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 order-2 order-lg-1">
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="rounded mb-2 overflow-hidden shadow-sm" style="height: 200px;">
                                <img src="/img/product_1.png" alt="Hijab Premium 1" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="rounded overflow-hidden shadow-sm" style="height: 150px;">
                                <img src="/img/product_2.png" alt="Hijab Premium 2" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-6 mt-4">
                            <div class="rounded mb-2 overflow-hidden shadow-sm" style="height: 180px;">
                                <img src="/img/product_3.png" alt="Hijab Premium 3" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="rounded overflow-hidden shadow-sm" style="height: 220px;">
                                <img src="/img/product_4.png" alt="Hijab Premium 4" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 order-1 order-lg-2 mb-5 mb-lg-0">
                    <span class="text-gold fw-bold tracking-wide text-uppercase"
                        style="letter-spacing: 2px; font-size: 0.9rem;">Cerita Kami</span>
                    <h2 class="fw-bold mt-2 mb-4 display-6" style="color: #2c2c2c;">Lebih dari sekadar Hijab</h2>
                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        Berawal dari keinginan untuk memberikan pilihan hijab yang nyaman namun tetap elegan,
                        Hijabkku hadir dengan dedikasi pada kualitas bahan dan perpaduan warna pastel yang manis.
                    </p>
                    <p class="text-muted mb-5" style="line-height: 1.8;">
                        Setiap helai hijab kami dikurasi dengan cinta untuk memastikan Anda tampil percaya diri
                        setiap saat. Karena kecantikan sejati berasal dari hati yang nyaman.
                    </p>
                    <div class="d-flex align-items-center">
                        <div class="me-4 text-center">
                            <h3 class="fw-bold text-gold mb-0">10k+</h3>
                            <span class="small text-muted">Pelanggan Setia</span>
                        </div>
                        <div class="text-center">
                            <h3 class="fw-bold text-gold mb-0">50+</h3>
                            <span class="small text-muted">Varian Warna</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof acorn !== 'undefined' && acorn.Icons) {
                acorn.Icons.replace();
            }
        });
    </script>
@endsection
