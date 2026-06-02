@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Custom CSS Styles -->
            <style>
                /* Styling Tabs & Active states */
                .filter-period-btn {
                    transition: all 0.2s ease-in-out;
                    border-color: rgba(var(--primary-rgb), 0.25);
                    color: var(--primary);
                }

                .filter-period-btn:hover {
                    background-color: var(--primary-light);
                    border-color: var(--primary);
                    color: var(--primary-dark);
                }

                .filter-period-btn.active {
                    background-color: var(--primary) !important;
                    border-color: var(--primary) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3) !important;
                }

                .btn-gradient-success {
                    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                    border: none;
                    color: #fff;
                    transition: all 0.2s ease-in-out;
                }

                .btn-gradient-success:hover:not(:disabled) {
                    background: linear-gradient(135deg, #059669 0%, #047857 100%);
                    transform: translateY(-1px);
                    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
                }

                .btn-gradient-success:disabled {
                    background: #cbd5e1;
                    color: #64748b;
                    opacity: 0.75;
                }

                /* KPI Colors & Transitions */
                .card-kpi {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                .card-kpi:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
                }

                .bg-blue-soft {
                    background-color: rgba(59, 130, 246, 0.1);
                }

                .bg-green-soft {
                    background-color: rgba(16, 185, 129, 0.1);
                }

                .bg-purple-soft {
                    background-color: rgba(139, 92, 246, 0.1);
                }

                .bg-amber-soft {
                    background-color: rgba(245, 158, 11, 0.1);
                }

                .bg-slate-soft {
                    background-color: rgba(100, 116, 139, 0.1);
                }

                .text-blue {
                    color: #2563eb !important;
                }

                .text-green {
                    color: #059669 !important;
                }

                .text-purple {
                    color: #7c3aed !important;
                }

                .text-amber {
                    color: #d97706 !important;
                }

                .text-slate {
                    color: #475569 !important;
                }

                /* Modern Tab Styling */
                .modern-tabs {
                    background-color: #f1f5f9 !important;
                    border: 1px solid #e2e8f0;
                }

                .modern-tabs .nav-link {
                    color: #64748b;
                    transition: all 0.25s ease;
                }

                .modern-tabs .nav-link.active {
                    background-color: #ffffff !important;
                    color: var(--primary) !important;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                }

                .modern-tabs .nav-link.active #badge-count-umum {
                    background-color: #2563eb !important;
                }

                .modern-tabs .nav-link.active #badge-count-grosir {
                    background-color: #059669 !important;
                }

                /* Animations */
                .animate-fade-in {
                    animation: fadeIn 0.4s ease-out forwards;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(8px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                /* Custom Table Style */
                .table> :not(caption)>*>* {
                    padding: 1rem 0.75rem;
                }

                table.dataTable {
                    border-collapse: collapse !important;
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                    background: var(--primary) !important;
                    color: white !important;
                    border: 1px solid var(--primary) !important;
                    border-radius: 8px;
                }
            </style>

            <!-- Title Start -->
            <div class="page-title-container mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4 fw-bold text-dark" id="title">Laporan Penjualan</h1>
                        <p class="text-muted mb-0">Analisis kinerja transaksi kasir, omzet penjualan eceran dan grosir
                            real-time</p>
                    </div>
                </div>
            </div>
            <!-- Title End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <!-- Modern 3-Column Filter Card -->
                    <div class="card mb-4 card-spiner border-0 shadow-sm"
                        style="border-radius: 16px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-end">
                                <!-- Tanggal Filter -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i data-acorn-icon="calendar" class="me-1 text-primary"></i> Pilih Tanggal
                                    </label>
                                    <div class="input-group input-group-lg border-0 shadow-sm rounded-3">
                                        <input type="text" class="form-control border-0 bg-light"
                                            placeholder="Input Tanggal" id="getDates"
                                            style="border-radius: 12px 0 0 12px;">
                                        <span class="input-group-text border-0 bg-light"
                                            style="border-radius: 0 12px 12px 0;">
                                            <i data-acorn-icon="calendar" class="text-primary"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Toko Filter -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i data-acorn-icon="shop" class="me-1 text-primary"></i> Pilih Toko
                                    </label>
                                    <div class="select2-lg shadow-sm" style="border-radius: 12px;">
                                        <select class="form-select select2Class border-0" id="selectToko"
                                            data-placeholder="Pilih Toko">
                                            <option label="&nbsp;"></option>
                                            @if (Auth::user()->role == 'admin')
                                                <option value="semua">Semua Toko</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->kode }}">{{ $tk->nama_toko }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ Auth::user()->kode_toko }}" selected>
                                                    {{ Auth::user()->toko ? Auth::user()->toko->nama_toko : Auth::user()->kode_toko }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Karyawan Filter -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i data-acorn-icon="user" class="me-1 text-primary"></i> Pilih Karyawan
                                    </label>
                                    <div class="select2-lg shadow-sm" style="border-radius: 12px;">
                                        <select class="form-select select2Class border-0" id="selectKaryawan"
                                            data-placeholder="Pilih Karyawan" disabled>
                                            @if (Auth::user()->role != 'admin')
                                                <option value="{{ Auth::user()->id }}" selected>{{ Auth::user()->name }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted mb-2 d-block">Pilih Periode Laporan</label>
                                    <div class="d-flex gap-2">
                                        <button
                                            class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn active"
                                            id="hari" style="border-radius: 12px; font-weight: 600;">
                                            Hari Ini
                                        </button>
                                        <button class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn"
                                            id="bulan" style="border-radius: 12px; font-weight: 600;">
                                            Bulan Ini
                                        </button>
                                        <button class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn"
                                            id="tahun" style="border-radius: 12px; font-weight: 600;">
                                            Tahun Ini
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 d-flex align-items-end">
                                    <button class="btn btn-gradient-success btn-lg w-100 py-2.5 fw-bold shadow"
                                        id="hitungTotal" data-parameter="" disabled
                                        style="border-radius: 12px; font-size: 1.05rem;">
                                        <i data-acorn-icon="activity" class="me-2"></i> Hitung Total Penjualan & Laba
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overlay-spinner spinner_card d-none" style="border-radius: 16px;">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>

                    <!-- KPI Cards Row -->
                    <div id="kpiCardsSection" class="row g-3 mb-4 d-none">
                        <!-- Card 1: Penjualan Umum -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 transition-all card-kpi"
                                style="border-radius: 16px; border-left: 5px solid #3b82f6 !important; background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold text-muted text-uppercase small tracking-wider">Penjualan
                                            Umum</span>
                                        <div class="bg-blue-soft p-2.5 rounded-3">
                                            <i data-acorn-icon="cart" class="text-blue"
                                                style="width: 20px; height: 20px;"></i>
                                        </div>
                                    </div>
                                    <h3 class="mb-1 fw-bold text-dark text-blue" id="kpiTotalUmum">Rp 0</h3>
                                    <p class="mb-0 text-muted small"><span id="kpiCountUmum">0</span> Transaksi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Penjualan Grosir -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 transition-all card-kpi"
                                style="border-radius: 16px; border-left: 5px solid #10b981 !important; background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold text-muted text-uppercase small tracking-wider">Penjualan
                                            Grosir</span>
                                        <div class="bg-green-soft p-2.5 rounded-3">
                                            <i data-acorn-icon="tag" class="text-green"
                                                style="width: 20px; height: 20px;"></i>
                                        </div>
                                    </div>
                                    <h3 class="mb-1 fw-bold text-dark text-green" id="kpiTotalGrosir">Rp 0</h3>
                                    <p class="mb-0 text-muted small"><span id="kpiCountGrosir">0</span> Transaksi</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3: Total Gabungan -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 transition-all card-kpi"
                                style="border-radius: 16px; border-left: 5px solid #8b5cf6 !important; background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <span class="fw-bold text-muted text-uppercase small tracking-wider">Total
                                            Gabungan</span>
                                        <div class="bg-purple-soft p-2.5 rounded-3">
                                            <i data-acorn-icon="trend-up" class="text-purple"
                                                style="width: 20px; height: 20px;"></i>
                                        </div>
                                    </div>
                                    <h3 class="mb-1 fw-bold text-dark text-purple" id="kpiTotalGabungan">Rp 0</h3>
                                    <p class="mb-0 text-muted small">Semua Pembayaran</p>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4: Estimasi Keuntungan (Khusus Admin) -->
                        @if (Auth::user()->role == 'admin')
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100 transition-all card-kpi"
                                    style="border-radius: 16px; border-left: 5px solid #f59e0b !important; background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="fw-bold text-muted text-uppercase small tracking-wider">Laba
                                                Bersih (Profit)</span>
                                            <div class="bg-amber-soft p-2.5 rounded-3">
                                                <i data-acorn-icon="dollar" class="text-amber"
                                                    style="width: 20px; height: 20px;"></i>
                                            </div>
                                        </div>
                                        <h3 class="mb-1 fw-bold text-dark text-amber" id="kpiTotalLaba">Rp 0</h3>
                                        <p class="mb-0 text-muted small">Margin Bersih</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Card 4 untuk Kasir: Jam Shift Aktif / Info Selected -->
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card border-0 shadow-sm h-100 transition-all card-kpi"
                                    style="border-radius: 16px; border-left: 5px solid #64748b !important; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <span class="fw-bold text-muted text-uppercase small tracking-wider">Tanggal
                                                Laporan</span>
                                            <div class="bg-slate-soft p-2.5 rounded-3">
                                                <i data-acorn-icon="clock" class="text-slate"
                                                    style="width: 20px; height: 20px;"></i>
                                            </div>
                                        </div>
                                        <h3 class="mb-1 fw-bold text-dark text-slate" id="kpiSelectedDate">-</h3>
                                        <p class="mb-0 text-muted small" id="kpiSelectedPeriod">-</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Trend Chart Card -->
                    <div id="chartSection" class="card border-0 shadow-sm mb-4 d-none animate-fade-in"
                        style="border-radius: 16px; background: rgba(255, 255, 255, 0.9);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="card-title fw-bold text-dark mb-0">Grafik Penjualan</h5>
                                <span class="text-muted small">Visualisasi Tren Transaksi</span>
                            </div>
                            <div style="height: 300px; position: relative;">
                                <canvas id="laporanGrafik"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Table Data Container using elegant modern tabs -->
                    <div class="card border-0 shadow-sm mb-4"
                        style="border-radius: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.95);">
                        <div class="card-header border-0 bg-transparent pt-4 px-4 pb-0">
                            <ul class="nav nav-pills nav-fill modern-tabs p-1 bg-light rounded-3" id="laporanTabs"
                                role="tablist" style="border-radius: 12px;">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link active py-2.5 fw-bold position-relative d-flex align-items-center justify-content-center gap-2"
                                        id="umum-tab" data-bs-toggle="tab" data-bs-target="#umum-pane" type="button"
                                        role="tab" aria-controls="umum-pane" aria-selected="true"
                                        style="border-radius: 10px;">
                                        <i data-acorn-icon="cart" class="me-1"></i> Pembayaran Umum
                                        <span class="badge bg-blue text-white rounded-pill ms-1"
                                            id="badge-count-umum">0</span>
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link py-2.5 fw-bold position-relative d-flex align-items-center justify-content-center gap-2"
                                        id="grosir-tab" data-bs-toggle="tab" data-bs-target="#grosir-pane"
                                        type="button" role="tab" aria-controls="grosir-pane" aria-selected="false"
                                        style="border-radius: 10px;">
                                        <i data-acorn-icon="tag" class="me-1"></i> Pembayaran Grosir
                                        <span class="badge bg-green text-white rounded-pill ms-1"
                                            id="badge-count-grosir">0</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body p-4">
                            <div class="tab-content" id="laporanTabsContent">
                                <!-- Tab Pane 1: Umum -->
                                <div class="tab-pane fade show active animate-fade-in" id="umum-pane" role="tabpanel"
                                    aria-labelledby="umum-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped w-100" id="table-umum"
                                            style="border-radius: 12px; overflow: hidden;">
                                            <thead>
                                                <tr class="table-light">
                                                    <th class="py-3 border-0">Tanggal</th>
                                                    <th class="py-3 border-0">Kode Invoice</th>
                                                    <th class="py-3 border-0">Nama Kasir</th>
                                                    <th class="py-3 border-0">Nama Barang</th>
                                                    <th class="py-3 border-0">Pembayaran</th>
                                                    <th class="py-3 border-0">Jumlah</th>
                                                    <th class="py-3 border-0">Harga</th>
                                                    <th class="py-3 border-0">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody-umum"></tbody>
                                            <tfoot>
                                                <tr class="fw-bold bg-light">
                                                    <td colspan="7" class="text-end py-3 border-0">Total Penjualan
                                                        Umum:</td>
                                                    <td id="footer-total-umum"
                                                        class="py-3 border-0 text-blue font-weight-black"
                                                        style="font-size: 1.1rem;">Rp. 0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Tab Pane 2: Grosir -->
                                <div class="tab-pane fade animate-fade-in" id="grosir-pane" role="tabpanel"
                                    aria-labelledby="grosir-tab" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped w-100" id="table-grosir"
                                            style="border-radius: 12px; overflow: hidden;">
                                            <thead>
                                                <tr class="table-light">
                                                    <th class="py-3 border-0">Tanggal</th>
                                                    <th class="py-3 border-0">Kode Invoice</th>
                                                    <th class="py-3 border-0">Nama Kasir</th>
                                                    <th class="py-3 border-0">Nama Barang</th>
                                                    <th class="py-3 border-0">Pembayaran</th>
                                                    <th class="py-3 border-0">Jumlah</th>
                                                    <th class="py-3 border-0">Harga</th>
                                                    <th class="py-3 border-0">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody-grosir"></tbody>
                                            <tfoot>
                                                <tr class="fw-bold bg-light">
                                                    <td colspan="7" class="text-end py-3 border-0">Total Penjualan
                                                        Grosir:</td>
                                                    <td id="footer-total-grosir"
                                                        class="py-3 border-0 text-green font-weight-black"
                                                        style="font-size: 1.1rem;">Rp. 0</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>
@endsection

@push('script')
    <script>
        let globalRes = null;
        let laporanTableUmum = null;
        let laporanTableGrosir = null;

        function loadLaporanTable(id, metode, param) {
            let date = '';
            var getDates = $('#getDates').val();
            var extraDates = ekstrakInfoTanggal(getDates);
            const toko = $('#selectToko').val();
            const karyawan = $('#selectKaryawan').val();

            if (param == 'hari') {
                date = getDates;
            } else if (param == 'bulan') {
                date = extraDates.tahun + '-' + extraDates.bulan;
            } else if (param == 'tahun') {
                date = extraDates.tahun;
            }

            let tableVar = (id === '#table-umum') ? laporanTableUmum : laporanTableGrosir;

            if (tableVar) {
                tableVar.destroy();
                $(id + ' tbody').empty();
            }

            tableVar = $(id).DataTable({
                processing: true,
                serverSide: true,
                scrollY: '400px',
                scrollCollapse: true,
                scrollX: true,
                lengthMenu: [
                    [25, 50, 100, 200],
                    [25, 50, 100, 200]
                ],
                ajax: {
                    url: '/laporan/penjualan/show',
                    type: 'GET',
                    data: function(d) {
                        d.param = param;
                        d.date = date;
                        d.toko = toko;
                        d.karyawan = karyawan;
                        d.metode = metode;
                    }
                },
                columns: [{
                        data: 'tanggal',
                        render: function(data) {
                            return formatTanggal(data);
                        }
                    },
                    {
                        data: 'kode_invoice'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'nama_barang'
                    },
                    {
                        data: 'metode'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'harga',
                        render: function(data) {
                            return formatRupiah(data);
                        }
                    },
                    {
                        data: 'total',
                        render: function(data) {
                            return formatRupiah(data);
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    let api = this.api();

                    let totalPenjualan = api.column(7, {
                        page: 'current'
                    }).data().reduce(function(acc, val) {
                        return acc + parseFloat(typeof val === 'string' ? val.replace(/[^\d.-]/g, '') :
                            val);
                    }, 0);

                    $(api.column(7).footer()).html(formatRupiah(totalPenjualan));
                }
            });

            if (id === '#table-umum') {
                laporanTableUmum = tableVar;
            } else if (id === '#table-grosir') {
                laporanTableGrosir = tableVar;
            }
        }

        function formatTanggal(dateStr) {
            const d = new Date(dateStr);
            return d.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }) + ' Jam ' + d.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            }).replace('.', ':');
        }

        function formatRupiah(angka) {
            return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function removeData(chart) {
            chart.data = {
                labels: [],
                datasets: []
            };
            chart.update();
        }

        function addData(chart, label, newData) {
            chart.data = {
                labels: label,
                datasets: newData
            };
            chart.update();
        }

        function checkSelectKaryawan() {
            const val = $('#selectKaryawan').val();
            return !!(val && val !== '');
        }

        function ajaxData(method, url, data, param, optional) {
            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                beforeSend: function(response) {
                    $('.spinner_card').removeClass('d-none');
                },
                success: function(response) {
                    if (['hari', 'bulan', 'tahun'].includes(response.param)) {
                        getData(response, optional);
                    }
                    if (response.param == 'change') {
                        getDataChange(response, optional);
                    }
                    $('.spinner_card').addClass('d-none');
                },
                error: function(xhr) {
                    $('.spinner_card').addClass('d-none');
                },
            });
        }

        function updateActivePeriodButton(activeId) {
            $('.filter-period-btn').removeClass('active');
            $(activeId).addClass('active');
        }

        function updateChart(chart, res) {
            if (!chart) return;
            const laporan = res.data.laporan;
            const param = res.param;

            let groupedData = {};
            let labels = [];

            if (param === 'hari') {
                for (let i = 0; i < 24; i++) {
                    let hourStr = i.toString().padStart(2, '0') + ':00';
                    groupedData[hourStr] = {
                        umum: 0,
                        grosir: 0
                    };
                    labels.push(hourStr);
                }
                laporan.forEach(item => {
                    let date = new Date(item.tanggal_data);
                    let hourStr = date.getHours().toString().padStart(2, '0') + ':00';
                    if (groupedData[hourStr]) {
                        if (item.metode === 'umum') {
                            groupedData[hourStr].umum += parseFloat(item.harga_total);
                        } else {
                            groupedData[hourStr].grosir += parseFloat(item.harga_total);
                        }
                    }
                });
            } else if (param === 'bulan') {
                let dateObj = new Date($('#getDates').val());
                let year = dateObj.getFullYear();
                let month = dateObj.getMonth();
                let daysInMonth = new Date(year, month + 1, 0).getDate();

                for (let i = 1; i <= daysInMonth; i++) {
                    let dayStr = i.toString();
                    groupedData[dayStr] = {
                        umum: 0,
                        grosir: 0
                    };
                    labels.push(dayStr);
                }
                laporan.forEach(item => {
                    let date = new Date(item.tanggal_data);
                    let dayStr = date.getDate().toString();
                    if (groupedData[dayStr]) {
                        if (item.metode === 'umum') {
                            groupedData[dayStr].umum += parseFloat(item.harga_total);
                        } else {
                            groupedData[dayStr].grosir += parseFloat(item.harga_total);
                        }
                    }
                });
            } else if (param === 'tahun') {
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                monthNames.forEach(m => {
                    groupedData[m] = {
                        umum: 0,
                        grosir: 0
                    };
                    labels.push(m);
                });
                laporan.forEach(item => {
                    let date = new Date(item.tanggal_data);
                    let m = monthNames[date.getMonth()];
                    if (groupedData[m]) {
                        if (item.metode === 'umum') {
                            groupedData[m].umum += parseFloat(item.harga_total);
                        } else {
                            groupedData[m].grosir += parseFloat(item.harga_total);
                        }
                    }
                });
            }

            let dataUmum = [];
            let dataGrosir = [];

            labels.forEach(lbl => {
                dataUmum.push(groupedData[lbl].umum);
                dataGrosir.push(groupedData[lbl].grosir);
            });

            if (param === 'hari') {
                let firstActive = 8;
                let lastActive = 21;

                labels.forEach((lbl, idx) => {
                    let hour = parseInt(lbl.split(':')[0]);
                    if (dataUmum[idx] > 0 || dataGrosir[idx] > 0) {
                        if (hour < firstActive) firstActive = Math.max(0, hour - 1);
                        if (hour > lastActive) lastActive = Math.min(23, hour + 1);
                    }
                });

                labels = labels.slice(firstActive, lastActive + 1);
                dataUmum = dataUmum.slice(firstActive, lastActive + 1);
                dataGrosir = dataGrosir.slice(firstActive, lastActive + 1);
            }

            let datasets = [{
                    label: 'Umum',
                    backgroundColor: 'rgba(59, 130, 246, 0.75)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    data: dataUmum
                },
                {
                    label: 'Grosir',
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    data: dataGrosir
                }
            ];

            addData(chart, labels, datasets);
        }

        function getData(res, opt) {
            globalRes = res;
            const param = res.param;

            $('.tbody-umum').html('');
            $('.tbody-grosir').html('');

            const laporan = res.data.laporan;
            const dataKaryawan = res.karyawan;
            const isAdmin = "{{ Auth::user()->role }}" === 'admin';

            // Show KPI Cards Section
            $('#kpiCardsSection').removeClass('d-none');
            $('#chartSection').removeClass('d-none');

            // 1. Calculate Totals & Counts
            const totalUmum = res.data.total.umum;
            const modalUmum = res.data.total.modal_umum;
            const totalGrosir = res.data.total.grosir;
            const modalGrosir = res.data.total.modal_grosir;

            const totalGabungan = totalUmum + totalGrosir;
            const totalModal = modalUmum + modalGrosir;
            const totalKeuntungan = totalGabungan - totalModal;

            const countUmum = laporan.filter(item => item.metode === 'umum').length;
            const countGrosir = laporan.filter(item => item.metode === 'grosir').length;

            // 2. Set KPI content
            $('#kpiTotalUmum').html(formatRupiah(totalUmum));
            $('#kpiCountUmum').text(countUmum);
            $('#badge-count-umum').text(countUmum);

            $('#kpiTotalGrosir').html(formatRupiah(totalGrosir));
            $('#kpiCountGrosir').text(countGrosir);
            $('#badge-count-grosir').text(countGrosir);

            $('#kpiTotalGabungan').html(formatRupiah(totalGabungan));

            if (isAdmin) {
                $('#kpiTotalLaba').html(formatRupiah(totalKeuntungan));
            } else {
                const dateVal = $('#getDates').val();
                $('#kpiSelectedDate').text(dateVal);
                $('#kpiSelectedPeriod').text('Laporan: ' + param.toUpperCase());
            }

            // 3. Load DataTables using Server-Side pagination
            loadLaporanTable('#table-umum', 'umum', param);
            loadLaporanTable('#table-grosir', 'grosir', param);

            // 4. Update visual chart trend dynamically
            updateChart(opt, res);

            $('#hitungTotal').attr('disabled', false);
        }

        function getDataChange(res, opt) {
            var karyawan = $('#selectKaryawan');
            karyawan.attr('disabled', true);
            karyawan.html('');

            checkSelectKaryawan();

            if ("{{ Auth::user()->role }}" == 'admin') {
                karyawan.append('<option label="&nbsp;"> </option>');
                karyawan.append('<option value="semua" selected>Semua</option>');
                res.data.forEach(item => {
                    karyawan.append(`<option value="${item.id}">${item.name}</option>`);
                });
            }

            karyawan.attr('disabled', false);
        }

        function ekstrakInfoTanggal(tanggalStr) {
            const [tahunStr, bulanStr, hariStr] = tanggalStr.split('-');

            const tahun = parseInt(tahunStr, 10);
            const bulanUntukDateObj = parseInt(bulanStr, 10) - 1;
            const hari = parseInt(hariStr, 10);

            const tanggalObj = new Date(Date.UTC(tahun, bulanUntukDateObj, hari));

            const tahunHasil = tanggalObj.getUTCFullYear();
            const bulanRaw = tanggalObj.getUTCMonth();
            const bulanAngkaFormatted = (bulanRaw + 1).toString().padStart(2, '0');
            const namaBulan = new Intl.DateTimeFormat('id-ID', {
                month: 'long',
                timeZone: 'UTC'
            }).format(tanggalObj);

            return {
                tahun: tahunHasil,
                bulan: bulanAngkaFormatted,
                namaBulan: namaBulan
            };
        }

        $(document).ready(function() {
            checkSelectKaryawan();
            let chartBars = null;

            if (document.getElementById('laporanGrafik')) {
                const barChart = document.getElementById('laporanGrafik').getContext('2d');
                chartBars = new Chart(barChart, {
                    type: 'bar',
                    options: {
                        cornerRadius: parseInt(Globals.borderRadiusMd),
                        plugins: {
                            crosshair: false,
                            datalabels: {
                                display: false
                            },
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    display: true,
                                    lineWidth: 1,
                                    color: Globals.separatorLight,
                                    drawBorder: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    padding: 20,
                                },
                            }, ],
                            xAxes: [{
                                gridLines: {
                                    display: false
                                },
                            }, ],
                        },
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: ChartsExtend.LegendLabels(),
                        },
                        tooltips: ChartsExtend.ChartTooltip(),
                    },
                    data: {
                        labels: [],
                        datasets: [],
                    },
                });
            }

            $('#getDates').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            }).datepicker('update', new Date());

            $('#hari').click(function(e) {
                e.preventDefault();
                updateActivePeriodButton('#hari');
                var date = $('#getDates').val();
                var selectToko = $('#selectToko').val();
                var selectKaryawan = $('#selectKaryawan').val();
                $('#hitungTotal').attr('data-parameter', 'tanggal');
                $('#hitungTotal').attr('disabled', true);
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: date,
                    param: 'hari',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'hari', chartBars);
            });

            $('#bulan').click(function(e) {
                e.preventDefault();
                updateActivePeriodButton('#bulan');
                var date = $('#getDates').val();
                var get_date = ekstrakInfoTanggal(date);

                var selectToko = $('#selectToko').val();
                var selectKaryawan = $('#selectKaryawan').val();
                $('#hitungTotal').attr('data-parameter', 'bulan');
                $('#hitungTotal').attr('disabled', true);
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: get_date.tahun + '-' + get_date.bulan,
                    param: 'bulan',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'bulan', chartBars);
            });

            $('#tahun').click(function(e) {
                e.preventDefault();
                updateActivePeriodButton('#tahun');
                var date = $('#getDates').val();
                var get_date = ekstrakInfoTanggal(date);

                var selectToko = $('#selectToko').val();
                var selectKaryawan = $('#selectKaryawan').val();
                $('#hitungTotal').attr('data-parameter', 'tahun');
                $('#hitungTotal').attr('disabled', true);
                $('#laporan-body').empty();

                ajaxData('GET', '/laporan/penjualan/show', {
                    date: get_date.tahun,
                    param: 'tahun',
                    toko: selectToko,
                    karyawan: selectKaryawan,
                }, 'tahun', chartBars);
            });

            $('#selectToko').change(function(e) {
                e.preventDefault();
                var selected = $(this).val();
                var data = {
                    selected: selected,
                    parameters: 'karyawan'
                };
                ajaxData('get', '/laporan/penjualan/create', data, 'change');
            });

            $('#hitungTotal').click(function() {
                if (!globalRes) {
                    Swal.fire('Gagal', 'Data belum dimuat.', 'error');
                    return;
                }

                const totalUmum = globalRes.data.total.umum;
                const modalUmum = globalRes.data.total.modal_umum;
                const totalGrosir = globalRes.data.total.grosir;
                const modalGrosir = globalRes.data.total.modal_grosir;

                const totalGabungan = totalUmum + totalGrosir;
                const totalModal = modalUmum + modalGrosir;
                const totalKeuntungan = totalGabungan - totalModal;

                const parameter_date = $('#hitungTotal').attr('data-parameter');
                const date = $('#getDates').val();
                const nama_toko = $('#selectToko').find('option:selected').text();
                const infoTanggal1 = ekstrakInfoTanggal(date);

                let data_date;
                if (parameter_date === 'tanggal') {
                    data_date = nama_toko + ' Tanggal ' + date;
                } else if (parameter_date === 'bulan') {
                    data_date = nama_toko + ' Bulan ' + infoTanggal1.namaBulan + ' Tahun ' + infoTanggal1
                        .tahun;
                } else if (parameter_date === 'tahun') {
                    data_date = nama_toko + ' Tahun ' + infoTanggal1.tahun;
                } else {
                    data_date = 'error';
                }

                let htmlAdds = '';
                if ("{{ Auth::user()->role }}" === 'admin') {
                    htmlAdds = `<div style="text-align: left; font-family: inherit;">
                    <div class="d-flex justify-content-between mb-2"><b>Pembayaran Umum:</b> <span>${formatRupiah(totalUmum)}</span></div>
                    <div class="d-flex justify-content-between mb-2"><b>Pembayaran Grosir:</b> <span>${formatRupiah(totalGrosir)}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2"><b>Total Gabungan:</b> <span>${formatRupiah(totalGabungan)}</span></div>
                    <div class="d-flex justify-content-between text-success"><b>Total Keuntungan (Laba):</b> <span>${formatRupiah(totalKeuntungan)}</span></div>
                </div>`;
                } else {
                    htmlAdds = `<div style="text-align: left; font-family: inherit;">
                    <div class="d-flex justify-content-between mb-2"><b>Pembayaran Umum:</b> <span>${formatRupiah(totalUmum)}</span></div>
                    <div class="d-flex justify-content-between mb-2"><b>Pembayaran Grosir:</b> <span>${formatRupiah(totalGrosir)}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between"><b>Total Gabungan:</b> <span>${formatRupiah(totalGabungan)}</span></div>
                </div>`;
                }

                Swal.fire({
                    title: 'Total Penjualan ' + data_date,
                    html: htmlAdds,
                    icon: 'success',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: 'var(--primary)'
                });
            });

            // Automatically load today's report on load
            $('#hari').click();
        });
    </script>
@endpush
