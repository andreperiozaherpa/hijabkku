@extends('layouts.main')
@section('main')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            border: none;
            border-radius: 16px;
            color: #fff;
            box-shadow: 0 10px 30px rgba(2, 132, 199, 0.2);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .action-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: block;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.06);
        }

        .circle-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Dashboard Kasir</h1>
                        <p class="text-muted">Akses cepat ke kasir POS dan panduan transaksi penjualan.</p>
                    </div>
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Welcoming Banner -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card welcome-card p-4">
                        <div class="card-body">
                            <h2 class="display-6 fw-bold text-white mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}!</h2>
                            <p class="fs-5 mb-0 text-white opacity-90">
                                Anda masuk sebagai <strong class="text-uppercase">{{ Auth::user()->role }}</strong>
                                - Shift: <strong>{{ Auth::user()->shift == 1 ? 'Pagi' : (Auth::user()->shift == 2 ? 'Sore' : 'Semua') }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KASIR DASHBOARD ACTIONS -->
            <div class="row mb-5">
                <div class="col-12 col-md-6 mb-3">
                    <a href="/transaksi/penjualan" class="card action-card bg-foreground p-4 border-0 h-100">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="circle-icon bg-light-primary text-primary mx-auto" style="width: 72px; height: 72px;">
                                <i data-acorn-icon="cart" data-acorn-size="32"></i>
                            </div>
                            <h4 class="fw-bold text-alternate mb-2">Mulai Transaksi (POS)</h4>
                            <p class="text-muted mb-0">Klik disini untuk membuka layar POS kasir, melayani pembeli, dan mencetak invoice transaksi baru.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <a href="/buku/panduan" class="card action-card bg-foreground p-4 border-0 h-100">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                            <div class="circle-icon bg-light-info text-info mx-auto" style="width: 72px; height: 72px;">
                                <i data-acorn-icon="book" data-acorn-size="32"></i>
                            </div>
                            <h4 class="fw-bold text-alternate mb-2">Buku Panduan Kasir</h4>
                            <p class="text-muted mb-0">Lihat instruksi langkah-langkah penggunaan mesin kasir, pengembalian barang, dan penanganan metode pembayaran nontunai.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
