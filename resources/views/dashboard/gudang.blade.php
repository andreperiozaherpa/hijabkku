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
                        <h1 class="mb-0 pb-0 display-4" id="title">Dashboard Gudang</h1>
                        <p class="text-muted">Kelola persediaan barang, stok masuk, dan pemantauan gudang.</p>
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
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GUDANG DASHBOARD ACTIONS -->
            <div class="row mb-5">
                <div class="col-12 col-md-4 mb-3">
                    <a href="/manajemen/stock/inout/index" class="card action-card bg-foreground p-3 border-0 h-100">
                        <div class="card-body text-center">
                            <div class="circle-icon bg-light-primary text-primary mx-auto">
                                <i data-acorn-icon="exchange" data-acorn-size="24"></i>
                            </div>
                            <h5 class="fw-bold text-alternate mb-1">Stok Masuk / Keluar</h5>
                            <p class="text-muted small mb-0">Catat persediaan baru dari supplier atau mutasikan stok ke toko cabang.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <a href="/manajemen/stock/toko/index" class="card action-card bg-foreground p-3 border-0 h-100">
                        <div class="card-body text-center">
                            <div class="circle-icon bg-light-success text-success mx-auto">
                                <i data-acorn-icon="home-garage" data-acorn-size="24"></i>
                            </div>
                            <h5 class="fw-bold text-alternate mb-1">Stok Toko & Cabang</h5>
                            <p class="text-muted small mb-0">Pantau sisa stok barang di masing-masing lokasi secara live.</p>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-md-4 mb-3">
                    <a href="/buku/panduan" class="card action-card bg-foreground p-3 border-0 h-100">
                        <div class="card-body text-center">
                            <div class="circle-icon bg-light-info text-info mx-auto">
                                <i data-acorn-icon="book" data-acorn-size="24"></i>
                            </div>
                            <h5 class="fw-bold text-alternate mb-1">Buku Panduan Gudang</h5>
                            <p class="text-muted small mb-0">Instruksi lengkap seputar input barang, manajemen supplier, dan stok opname.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
