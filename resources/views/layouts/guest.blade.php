<!DOCTYPE html>
<html lang="id" data-url-prefix="/" data-footer="true"
    data-override='{"attributes": {"placement": "vertical", "layout": "fluid","radius": "standard", "color": "light-pink"}, "showSettings":false}'>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon Tags Start -->
    <link rel="icon" type="image/png" href="/img/logo.png" />
    <link rel="apple-touch-icon" href="/img/logo.png" />

    <!-- Font Tags Start -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/vendor/acorn/font/CS-Interface/style.css" />

    <!-- Vendor Styles Start -->
    <link rel="stylesheet" href="/vendor/acorn/css/vendor/bootstrap.min.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/vendor/OverlayScrollbars.min.css" />

    <!-- Template Base Styles Start -->
    <link rel="stylesheet" href="/vendor/acorn/css/styles.css" />
    <link rel="stylesheet" href="/vendor/acorn/css/main.css" />

    <script src="/vendor/acorn/js/base/loader.js"></script>
    <style>
        body {
            background-color: #fff0f5;
            /* light pink pastel background */
        }

        .btn-gold {
            background-color: #D4AF37;
            color: white;
            border-color: #D4AF37;
        }

        .btn-gold:hover {
            background-color: #c5a02c;
            color: white;
            border-color: #c5a02c;
        }
    </style>
</head>

<body class="h-100">
    <div id="root" class="h-100">
        <div class="fixed-background" style="background-image: linear-gradient(135deg, #ffd1dc 0%, #fff 100%);"></div>

        <div class="container-fluid p-0 h-100 position-relative">
            <div class="row g-0 h-100">
                <!-- Left Side Start -->
                <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                    <div class="min-h-100 d-flex align-items-center">
                        <div class="w-100 w-lg-75 w-xxl-50">
                            <div>
                                <div class="mb-5">
                                    <img src="/img/logo.png" alt="Hijabku Logo"
                                        style="height: 100px; border-radius: 15px;" class="mb-4 shadow">
                                    <h1 class="display-4 text-dark font-weight-bold">HIJABKU POS</h1>
                                    <h1 class="display-5 text-dark">Sistem Manajemen Toko</h1>
                                </div>
                                <p class="h6 text-dark lh-1-5 mb-5">
                                    Akses internal untuk mengelola persediaan produk, transaksi penjualan, dan laporan
                                    performa toko Hijabkku.
                                </p>
                                <div class="mb-5">
                                    <a class="btn btn-lg btn-outline-dark" href="/">Kembali ke Landing Page</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Left Side End -->

                <!-- Right Side Start -->
                <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                    <div
                        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
                        <div class="sw-lg-50 px-5">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                <!-- Right Side End -->
            </div>
        </div>
    </div>

    <!-- Vendor Scripts Start -->
    <script src="/vendor/acorn/js/vendor/jquery-3.5.1.min.js"></script>
    <script src="/vendor/acorn/js/vendor/bootstrap.bundle.min.js"></script>
    <script src="/vendor/acorn/js/vendor/OverlayScrollbars.min.js"></script>

    <!-- Template Base Scripts Start -->
    <script src="/vendor/acorn/js/base/helpers.js"></script>
    <script src="/vendor/acorn/js/base/globals.js"></script>
    <script src="/vendor/acorn/js/base/nav.js"></script>
    <script src="/vendor/acorn/js/base/settings.js"></script>
    <!-- Template Base Scripts End -->
    <!-- Page Specific Scripts Start -->
    <script src="/vendor/acorn/js/common.js"></script>
    <script src="/vendor/acorn/js/scripts.js"></script>
    <!-- Page Specific Scripts End -->
</body>

</html>
