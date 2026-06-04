<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Hijabkku - Elegansi dalam Berhijab')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Hijabkku menyediakan koleksi hijab premium terbaik dengan warna pastel lembut untuk wanita Muslimah anggun. Dapatkan pashmina silk, Bella Square, dan khimar premium di outlet terdekat kami." />
    <meta name="keywords" content="hijab, pashmina, bella square, khimar, hijab premium, hijab pastel, hijabkku" />
    <meta name="author" content="Hijabkku" />
    <meta property="og:title" content="Hijabkku - Elegansi dalam Berhijab" />
    <meta property="og:description" content="Koleksi hijab premium dengan sentuhan warna pastel yang lembut, didesain khusus untuk menemani setiap momen spesial Anda." />
    <meta property="og:image" content="/img/logo.png" />
    <meta property="og:type" content="website" />
    @yield('meta')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/img/logo.png" />
    <link rel="apple-touch-icon" href="/img/logo.png" />

    <!-- Font Tags Start -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="/vendor/acorn/font/CS-Interface/style.css" />

    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="/vendor/acorn/css/vendor/bootstrap.min.css" />

    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="/vendor/acorn/css/styles.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/main.css" />

    <!-- Select2 CSS (Shared if needed) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #ffffff;
            color: #2c2c2c;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #f0f0f0;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #D4AF37 !important;
            /* Pastel gold */
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: #D4AF37 !important;
        }

        .nav-link-cart {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: #2c2c2c !important;
            transition: all 0.2s ease-in-out;
        }
        
        .nav-link-cart:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: #D4AF37 !important;
        }

        .cart-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: #e05260;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            pointer-events: none; /* Make sure clicking badge clicks button */
            z-index: 1;
        }

        .text-gold {
            color: #D4AF37;
        }

        .btn-gold {
            background-color: #D4AF37;
            color: white;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-gold:hover {
            background-color: #b5952f;
            color: white;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }

        .btn-outline-gold {
            border: 2px solid #D4AF37;
            color: #D4AF37;
            background-color: transparent;
            font-weight: 600;
            border-radius: 50px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-gold:hover {
            background-color: #D4AF37;
            color: white;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
        }

        .product-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.03);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }

        .product-img {
            height: 320px;
            background: linear-gradient(45deg, #fce4ec, #fff0f5);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffb6c1;
        }

        /* Hero Section style definition */
        .hero-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
            min-height: 500px;
            display: flex;
            align-items: center;
        }

        .hero-title span {
            color: #D4AF37;
        }

        .footer {
            background-color: #faf9f6;
            padding: 70px 0 40px 0;
            margin-top: 80px;
            border-top: 1px solid #f0eee9;
        }

        .hover-gold:hover {
            color: #D4AF37 !important;
            transition: color 0.2s ease-in-out;
        }

        /* Custom Select2 Styling */
        .select2-container--default .select2-selection--single {
            border: 2px solid #ffe4e1 !important;
            border-radius: 50px !important;
            height: 50px !important;
            padding: 10px 20px !important;
            background-color: #fff !important;
            transition: all 0.3s ease;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #D4AF37 !important;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.15) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #555 !important;
            font-weight: 600 !important;
            line-height: 26px !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 15px !important;
        }

        .select2-dropdown {
            border: 1px solid #ffe4e1 !important;
            border-radius: 15px !important;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #D4AF37 !important;
            color: white !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #ffd1dc !important;
            color: #2c2c2c !important;
        }

        /* Custom Pagination Styling */
        .pagination .page-link {
            color: #D4AF37;
            border-color: #ffe4e1;
            background-color: #fff;
            padding: 10px 18px;
            font-weight: 600;
            border-radius: 50px;
            margin: 0 3px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            color: white;
            background-color: #D4AF37;
            border-color: #D4AF37;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2);
        }

        .pagination .page-item.active .page-link {
            background-color: #D4AF37;
            border-color: #D4AF37;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            border-color: #ffe4e1;
            color: #ccc;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 50px !important;
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/img/logo.png" alt="Hijabku Logo" style="height: 40px; border-radius: 5px;" class="me-2">
                HIJABKU
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ Request::is('/') ? 'active text-gold' : '' }}"
                            href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ Request::is('catalog') ? 'active text-gold' : '' }}"
                            href="/catalog">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ Request::is('about') ? 'active text-gold' : '' }}" 
                            href="{{ route('about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ Request::is('contact') ? 'active text-gold' : '' }}" 
                            href="{{ route('contact') }}">Hubungi Kami</a>
                    </li>
                    @if (request()->routeIs('catalog'))
                        <li class="nav-item me-lg-3 mt-3 mt-lg-0 d-flex align-items-center">
                            <button class="nav-link nav-link-cart position-relative p-0 border-0 bg-transparent" data-bs-toggle="offcanvas"
                                data-bs-target="#cartOffcanvas"
                                title="Keranjang Belanja"
                                style="cursor: pointer; outline: none; box-shadow: none;">
                                <i data-acorn-icon="cart" style="font-size: 20px; position: relative; z-index: 2;"></i>
                                <span id="cart-badge" class="cart-badge">0</span>
                            </button>
                        </li>
                    @endif
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-outline-secondary rounded-pill px-4" style="border-color: #dcdcdc;"
                            href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4 mb-5 text-center text-md-start">
                <!-- Column 1: Brand Info -->
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a class="navbar-brand d-flex align-items-center justify-content-center justify-content-md-start mb-3" href="/">
                        <img src="/img/logo.png" alt="Hijabku Logo" style="height: 40px; border-radius: 5px;" class="me-2">
                        HIJABKU
                    </a>
                    <p class="text-muted pe-lg-4 small" style="line-height: 1.6;">
                        Menebar kebaikan dan kecantikan melalui balutan hijab yang elegan dengan sentuhan warna pastel lembut terbaik.
                    </p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-2 mt-3">
                        <a href="https://instagram.com/hijabkku" target="_blank" class="btn btn-outline-dark btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i data-acorn-icon="instagram" style="font-size: 16px;"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-bold text-dark font-serif mb-4" style="letter-spacing: 0.5px;">Tautan Cepat</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="/" class="text-muted text-decoration-none hover-gold small">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('catalog') }}" class="text-muted text-decoration-none hover-gold small">Katalog Hijab</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none hover-gold small">Tentang Kami</a></li>
                        <li class="mb-0"><a href="{{ route('contact') }}" class="text-muted text-decoration-none hover-gold small">Hubungi Kami</a></li>
                    </ul>
                </div>

                <!-- Column 3: Legal/Policies (KYC Compliant) -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-bold text-dark font-serif mb-4" style="letter-spacing: 0.5px;">Kebijakan Usaha</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('privacy-policy') }}" class="text-muted text-decoration-none hover-gold small">Kebijakan Privasi</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-muted text-decoration-none hover-gold small">Syarat & Ketentuan</a></li>
                        <li class="mb-0"><a href="{{ route('refund-policy') }}" class="text-muted text-decoration-none hover-gold small">Kebijakan Refund</a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact Support Details -->
                <div class="col-md-6 col-lg-4">
                    <h6 class="fw-bold text-dark font-serif mb-4" style="letter-spacing: 0.5px;">Kontak & Jam Operasional</h6>
                    <ul class="list-unstyled mb-0 small text-muted">
                        <li class="mb-2 d-flex align-items-start justify-content-center justify-content-md-start">
                            <span class="me-2 text-gold"><i data-acorn-icon="shop" style="width: 14px; height: 14px;"></i></span>
                            <span>Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah, Kab. Tulang Bawang Barat, Lampung (Depan Masjid Baitul Sobur)</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center justify-content-center justify-content-md-start">
                            <span class="me-2 text-gold"><i data-acorn-icon="email" style="width: 14px; height: 14px;"></i></span>
                            <a href="mailto:andreperiozaherpa@gmail.com" class="text-muted text-decoration-none hover-gold">andreperiozaherpa@gmail.com</a>
                        </li>
                        <li class="mb-2 d-flex align-items-center justify-content-center justify-content-md-start">
                            <span class="me-2 text-gold"><i data-acorn-icon="phone" style="width: 14px; height: 14px;"></i></span>
                            <a href="https://wa.me/6282280783843" target="_blank" class="text-muted text-decoration-none hover-gold">0822 8078 3843 (WhatsApp)</a>
                        </li>
                        <li class="d-flex align-items-center justify-content-center justify-content-md-start">
                            <span class="me-2 text-gold"><i data-acorn-icon="clock" style="width: 14px; height: 14px;"></i></span>
                            <span>Senin – Sabtu: 08.00 – 20.00 WIB</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="row border-top pt-4 border-light text-center text-md-start">
                <div class="col-md-6 mb-2 mb-md-0">
                    <p class="small text-muted mb-0">&copy; {{ date('Y') }} Hijabkku. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small text-muted mb-0">
                        <a href="{{ route('login') }}" class="text-muted text-decoration-none hover-gold">Login Sistem POS</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Shared Vendor Scripts -->
    <script src="/vendor/acorn/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/vendor/acorn/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons-interface.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons-commerce.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (request()->routeIs('catalog'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    @yield('scripts')
</body>

</html>
