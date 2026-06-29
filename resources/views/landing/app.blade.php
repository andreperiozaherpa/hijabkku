<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Hijabkku - Elegansi dalam Berhijab')</title>

    <meta name="description" content="Hijabkku menyediakan koleksi hijab premium terbaik untuk wanita Muslimah anggun." />
    <meta name="keywords" content="hijab, pashmina, bella square, khimar, hijab premium, hijabkku" />
    <meta name="author" content="Hijabkku" />
    <meta property="og:title" content="Hijabkku - Elegansi dalam Berhijab" />
    <meta property="og:description" content="Koleksi hijab premium untuk setiap momen spesial Anda." />
    <meta property="og:image" content="/img/logo.png" />
    <meta property="og:type" content="website" />
    @yield('meta')

    <link rel="icon" type="image/png" href="/img/logo.png" />
    <link rel="apple-touch-icon" href="/img/logo.png" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="/vendor/acorn/font/CS-Interface/style.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/styles.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/main.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --bg: #FAF9F7;
            --bg-warm: #F5F3EF;
            --bg-card: #FFFFFF;
            --border: #E6E2DC;
            --border-light: #F0EDE8;
            --text: #1A1A1A;
            --text-sub: #6B6560;
            --text-light: #9C9590;
            --accent: #8B7355;
            --accent-dark: #6D5A43;
            --accent-light: #F0EBE3;
            --rose: #C9A9A6;
            --rose-light: #F2E8E7;
        }

        * { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'DM Serif Display', serif;
            font-weight: 400;
        }

        /* ===== NAVBAR ===== */
        .nav-main {
            background: rgba(250, 249, 247, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-light);
            padding: 0;
            transition: all 0.3s ease;
        }

        .nav-main.scrolled {
            box-shadow: 0 1px 20px rgba(0,0,0,0.03);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-brand img {
            height: 30px;
        }

        .nav-brand-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 2.5px;
            color: var(--text);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            font-size: 0.82rem;
            font-weight: 400;
            color: var(--text-sub);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--text);
            background: var(--accent-light);
        }

        .nav-cart {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 8px;
        }

        .nav-cart:hover {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .nav-cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 18px;
            height: 18px;
            background: var(--accent);
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--bg);
        }

        .nav-login {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--text-sub);
            text-decoration: none;
            padding: 8px 20px;
            border: 1px solid var(--border);
            border-radius: 4px;
            margin-left: 12px;
            transition: all 0.2s ease;
        }

        .nav-login:hover {
            border-color: var(--text);
            color: var(--text);
        }

        /* ===== BUTTONS ===== */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--accent);
            color: white;
            border: none;
            padding: 14px 32px;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.8px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--accent-dark);
            color: white;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            padding: 13px 31px;
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.8px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            border-color: var(--text);
            background: var(--bg-warm);
        }

        /* ===== SECTION ===== */
        .section {
            padding: 100px 0;
        }

        .section-sm {
            padding: 60px 0;
        }

        .section-label {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 12px;
            display: block;
        }

        .section-title {
            font-size: 2.8rem;
            color: var(--text);
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .section-desc {
            font-size: 0.95rem;
            color: var(--text-sub);
            line-height: 1.8;
            max-width: 480px;
        }

        .section-line {
            width: 40px;
            height: 1px;
            background: var(--accent);
            margin: 20px 0;
        }

        /* ===== PRODUCT CARD ===== */
        .product-card {
            background: var(--bg-card);
            overflow: hidden;
            transition: all 0.4s ease;
            text-decoration: none;
            display: block;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
        }

        .product-card-img {
            aspect-ratio: 4/5;
            background: var(--bg-warm);
            overflow: hidden;
            position: relative;
        }

        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }

        .product-card:hover .product-card-img img {
            transform: scale(1.05);
        }

        .product-card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 26, 26, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-card:hover .product-card-overlay {
            opacity: 1;
        }

        .product-card-overlay-btn {
            width: 42px;
            height: 42px;
            background: var(--bg-card);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text);
            transform: translateY(10px);
            opacity: 0;
        }

        .product-card:hover .product-card-overlay-btn {
            transform: translateY(0);
            opacity: 1;
        }

        .product-card:hover .product-card-overlay-btn:nth-child(2) {
            transition-delay: 0.05s;
        }

        .product-card-overlay-btn:hover {
            background: var(--accent);
            color: white;
        }

        .product-card-tag {
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

        .product-card-body {
            padding: 16px 16px 20px;
        }

        .product-card-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 8px;
            letter-spacing: 0.2px;
            line-height: 1.4;
            display: block;
        }

        .product-card-name:hover {
            color: var(--accent);
        }

        .product-card-price {
            font-size: 0.88rem;
            color: var(--accent);
            font-weight: 600;
            margin: 0;
            letter-spacing: 0.3px;
        }

        /* ===== CARD ===== */
        .card-clean {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 36px;
        }

        .card-icon {
            width: 44px;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            margin-bottom: 20px;
        }

        /* ===== FORM ===== */
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            color: var(--text);
            background: var(--bg-card);
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .form-label-sm {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            display: block;
        }

        /* ===== SELECT2 ===== */
        .select2-container--default .select2-selection--single {
            border: 1px solid var(--border) !important;
            border-radius: 4px !important;
            height: 46px !important;
            padding: 10px 16px !important;
            background: var(--bg-card) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.88rem !important;
            color: var(--text) !important;
            line-height: 22px !important;
            padding-left: 0 !important;
        }

        .select2-dropdown {
            border: 1px solid var(--border) !important;
            border-radius: 4px !important;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--bg-warm);
            border-top: 1px solid var(--border-light);
            padding: 80px 0 40px;
        }

        .footer-title {
            font-family: 'Inter', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-light);
            margin-bottom: 20px;
        }

        .footer-link {
            font-size: 0.85rem;
            color: var(--text-sub);
            text-decoration: none;
            display: block;
            padding: 6px 0;
            transition: color 0.2s ease;
        }

        .footer-link:hover {
            color: var(--text);
        }

        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 24px;
            margin-top: 48px;
        }

        .footer-copy {
            font-size: 0.78rem;
            color: var(--text-light);
        }

        /* ===== OFFCANVAS ===== */
        .offcanvas {
            background: var(--bg-card);
            border-left: 1px solid var(--border);
        }

        /* ===== PAGINATION ===== */
        .pagination .page-link {
            color: var(--text-sub);
            border: 1px solid var(--border);
            background: var(--bg-card);
            padding: 8px 14px;
            font-size: 0.82rem;
            border-radius: 2px;
            margin: 0 2px;
        }

        .pagination .page-link:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        /* ===== UTILITIES ===== */
        .text-accent { color: var(--accent) !important; }
        .bg-warm { background: var(--bg-warm) !important; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-light); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .section-title { font-size: 2.2rem; }
            .section { padding: 70px 0; }
        }

        @media (max-width: 767.98px) {
            .section-title { font-size: 1.8rem; }
            .section { padding: 50px 0; }
            .nav-links { display: none; }
        }

        /* ===== PAYMENT CARD LABEL ===== */
        .payment-card-label {
            border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--bg-card);
        }

        .payment-card-label:hover {
            border-color: var(--accent);
        }

        .btn-check:checked + .payment-card-label {
            border-color: var(--accent);
            background: var(--accent-light);
        }

        .payment-label-text {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text);
        }

        /* ===== TOAST ===== */
        .toast-item {
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 14px 18px;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            animation: toastSlideIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 360px;
        }

        .toast-success { border-left: 3px solid #28a745; }
        .toast-warning { border-left: 3px solid #ffc107; }
        .toast-danger { border-left: 3px solid #dc3545; }

        @keyframes toastSlideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="nav-main sticky-top" id="mainNav">
        <div class="container">
            <div class="nav-inner">
                <a class="nav-brand" href="/">
                    <img src="/img/logo.png" alt="Hijabkku">
                    <span class="nav-brand-text">HIJABKU</span>
                </a>

                <ul class="nav-links d-none d-lg-flex">
                    <li><a href="/" class="{{ Request::is('/') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="/catalog" class="{{ Request::is('catalog') ? 'active' : '' }}">Katalog</a></li>
                    <li><a href="{{ route('about') }}" class="{{ Request::is('about') ? 'active' : '' }}">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ Request::is('contact') ? 'active' : '' }}">Kontak</a></li>
                </ul>

                <div class="d-flex align-items-center">
                    <button class="nav-cart d-none d-lg-flex" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        <span class="nav-cart-badge" id="cart-badge">0</span>
                    </button>
                    <a class="nav-login d-none d-lg-flex" href="{{ route('login') }}">Login</a>

                    <!-- Mobile Menu Toggle -->
                    <button class="btn p-0 d-lg-none ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Nav -->
            <div class="collapse d-lg-none" id="mobileNav" style="padding-bottom: 16px;">
                <ul class="list-unstyled mb-0">
                    <li><a href="/" class="d-block py-2 text-decoration-none" style="color: var(--text-sub); font-size: 0.9rem;">Beranda</a></li>
                    <li><a href="/catalog" class="d-block py-2 text-decoration-none" style="color: var(--text-sub); font-size: 0.9rem;">Katalog</a></li>
                    <li><a href="{{ route('about') }}" class="d-block py-2 text-decoration-none" style="color: var(--text-sub); font-size: 0.9rem;">Tentang</a></li>
                    <li><a href="{{ route('contact') }}" class="d-block py-2 text-decoration-none" style="color: var(--text-sub); font-size: 0.9rem;">Kontak</a></li>
                    <li style="border-top: 1px solid var(--border-light); margin-top: 8px; padding-top: 8px;">
                        <a href="{{ route('login') }}" class="d-block py-2 text-decoration-none" style="color: var(--accent); font-size: 0.9rem; font-weight: 500;">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a class="nav-brand mb-4 d-inline-flex" href="/">
                        <img src="/img/logo.png" alt="Hijabkku" style="height: 26px;">
                        <span class="nav-brand-text">HIJABKU</span>
                    </a>
                    <p style="font-size: 0.88rem; color: var(--text-sub); line-height: 1.7; max-width: 280px;">
                        Hijab premium untuk wanita Muslimah yang anggun dan percaya diri.
                    </p>
                    <div class="mt-4">
                        <a href="https://instagram.com/hijabkku" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1px solid var(--border); border-radius: 50%; color: var(--text-sub); text-decoration: none; transition: all 0.2s ease;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5"/></svg>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Menu</h6>
                    <div>
                        <a href="/" class="footer-link">Beranda</a>
                        <a href="{{ route('catalog') }}" class="footer-link">Katalog</a>
                        <a href="{{ route('about') }}" class="footer-link">Tentang Kami</a>
                        <a href="{{ route('contact') }}" class="footer-link">Kontak</a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-title">Kebijakan</h6>
                    <div>
                        <a href="{{ route('privacy-policy') }}" class="footer-link">Privasi</a>
                        <a href="{{ route('terms') }}" class="footer-link">Syarat & Ketentuan</a>
                        <a href="{{ route('refund-policy') }}" class="footer-link">Refund</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <h6 class="footer-title">Kontak</h6>
                    <p style="font-size: 0.85rem; color: var(--text-sub); line-height: 1.7; margin-bottom: 16px;">
                        Kel. Panaragan Jaya, Kec. Tulang Bawang Tengah,<br>Kab. Tulang Bawang Barat, Lampung
                    </p>
                    <p style="font-size: 0.85rem; margin-bottom: 8px;">
                        <a href="mailto:andreperiozaherpa@gmail.com" class="footer-link" style="padding: 0;">andreperiozaherpa@gmail.com</a>
                    </p>
                    <p style="font-size: 0.85rem; margin-bottom: 8px;">
                        <a href="https://wa.me/6282280783843" target="_blank" class="footer-link" style="padding: 0;">0822 8078 3843</a>
                    </p>
                    <p style="font-size: 0.82rem; color: var(--text-light); margin: 0;">Senin - Sabtu, 08.00 - 20.00 WIB</p>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
                <p class="footer-copy mb-0">&copy; {{ date('Y') }} Hijabkku. All rights reserved.</p>
                <a href="{{ route('login') }}" class="footer-link" style="padding: 0;">Login POS</a>
            </div>
        </div>
    </footer>

    <!-- Global Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel"
        style="width: 400px; max-width: 100%;">
        <div class="offcanvas-header py-3" style="border-bottom: 1px solid var(--border);">
            <h5 style="font-family: 'Inter', sans-serif; font-size: 0.92rem; font-weight: 500; margin: 0;" id="cartOffcanvasLabel">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="color: var(--accent); margin-right: 8px;"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                Keranjang
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="p-3 mb-3" style="background: var(--bg-warm); border: 1px solid var(--border-light);">
                <span style="font-size: 0.7rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Cabang Pengambilan</span>
                <strong id="cart-pickup-store" class="d-block mt-1" style="font-size: 0.9rem; color: var(--text);">-</strong>
            </div>

            <div id="cart-items-container" class="flex-grow-1 overflow-auto"></div>

            <div id="cart-empty-state" class="text-center py-5 my-5">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                <p class="mt-3" style="font-size: 0.9rem; color: var(--text-light);">Keranjang kosong</p>
                <a href="{{ route('catalog') }}" class="btn-secondary px-4 py-2 mt-2" style="font-size: 0.82rem; text-decoration: none;">Mulai Belanja</a>
            </div>

            <div id="cart-footer" class="pt-3 mt-3" style="border-top: 1px solid var(--border);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span style="font-size: 0.9rem; color: var(--text-sub);">Subtotal</span>
                    <span id="cart-subtotal" style="font-family: 'DM Serif Display', serif; font-size: 1.3rem; color: var(--text);">Rp 0</span>
                </div>
                <button class="btn-primary w-100 py-3" style="letter-spacing: 0.5px; text-align: center;" onclick="openCheckoutModal()">Lanjutkan ke Pembayaran</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 8px;"></div>

    <!-- Checkout Modal (Global) -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 4px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-lg-7 p-4 p-md-5" style="background: var(--bg-card);">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 style="font-size: 1.2rem; color: var(--text);">Checkout</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        @if (($xenditSimulationMode ?? 'false') === 'true')
                            <div class="p-3 mb-4" style="background: var(--bg-warm); border: 1px solid var(--border); font-size: 0.85rem; color: var(--text-sub);">
                                <strong>Mode Simulasi Aktif</strong> - Pembayaran tidak akan diproses ke sistem ril.
                            </div>
                        @endif

                        <div class="p-3 mb-4" style="background: var(--accent-light); border: 1px solid var(--border); font-size: 0.85rem; color: var(--accent);">
                            <strong>Catatan:</strong> Pesanan wajib diambil di cabang toko setelah pembayaran lunas.
                        </div>

                        <form id="landingCheckoutForm" onsubmit="submitCheckout(event)">
                            <h6 style="font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">Informasi Pelanggan</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-sm">Nama Lengkap</label>
                                    <input type="text" id="cust-name" class="form-input" placeholder="Masukkan nama" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-sm">Nomor WhatsApp</label>
                                    <input type="tel" id="cust-phone" class="form-input" placeholder="081234567890" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label-sm">Email (Opsional)</label>
                                <input type="email" id="cust-email" class="form-input" placeholder="email@contoh.com">
                            </div>

                            <h6 style="font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 16px;">Metode Pembayaran</h6>
                            <div class="row g-2 mb-4">
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-qris" value="QRIS" checked onchange="globalUpdatePaymentFees()">
                                    <label class="payment-card-label w-100 py-3 text-center d-block" for="pay-qris">
                                        <span class="payment-label-text d-block">QRIS</span>
                                        <span style="font-size: 0.7rem; color: var(--text-light);">Fee 0.7%</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-va" value="VA" onchange="globalUpdatePaymentFees()">
                                    <label class="payment-card-label w-100 py-3 text-center d-block" for="pay-va">
                                        <span class="payment-label-text d-block">VA</span>
                                        <span style="font-size: 0.7rem; color: var(--text-light);">Fee Rp 5.040</span>
                                    </label>
                                </div>
                                <div class="col-4">
                                    <input type="radio" class="btn-check" name="payment_method" id="pay-ewallet" value="EWALLET" onchange="globalUpdatePaymentFees()">
                                    <label class="payment-card-label w-100 py-3 text-center d-block" for="pay-ewallet">
                                        <span class="payment-label-text d-block">E-Wallet</span>
                                        <span style="font-size: 0.7rem; color: var(--text-light);">Fee 1.665%</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-4 d-flex justify-content-center">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>

                            <button type="submit" id="btn-submit-checkout" class="btn-primary w-100 py-3" style="letter-spacing: 0.5px;">Bayar Sekarang</button>
                        </form>
                    </div>

                    <div class="col-lg-5 p-4 p-md-5" style="background: var(--bg-warm); border-left: 1px solid var(--border);">
                        <h4 style="font-size: 1rem; font-family: 'Inter', sans-serif; font-weight: 500; color: var(--text); margin-bottom: 20px;">Ringkasan Pesanan</h4>
                        <div class="mb-4">
                            <span style="font-size: 0.7rem; font-weight: 500; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px;">Cabang:</span>
                            <span id="checkout-pickup-store" class="d-block mt-1" style="font-size: 0.92rem; color: var(--text);">-</span>
                        </div>
                        <div id="checkout-items-list" class="mb-4 overflow-auto" style="max-height: 180px;"></div>
                        <div style="border-top: 1px solid var(--border); padding-top: 16px;">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size: 0.85rem; color: var(--text-sub);">Subtotal</span>
                                <strong id="checkout-subtotal" style="font-size: 0.85rem; color: var(--text);">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size: 0.85rem; color: var(--text-sub);">Biaya (<span id="fee-type-label">QRIS</span>)</span>
                                <strong id="checkout-fee" style="font-size: 0.85rem; color: var(--text);">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px solid var(--border);">
                                <span style="font-family: 'DM Serif Display', serif; font-size: 1.1rem; color: var(--text);">Total</span>
                                <span id="checkout-total" style="font-family: 'DM Serif Display', serif; font-size: 1.4rem; color: var(--text);">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner Overlay -->
    <div id="checkout-spinner" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(250, 249, 247, 0.92); backdrop-filter: blur(8px); align-items: center; justify-content: center; flex-direction: column;">
        <div style="width: 48px; height: 48px; border: 3px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin 0.8s linear infinite;"></div>
        <p style="margin-top: 24px; font-size: 0.92rem; color: var(--text); font-weight: 500;">Memproses pesanan Anda...</p>
        <p style="margin-top: 8px; font-size: 0.82rem; color: var(--text-light);">Mohon jangan tutup halaman ini</p>
    </div>

    <!-- Scripts -->
    <script src="/vendor/acorn/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="/vendor/acorn/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons-interface.js"></script>
    <script src="/vendor/acorn/icon/acorn-icons-commerce.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Global Cart Functions
        let cart = [];

        document.addEventListener('DOMContentLoaded', function() {
            const savedCart = localStorage.getItem('hijabkku_cart');
            const savedToko = localStorage.getItem('hijabkku_cart_toko');

            if (savedCart) {
                cart = JSON.parse(savedCart);
                updateGlobalCartUI();
            }
        });

        function updateGlobalCartUI() {
            const container = document.getElementById('cart-items-container');
            const emptyState = document.getElementById('cart-empty-state');
            const footer = document.getElementById('cart-footer');
            const badge = document.getElementById('cart-badge');

            if (!container) return;

            if (cart.length === 0) {
                container.style.display = 'none';
                emptyState.style.display = 'block';
                footer.style.display = 'none';
                if (badge) badge.innerText = '0';
                return;
            }

            container.style.display = 'block';
            emptyState.style.display = 'none';
            footer.style.display = 'block';

            let totalItems = 0;
            let subtotal = 0;
            let htmlContent = '';

            cart.forEach(item => {
                totalItems += item.jumlah;
                const itemTotal = item.harga * item.jumlah;
                subtotal += itemTotal;

                htmlContent += `
                    <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid var(--border-light);">
                        <img src="${item.img}" style="width: 56px; height: 56px; object-fit: cover; border: 1px solid var(--border-light); margin-right: 14px;" alt="${item.nama_barang}">
                        <div class="flex-grow-1">
                            <div style="font-size: 0.88rem; font-weight: 500; color: var(--text); margin-bottom: 4px;">${item.nama_barang}</div>
                            <div style="font-size: 0.82rem; color: var(--accent); font-weight: 600;">Rp ${item.harga.toLocaleString('id-ID')}</div>
                            <div class="d-flex align-items-center mt-2">
                                <button style="width: 28px; height: 28px; border: 1px solid var(--border); background: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.85rem; color: var(--text-sub);" onclick="globalChangeQty('${item.kode_barang}', -1)">-</button>
                                <span style="min-width: 32px; text-align: center; font-size: 0.85rem; font-weight: 500; color: var(--text);">${item.jumlah}</span>
                                <button style="width: 28px; height: 28px; border: 1px solid var(--border); background: var(--bg-card); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.85rem; color: var(--text-sub);" onclick="globalChangeQty('${item.kode_barang}', 1)">+</button>
                            </div>
                        </div>
                        <button style="background: none; border: none; cursor: pointer; padding: 8px; color: var(--text-light);" onclick="globalRemoveFromCart('${item.kode_barang}')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                        </button>
                    </div>
                `;
            });

            container.innerHTML = htmlContent;
            document.getElementById('cart-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (badge) badge.innerText = totalItems;
        }

        function globalRemoveFromCart(kode) {
            cart = cart.filter(item => item.kode_barang !== kode);
            localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
            updateGlobalCartUI();
        }

        function globalChangeQty(kode, delta) {
            const item = cart.find(item => item.kode_barang === kode);
            if (item) {
                if (delta > 0 && item.jumlah + delta > item.maxStock) {
                    return;
                }
                item.jumlah += delta;
                if (item.jumlah <= 0) {
                    globalRemoveFromCart(kode);
                } else {
                    localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                    updateGlobalCartUI();
                }
            }
        }

        function globalAddToCart(kode, name, price, img, maxStock) {
            if (maxStock <= 0) {
                return;
            }

            const existingItem = cart.find(item => item.kode_barang === kode);
            if (existingItem) {
                if (existingItem.jumlah + 1 > maxStock) {
                    return;
                }
                existingItem.jumlah++;
            } else {
                cart.push({
                    kode_barang: kode,
                    nama_barang: name,
                    harga: price,
                    img: img,
                    jumlah: 1,
                    maxStock: maxStock
                });
            }

            localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
            updateGlobalCartUI();

            const cartOffcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(cartOffcanvasEl) || new bootstrap.Offcanvas(cartOffcanvasEl);
            bsOffcanvas.show();
        }
        function openCheckoutModal() {
            const cartOffcanvasEl = document.getElementById('cartOffcanvas');
            const bsOffcanvas = bootstrap.Offcanvas.getInstance(cartOffcanvasEl);

            function showCheckout() {
                if (cart.length === 0) {
                    globalShowToast('Keranjang kosong. Silakan tambahkan produk terlebih dahulu.', 'warning');
                    return;
                }

                const itemsContainer = document.getElementById('checkout-items-list');
                let html = '';
                let subtotal = 0;

                cart.forEach(item => {
                    const totalItemPrice = item.harga * item.jumlah;
                    subtotal += totalItemPrice;
                    html += `
                        <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid var(--border-light);">
                            <div style="width: 48px; height: 48px; overflow: hidden; border: 1px solid var(--border-light); margin-right: 12px; flex-shrink: 0;">
                                <img src="${item.img}" alt="${item.nama_barang}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="flex-grow-1">
                                <div style="font-size: 0.85rem; font-weight: 500; color: var(--text);">${item.nama_barang}</div>
                                <div style="font-size: 0.78rem; color: var(--text-sub);">${item.jumlah} x Rp ${item.harga.toLocaleString('id-ID')}</div>
                            </div>
                            <div style="font-size: 0.85rem; font-weight: 600; color: var(--text);">Rp ${totalItemPrice.toLocaleString('id-ID')}</div>
                        </div>
                    `;
                });

                itemsContainer.innerHTML = html;
                document.getElementById('checkout-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');

                const pickupEl = document.getElementById('checkout-pickup-store');
                if (pickupEl) {
                    const tokoEl = document.getElementById('toko-selector');
                    if (tokoEl) {
                        pickupEl.innerText = tokoEl.options[tokoEl.selectedIndex].text;
                    } else {
                        pickupEl.innerText = '-';
                    }
                }

                globalUpdatePaymentFees();

                const checkModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
                checkModal.show();
            }

            if (bsOffcanvas) {
                cartOffcanvasEl.addEventListener('hidden.bs.offcanvas', function handler() {
                    cartOffcanvasEl.removeEventListener('hidden.bs.offcanvas', handler);
                    showCheckout();
                });
                bsOffcanvas.hide();
            } else {
                showCheckout();
            }
        }

        function globalUpdatePaymentFees() {
            let subtotal = 0;
            cart.forEach(item => {
                subtotal += (item.harga * item.jumlah);
            });

            const methodEl = document.querySelector('input[name="payment_method"]:checked');
            const methodVal = methodEl ? methodEl.value : 'QRIS';
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
                    globalShowToast('Mohon lengkapi data Nama Lengkap dan Nomor WhatsApp/HP!', 'warning');
                    return;
                }

                let recaptchaResponse = '';
                if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.getResponse === 'function') {
                    try {
                        recaptchaResponse = grecaptcha.getResponse();
                    } catch (recaptchaErr) {
                        console.warn("grecaptcha.getResponse error:", recaptchaErr);
                    }
                }

                if (document.querySelector('.g-recaptcha') && !recaptchaResponse) {
                    globalShowToast('Harap verifikasi bahwa Anda bukan robot!', 'warning');
                    return;
                }

                const btn = document.getElementById('btn-submit-checkout');
                if (!btn) return;
                const originalBtnText = btn.innerHTML;

                const isSimulation = "{{ $xenditSimulationMode ?? 'false' }}";
                if (isSimulation === 'true') {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Mode Simulasi Pembayaran',
                            text: 'Ini adalah simulasi bayar dan belum aktif sepenuhnya. Lanjutkan pembuatan tagihan simulasi?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: 'var(--accent)',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Lanjutkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse);
                            }
                        });
                    } else {
                        if (confirm('Mode Simulasi Pembayaran: Lanjutkan pembuatan tagihan simulasi?')) {
                            proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse);
                        }
                    }
                } else {
                    proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse);
                }
            } catch (err) {
                console.error("Error in submitCheckout handler:", err);
                globalShowToast("Kesalahan script checkout: " + err.message, 'danger');
            }
        }

        function proceedWithCheckout(btn, originalBtnText, branch, name, phone, email, method, recaptchaResponse) {
            try {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                btn.disabled = true;

                const cartPayload = cart.map(item => {
                    return {
                        kode_barang: item.kode_barang,
                        jumlah: item.jumlah
                    };
                });

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
                        cart = [];
                        localStorage.setItem('hijabkku_cart', JSON.stringify(cart));
                        updateGlobalCartUI();

                        const checkoutModalEl = document.getElementById('checkoutModal');
                        if (checkoutModalEl) {
                            const checkoutModal = bootstrap.Modal.getInstance(checkoutModalEl);
                            if (checkoutModal) {
                                checkoutModal.hide();
                            }
                        }

                        const spinner = document.getElementById('checkout-spinner');
                        if (spinner) {
                            spinner.style.display = 'flex';
                        }

                        window.location.href = res.body.checkout_url;
                    } else {
                        globalShowToast('Gagal memproses Checkout: ' + (res.body.message || 'Kesalahan sistem tidak dikenal.'), 'danger');
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                            try { grecaptcha.reset(); } catch (e) {}
                        }
                    }
                })
                .catch(err => {
                    console.error(err);
                    globalShowToast('Terjadi kesalahan koneksi saat memproses checkout.', 'danger');
                    btn.innerHTML = originalBtnText;
                    btn.disabled = false;
                    if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.reset === 'function') {
                        try { grecaptcha.reset(); } catch (e) {}
                    }
                });
            } catch (err) {
                console.error("Error in proceedWithCheckout:", err);
                globalShowToast("Kesalahan saat memproses checkout: " + err.message, 'danger');
                btn.innerHTML = originalBtnText;
                btn.disabled = false;
            }
        }

        function globalShowToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast-item toast-${type} d-flex align-items-center`;

            let iconSvg = '';
            if (type === 'success') {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#28a745" class="bi bi-check-circle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>`;
            } else if (type === 'warning') {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#ffc107" class="bi bi-exclamation-triangle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>`;
            } else {
                iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#dc3545" class="bi bi-x-circle-fill me-3" viewBox="0 0 16 16" style="flex-shrink: 0;"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>`;
            }

            toast.innerHTML = `
                ${iconSvg}
                <div class="flex-grow-1 small fw-bold" style="line-height: 1.4;">${message}</div>
                <button type="button" class="btn-close ms-2" style="font-size: 0.7rem; filter: brightness(0.2); flex-shrink: 0;" onclick="globalCloseToast(this)"></button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                globalCloseToast(toast.querySelector('.btn-close'));
            }, 3500);
        }

        function globalCloseToast(btn) {
            if (!btn) return;
            const item = btn.closest('.toast-item');
            if (item) {
                item.style.animation = 'toastSlideOut 0.3s cubic-bezier(0.175, 0.885, 0.32, 1) forwards';
                setTimeout(() => item.remove(), 300);
            }
        }
    </script>

    @yield('scripts')
</body>

</html>
