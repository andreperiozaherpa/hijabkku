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

        .metric-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.08);
        }

        .chart-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.6);
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

        .segmented-control {
            background-color: #f1f5f9;
            padding: 4px;
            border-radius: 12px;
            display: inline-flex;
            border: 1px solid #e2e8f0;
        }

        .segmented-control .btn {
            border: none;
            border-radius: 8px;
            padding: 6px 16px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #64748b;
            background: transparent;
            transition: all 0.2s ease;
        }

        .segmented-control .btn.active {
            background: #fff;
            color: #0284c7;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .age-badge {
            background-color: rgba(245, 158, 11, 0.1);
            color: #d97706;
            font-weight: 600;
            padding: 0.25em 0.5em;
            border-radius: 6px;
            font-size: 0.8rem;
        }
        
        .stock-badge {
            background-color: rgba(56, 189, 248, 0.1);
            color: #0284c7;
            font-weight: 600;
            padding: 0.25em 0.5em;
            border-radius: 6px;
            font-size: 0.8rem;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Dashboard Admin</h1>
                        <p class="text-muted">Akses penuh ke semua analisis penjualan, perbandingan omzet per toko, data barang terlaris, dan stok mati.</p>
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

            <!-- ADMIN DASHBOARD METRICS -->
            <div class="row mb-4">
                <div class="col-6 col-lg-3 mb-3">
                    <div class="card metric-card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="circle-icon bg-light-primary text-primary me-3 mb-0">
                                <i data-acorn-icon="user" class="icon" data-acorn-size="24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Pegawai</h6>
                                <h3 class="fw-bold mb-0 text-alternate">{{ $metrics['total_users'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="card metric-card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="circle-icon bg-light-secondary text-secondary me-3 mb-0">
                                <i data-acorn-icon="signboard" class="icon" data-acorn-size="24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Cabang Toko</h6>
                                <h3 class="fw-bold mb-0 text-alternate">{{ $metrics['total_tokos'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="card metric-card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="circle-icon bg-light-success text-success me-3 mb-0">
                                <i data-acorn-icon="handbag" class="icon" data-acorn-size="24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Data Barang</h6>
                                <h3 class="fw-bold mb-0 text-alternate">{{ $metrics['total_barangs'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                    <div class="card metric-card p-3">
                        <div class="card-body d-flex align-items-center">
                            <div class="circle-icon bg-light-danger text-danger me-3 mb-0">
                                <i data-acorn-icon="factory" class="icon" data-acorn-size="24"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1 small text-uppercase fw-bold">Supplier</h6>
                                <h3 class="fw-bold mb-0 text-alternate">{{ $metrics['total_suppliers'] }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 1. MULTI-STORE (PER TOKO) SALES COMPARISON CHARTS -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card chart-card">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                                <div>
                                    <h4 class="fw-bold text-alternate mb-1">Perbandingan Omzet Antar Cabang (Toko)</h4>
                                    <p class="text-muted small mb-0">Visualisasi tren perbandingan omzet harian, bulanan, dan tahunan untuk tiap cabang toko.</p>
                                </div>
                                <div class="segmented-control">
                                    <button type="button" class="btn active store-tab-btn" data-target="daily">Harian</button>
                                    <button type="button" class="btn store-tab-btn" data-target="monthly">Bulanan</button>
                                    <button type="button" class="btn store-tab-btn" data-target="yearly">Tahunan</button>
                                </div>
                            </div>
                            
                            <div class="w-100">
                                <canvas id="storeSalesChart" style="max-height: 380px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. STORE SALES CONTRIBUTION DETAIL TABLE -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card chart-card">
                        <div class="card-body">
                            <h4 class="fw-bold text-alternate mb-1">Rincian Kontribusi Omzet per Cabang Toko</h4>
                            <p class="text-muted small mb-3">Rekap nilai riil omzet harian (hari aktif terakhir), bulanan (bulan aktif terakhir), tahunan (tahun aktif terakhir), dan total kumulatif dalam Rupiah.</p>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">Nama Cabang Toko</th>
                                            <th class="text-muted text-small text-uppercase">Kode Cabang</th>
                                            <th class="text-muted text-small text-uppercase text-end">Omzet Hari Aktif Terakhir</th>
                                            <th class="text-muted text-small text-uppercase text-end">Omzet Bulan Aktif Terakhir</th>
                                            <th class="text-muted text-small text-uppercase text-end">Omzet Tahun Aktif Terakhir</th>
                                            <th class="text-muted text-small text-uppercase text-end fw-bold">Total Omzet Keseluruhan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tokos as $toko)
                                            @php
                                                $tokoDaily = collect($storeDailyDatasets)->where('label', $toko->nama_toko)->first();
                                                $tokoMonthly = collect($storeMonthlyDatasets)->where('label', $toko->nama_toko)->first();
                                                $tokoYearly = collect($storeYearlyDatasets)->where('label', $toko->nama_toko)->first();
                                                
                                                $dailyVal = ($tokoDaily && !empty($tokoDaily['data'])) ? end($tokoDaily['data']) : 0;
                                                $monthlyVal = ($tokoMonthly && !empty($tokoMonthly['data'])) ? end($tokoMonthly['data']) : 0;
                                                $yearlyVal = ($tokoYearly && !empty($tokoYearly['data'])) ? end($tokoYearly['data']) : 0;
                                                $totalVal = $tokoYearly ? array_sum($tokoYearly['data']) : 0;
                                            @endphp
                                            <tr>
                                                <td class="fw-bold text-alternate">{{ $toko->nama_toko }}</td>
                                                <td><code>{{ $toko->kode }}</code></td>
                                                <td class="text-end text-primary fw-semi-bold">
                                                    Rp {{ number_format($dailyVal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end text-success fw-semi-bold">
                                                    Rp {{ number_format($monthlyVal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end text-info fw-semi-bold">
                                                    Rp {{ number_format($yearlyVal, 0, ',', '.') }}
                                                </td>
                                                <td class="text-end fw-bold text-alternate">
                                                    Rp {{ number_format($totalVal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. OVERALL CUMULATIVE SALES & BEST SELLERS SECTION -->
            <div class="row mb-4">
                <!-- OVERALL SALES CHART -->
                <div class="col-12 col-xl-7 mb-4">
                    <div class="card chart-card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
                                <div>
                                    <h4 class="fw-bold text-alternate mb-1">Total Akumulasi Omzet (Gabungan)</h4>
                                    <p class="text-muted small mb-0">Total pendapatan kotor dari seluruh toko cabang.</p>
                                </div>
                                <div class="segmented-control">
                                    <button type="button" class="btn active sales-tab-btn" data-target="daily">Harian</button>
                                    <button type="button" class="btn sales-tab-btn" data-target="monthly">Bulanan</button>
                                    <button type="button" class="btn sales-tab-btn" data-target="yearly">Tahunan</button>
                                </div>
                            </div>
                            
                            <div class="w-100">
                                <canvas id="overallSalesChart" style="max-height: 380px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BEST SELLERS (TOP 20) CHART -->
                <div class="col-12 col-xl-5 mb-4">
                    <div class="card chart-card h-100">
                        <div class="card-body">
                            <h4 class="fw-bold text-alternate mb-1">20 Barang Terlaris</h4>
                            <p class="text-muted small mb-3">Berdasarkan total kuantitas terjual.</p>
                            <div style="position: relative; max-height: 380px; overflow-y: auto;">
                                <canvas id="bestSellersChart" style="height: 480px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. 20 OLDEST STOCK IN STORE -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card chart-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="fw-bold text-alternate mb-1">20 Barang Lama (Stok Mati/Slow Moving)</h4>
                                    <p class="text-muted small mb-0">Barang yang sudah lama berada di toko dengan stok yang masih tersedia (Urutan Terlama).</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">No</th>
                                            <th class="text-muted text-small text-uppercase">Kode Barang</th>
                                            <th class="text-muted text-small text-uppercase">Nama Barang</th>
                                            <th class="text-muted text-small text-uppercase text-center">Jumlah Stok</th>
                                            <th class="text-muted text-small text-uppercase">Tanggal Input</th>
                                            <th class="text-muted text-small text-uppercase text-end">Umur Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($oldestStocks as $index => $stock)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><code>{{ $stock->kode_barang }}</code></td>
                                                <td class="fw-bold text-alternate">{{ $stock->nama_barang }}</td>
                                                <td class="text-center">
                                                    <span class="stock-badge">{{ $stock->jumlah }} pcs</span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($stock->created_at)->format('d M Y H:i') }}</td>
                                                <td class="text-end">
                                                    <span class="age-badge">
                                                        {{ \Carbon\Carbon::parse($stock->created_at)->diffInDays() }} Hari Lalu
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Load Chart.js from CDN to guarantee flawless rendering -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Currency formatter
            const formatRupiah = (value) => {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(value);
            };

            // Shorthand Currency formatter for neat Y-axis
            const formatRupiahShorthand = (value) => {
                if (value >= 1e9) {
                    return 'Rp ' + (value / 1e9).toFixed(1).replace(/\.0$/, '') + ' M';
                }
                if (value >= 1e6) {
                    return 'Rp ' + (value / 1e6).toFixed(1).replace(/\.0$/, '') + ' Jt';
                }
                if (value >= 1e3) {
                    return 'Rp ' + (value / 1e3).toFixed(0) + ' Rb';
                }
                return 'Rp ' + value;
            };

            // Date Label Formatter for neat X-axis
            const formatDateLabel = (dateStr) => {
                if (typeof dateStr === 'string') {
                    // Date YYYY-MM-DD
                    if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                        const parts = dateStr.split('-');
                        const day = parts[2];
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        const monthIdx = parseInt(parts[1], 10) - 1;
                        return `${day} ${monthNames[monthIdx]}`;
                    }
                    // Month YYYY-MM
                    if (/^\d{4}-\d{2}$/.test(dateStr)) {
                        const parts = dateStr.split('-');
                        const yearShort = parts[0].substring(2);
                        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                        const monthIdx = parseInt(parts[1], 10) - 1;
                        return `${monthNames[monthIdx]} '${yearShort}`;
                    }
                }
                return dateStr;
            };

            // Premium distinct store colors
            const storeColors = [
                '#0284c7', // TK_4fep (Daya Asri) - primary
                '#10b981', // TK_z4sj (Panaragan) - success
                '#6366f1', // TK_un7s (Mesuji) - indigo
                '#f59e0b', // TK_j7q1 (stock hilang) - amber
                '#ec4899', // TK_dtlo (Online Shop) - pink
                '#8b5cf6', // purple
                '#f43f5e'  // rose
            ];

            // --- 1. OVERALL CUMULATIVE SALES CHARTS (DYNAMIC RE-DATA SOLUTION) ---
            const dailyData = {
                labels: @json($dailySales->pluck('date')).map(formatDateLabel),
                totals: @json($dailySales->pluck('total'))
            };

            const monthlyData = {
                labels: @json($monthlySales->pluck('month')).map(formatDateLabel),
                totals: @json($monthlySales->pluck('total'))
            };

            const yearlyData = {
                labels: @json($yearlySales->pluck('year')),
                totals: @json($yearlySales->pluck('total'))
            };

            const overallCtx = document.getElementById('overallSalesChart').getContext('2d');
            
            const getOverallThemeColor = (type) => {
                return type === 'daily' ? '#0284c7' : (type === 'monthly' ? '#10b981' : '#6366f1');
            };
            
            const getOverallLabel = (type) => {
                return type === 'daily' ? 'Omzet Harian' : (type === 'monthly' ? 'Omzet Bulanan' : 'Omzet Tahunan');
            };

            const getOverallDataset = (type) => {
                const data = type === 'daily' ? dailyData.totals : (type === 'monthly' ? monthlyData.totals : yearlyData.totals);
                const colorTheme = getOverallThemeColor(type);
                const labelText = getOverallLabel(type);
                return [{
                    label: labelText,
                    data: data,
                    borderColor: colorTheme,
                    backgroundColor: 'rgba(56, 189, 248, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: colorTheme,
                    pointBorderColor: '#fff',
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: colorTheme
                }];
            };

            const overallSalesChart = new Chart(overallCtx, {
                type: 'line',
                data: {
                    labels: dailyData.labels,
                    datasets: getOverallDataset('daily')
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${formatRupiah(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return formatRupiahShorthand(value);
                                }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.03)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 10
                            }
                        }
                    }
                }
            });

            const tabButtons = document.querySelectorAll('.sales-tab-btn');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const target = this.getAttribute('data-target'); // 'daily', 'monthly', 'yearly'

                    if (target === 'daily') {
                        overallSalesChart.data.labels = dailyData.labels;
                        overallSalesChart.data.datasets = getOverallDataset('daily');
                    } else if (target === 'monthly') {
                        overallSalesChart.data.labels = monthlyData.labels;
                        overallSalesChart.data.datasets = getOverallDataset('monthly');
                    } else if (target === 'yearly') {
                        overallSalesChart.data.labels = yearlyData.labels;
                        overallSalesChart.data.datasets = getOverallDataset('yearly');
                    }
                    
                    overallSalesChart.update();
                });
            });


            // --- 2. PER-STORE COMPARISON SALES CHARTS (DYNAMIC RE-DATA SOLUTION) ---
            // Backend Data for per-store comparison
            const storeDailyLabels = @json($activeDays).map(formatDateLabel);
            const storeDailyDatasets = @json($storeDailyDatasets);

            const storeMonthlyLabels = @json($activeMonths).map(formatDateLabel);
            const storeMonthlyDatasets = @json($storeMonthlyDatasets);

            const storeYearlyLabels = @json($yearlyLabels);
            const storeYearlyDatasets = @json($storeYearlyDatasets);

            const storeComparisonCtx = document.getElementById('storeSalesChart').getContext('2d');
            
            const getStoreDatasets = (type) => {
                const datasetsRaw = type === 'daily' ? storeDailyDatasets : (type === 'monthly' ? storeMonthlyDatasets : storeYearlyDatasets);
                return datasetsRaw.map((dataset, idx) => {
                    const color = storeColors[idx % storeColors.length];
                    return {
                        label: dataset.label,
                        data: dataset.data,
                        borderColor: color,
                        backgroundColor: 'transparent',
                        borderWidth: 2.5,
                        pointBackgroundColor: color,
                        pointBorderColor: '#fff',
                        pointHoverRadius: 5,
                        tension: 0.3
                    };
                });
            };

            const storeSalesChart = new Chart(storeComparisonCtx, {
                type: 'line',
                data: {
                    labels: storeDailyLabels,
                    datasets: getStoreDatasets('daily')
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            position: 'top',
                            labels: { boxWidth: 12, usePointStyle: true }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${formatRupiah(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return formatRupiahShorthand(value);
                                }
                            },
                            grid: { color: 'rgba(0, 0, 0, 0.03)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                autoSkip: true,
                                maxTicksLimit: 10
                            }
                        }
                    }
                }
            });

            // Toggle Store Comparison Tabs
            const storeTabButtons = document.querySelectorAll('.store-tab-btn');
            storeTabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    storeTabButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    const target = this.getAttribute('data-target'); // 'daily', 'monthly', 'yearly'
                    
                    if (target === 'daily') {
                        storeSalesChart.data.labels = storeDailyLabels;
                        storeSalesChart.data.datasets = getStoreDatasets('daily');
                    } else if (target === 'monthly') {
                        storeSalesChart.data.labels = storeMonthlyLabels;
                        storeSalesChart.data.datasets = getStoreDatasets('monthly');
                    } else if (target === 'yearly') {
                        storeSalesChart.data.labels = storeYearlyLabels;
                        storeSalesChart.data.datasets = getStoreDatasets('yearly');
                    }
                    
                    storeSalesChart.update();
                });
            });


            // --- 3. BEST SELLERS (TOP 20) CHART ---
            const bestSellersLabels = @json($bestSellers->pluck('nama_barang'));
            const bestSellersQuantities = @json($bestSellers->pluck('total_terjual'));

            const truncatedLabels = bestSellersLabels.map(label => {
                return label.length > 25 ? label.substring(0, 22) + '...' : label;
            });

            const bestSellersCtx = document.getElementById('bestSellersChart').getContext('2d');
            new Chart(bestSellersCtx, {
                type: 'bar',
                data: {
                    labels: truncatedLabels,
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: bestSellersQuantities,
                        backgroundColor: 'rgba(56, 189, 248, 0.85)',
                        hoverBackgroundColor: '#0284c7',
                        borderRadius: 6,
                        borderWidth: 0
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    const index = tooltipItems[0].dataIndex;
                                    return bestSellersLabels[index];
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(0, 0, 0, 0.03)' }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endsection
