<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title', 'Hijabkku - Elegansi dalam Berhijab')</title>

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

        .footer {
            background-color: #faf9f6;
            padding: 60px 0 40px 0;
            margin-top: 80px;
            border-top: 1px solid #f0eee9;
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
                data-bs-target="#navbarNav">
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
                        <a class="nav-link px-3" href="/#tentang">Cerita Kami</a>
                    </li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-outline-secondary rounded-pill px-4" style="border-color: #dcdcdc;"
                            href="{{ route('login') }}">
                            <i data-acorn-icon="user" class="me-2" style="font-size: 14px;"></i>Login
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
            <div class="row mb-5">
                <div class="col-lg-4 mb-4 mb-lg-0 text-center text-lg-start">
                    <a class="navbar-brand d-flex align-items-center justify-content-center justify-content-lg-start mb-3"
                        href="/">
                        <img src="/img/logo.png" alt="Hijabku Logo" style="height: 40px; border-radius: 5px;"
                            class="me-2">
                        HIJABKU
                    </a>
                    <p class="text-muted pe-lg-4">Menebar kebaikan dan kecantikan melalui balutan hijab yang elegan
                        dengan sentuhan warna pastel.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0 text-center">
                    <h5 class="fw-bold mb-4">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('catalog') }}"
                                class="text-muted text-decoration-none hover-gold">Koleksi Terlaris</a></li>
                        <li class="mb-2"><a href="/#tentang"
                                class="text-muted text-decoration-none hover-gold">Tentang Kami</a></li>
                        <li class="mb-2"><a href="https://instagram.com/hijabkku"
                                class="text-muted text-decoration-none hover-gold">Cara Pemesanan</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 text-center text-lg-end">
                    <h5 class="fw-bold mb-4">Ikuti Kami</h5>
                    <p class="text-muted mb-2">Pesan via Instagram:</p>
                    <a href="https://instagram.com/hijabkku" target="_blank"
                        class="btn btn-outline-dark rounded-pill px-4">
                        <i data-acorn-icon="instagram" class="me-2"></i>@hijabkku
                    </a>
                </div>
            </div>

            <div class="row border-top pt-4 border-light">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="small text-muted mb-0">&copy; {{ date('Y') }} Hijabkku. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-muted mb-0"><a href="{{ route('login') }}"
                            class="text-muted text-decoration-none">Login Sistem POS</a></p>
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

    @yield('scripts')
</body>

</html>
