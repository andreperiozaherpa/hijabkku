@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Custom CSS Styles -->
            <style>
                .filter-period-btn {
                    transition: all 0.2s ease-in-out;
                    border-color: var(--primary);
                    color: var(--primary);
                }

                .filter-period-btn:hover {
                    background-color: var(--primary);
                    border-color: var(--primary);
                    color: #fff;
                }

                .filter-period-btn.active {
                    background-color: var(--primary) !important;
                    border-color: var(--primary) !important;
                    color: #fff !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
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
                    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
                }

                .btn-gradient-success:disabled {
                    background: #cbd5e1;
                    color: #64748b;
                    cursor: not-allowed;
                }

                .card-kpi {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }

                .card-kpi:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05) !important;
                }

                .bg-blue-soft { background-color: rgba(59, 130, 246, 0.1); }
                .bg-green-soft { background-color: rgba(16, 185, 129, 0.1); }
                .bg-purple-soft { background-color: rgba(139, 92, 246, 0.1); }
                .bg-amber-soft { background-color: rgba(245, 158, 11, 0.1); }
                .bg-slate-soft { background-color: rgba(100, 116, 139, 0.1); }

                .text-blue { color: #2563eb !important; }
                .text-green { color: #059669 !important; }
                .text-purple { color: #7c3aed !important; }
                .text-amber { color: #d97706 !important; }
                .text-slate { color: #475569 !important; }

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

                .table> :not(caption)>*>* {
                    padding: 1rem 0.75rem;
                }

                table.dataTable {
                    border-collapse: collapse !important;
                }

                .clickable-row {
                    cursor: pointer;
                    transition: background-color 0.15s ease;
                }
                .clickable-row:hover {
                    background-color: rgba(var(--primary-rgb), 0.05) !important;
                }
            </style>

            <!-- Header Section -->
            <div class="page-title-container mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4 fw-bold text-dark" id="title">Laporan Barang & Produk</h1>
                        <p class="text-muted mb-0">Analisis statistik peringkat produk, volume barang terjual, serta rasio profitabilitas barang</p>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 16px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-end">
                                <!-- Tanggal Filter -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i data-acorn-icon="calendar" class="me-1 text-primary"></i> Pilih Tanggal
                                    </label>
                                    <div class="input-group input-group-lg border-0 shadow-sm rounded-3">
                                        <input type="text" class="form-control border-0 bg-light" placeholder="Input Tanggal" id="getDates" style="border-radius: 12px 0 0 12px;">
                                        <span class="input-group-text border-0 bg-light" style="border-radius: 0 12px 12px 0;">
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
                                        <select class="form-select select2Class border-0" id="selectToko" data-placeholder="Pilih Toko">
                                            <option label="&nbsp;"></option>
                                            @if (Auth::user()->role == 'admin')
                                                <option value="semua">Semua Toko</option>
                                                @foreach ($toko as $tk)
                                                    <option value="{{ $tk->kode }}">{{ $tk->nama_toko }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ Auth::user()->kode_toko }}" selected>
                                                    {{ Auth::user()->toko ? Auth::user()->toko->nama_toko : Auth::user()->kode_toko }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <!-- Metode Filter -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label fw-bold text-muted mb-2">
                                        <i data-acorn-icon="tag" class="me-1 text-primary"></i> Pilih Metode
                                    </label>
                                    <div class="select2-lg shadow-sm" style="border-radius: 12px;">
                                        <select class="form-select select2Class border-0" id="selectMetode" data-placeholder="Pilih Metode">
                                            <option value="semua" selected>Semua Metode</option>
                                            <option value="umum">Umum (Eceran)</option>
                                            <option value="grosir">Grosir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons Section -->
                            <div class="row g-3 mt-2 align-items-center">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-muted mb-2 d-block">Pilih Periode Laporan</label>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn active" id="hari" style="border-radius: 12px; font-weight: 600;">
                                            Hari Ini
                                        </button>
                                        <button class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn" id="bulan" style="border-radius: 12px; font-weight: 600;">
                                            Bulan Ini
                                        </button>
                                        <button class="btn btn-outline-primary btn-lg flex-fill py-2.5 filter-period-btn" id="tahun" style="border-radius: 12px; font-weight: 600;">
                                            Tahun Ini
                                        </button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 d-flex align-items-end">
                                    <button class="btn btn-gradient-success btn-lg w-100 py-2.5 fw-bold shadow" id="hitungTotal" data-parameter="" disabled style="border-radius: 12px; font-size: 1.05rem;">
                                        <i data-acorn-icon="activity" class="me-2"></i> Hitung Laporan Barang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Summary Cards Section -->
            <div id="kpiCardsSection" class="row g-3 mb-4 d-none">
                <!-- Card 1: Total Jenis Barang -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 transition-all card-kpi" style="border-radius: 16px; border-left: 5px solid #3b82f6 !important; background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold text-muted text-uppercase small tracking-wider">Jenis Barang Terjual</span>
                                <div class="bg-blue-soft p-2.5 rounded-3">
                                    <i data-acorn-icon="package" class="text-blue" style="width: 20px; height: 20px;"></i>
                                </div>
                            </div>
                            <h3 class="mb-1 fw-bold text-dark text-blue" id="kpiTotalJenis">0 Jenis</h3>
                            <p class="mb-0 text-muted small">Jenis Item Unik</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Total Unit Terjual -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 transition-all card-kpi" style="border-radius: 16px; border-left: 5px solid #10b981 !important; background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold text-muted text-uppercase small tracking-wider">Volume Terjual</span>
                                <div class="bg-green-soft p-2.5 rounded-3">
                                    <i data-acorn-icon="cart" class="text-green" style="width: 20px; height: 20px;"></i>
                                </div>
                            </div>
                            <h3 class="mb-1 fw-bold text-dark text-green" id="kpiTotalUnit">0 pcs</h3>
                            <p class="mb-0 text-muted small">Total Kuantitas (Qty)</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Omzet Penjualan Barang -->
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 transition-all card-kpi" style="border-radius: 16px; border-left: 5px solid #8b5cf6 !important; background: linear-gradient(135deg, #ffffff 0%, #f5f3ff 100%);">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold text-muted text-uppercase small tracking-wider">Total Nilai Omzet</span>
                                <div class="bg-purple-soft p-2.5 rounded-3">
                                    <i data-acorn-icon="trend-up" class="text-purple" style="width: 20px; height: 20px;"></i>
                                </div>
                            </div>
                            <h3 class="mb-1 fw-bold text-dark text-purple" id="kpiTotalOmzet">Rp 0</h3>
                            <p class="mb-0 text-muted small">Akumulasi Penjualan</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Estimasi Keuntungan (Khusus Admin) -->
                @if (Auth::user()->role == 'admin')
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 transition-all card-kpi" style="border-radius: 16px; border-left: 5px solid #f59e0b !important; background: linear-gradient(135deg, #ffffff 0%, #fffbeb 100%);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="fw-bold text-muted text-uppercase small tracking-wider">Laba Kotor Barang</span>
                                    <div class="bg-amber-soft p-2.5 rounded-3">
                                        <i data-acorn-icon="dollar" class="text-amber" style="width: 20px; height: 20px;"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold text-dark text-amber" id="kpiTotalLaba">Rp 0</h3>
                                <p class="mb-0 text-muted small">Margin Keuntungan</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Card 4 untuk Non-Admin: Periode Info -->
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 transition-all card-kpi" style="border-radius: 16px; border-left: 5px solid #64748b !important; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="fw-bold text-muted text-uppercase small tracking-wider">Periode Aktif</span>
                                    <div class="bg-slate-soft p-2.5 rounded-3">
                                        <i data-acorn-icon="clock" class="text-slate" style="width: 20px; height: 20px;"></i>
                                    </div>
                                </div>
                                <h3 class="mb-1 fw-bold text-dark text-slate" id="kpiSelectedDate">-</h3>
                                <p class="mb-0 text-muted small" id="kpiSelectedPeriod">-</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Visual Chart Ranking -->
            <div id="chartSection" class="card border-0 shadow-sm mb-4 d-none animate-fade-in" style="border-radius: 16px; background: rgba(255, 255, 255, 0.9);">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title fw-bold text-dark mb-0">Grafik Peringkat 10 Barang Terlaris (by Qty)</h5>
                    </div>
                    <div class="chart-container" style="position: relative; height: 350px;">
                        <canvas id="laporanBarangChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table Rangking Barang -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden; background: rgba(255, 255, 255, 0.95);">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped w-100" id="table-barang" style="border-radius: 12px; overflow: hidden;">
                            <thead>
                                <tr class="table-light">
                                    <th class="py-3 border-0">Nama Barang</th>
                                    <th class="py-3 border-0">Kode Barang</th>
                                    <th class="py-3 border-0">Total Qty</th>
                                    <th class="py-3 border-0">Total Omzet</th>
                                    <th class="py-3 border-0">Harga Avg</th>
                                    <th class="py-3 border-0">Jml Transaksi</th>
                                    @if (Auth::user()->role == 'admin')
                                        <th class="py-3 border-0 text-amber">Laba Kotor</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal Pop-up -->
        <div class="modal fade" id="detailBarangModal" tabindex="-1" aria-labelledby="detailBarangModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                    <div class="modal-header border-0 bg-light p-4">
                        <div>
                            <h4 class="modal-title fw-bold text-dark" id="detailBarangModalLabel">Riwayat Transaksi Produk</h4>
                            <p class="text-muted mb-0 small" id="detailBarangSublabel">Menampilkan detail transaksi penjualan unit produk</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <!-- Loading spinner -->
                        <div id="modalLoading" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2">Mengambil riwayat transaksi...</p>
                        </div>

                        <!-- Content wrapper -->
                        <div id="modalContent">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped w-100" id="table-detail-barang" style="font-size: 0.9rem;">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Tanggal</th>
                                            <th>Invoice</th>
                                            <th>Toko</th>
                                            <th>Kasir</th>
                                            <th>Metode</th>
                                            <th>Qty</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailTransactionsBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 10px;">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script>
        let globalChart = null;
        let barangTable = null;
        let detailTable = null;

        function loadBarangTable(param) {
            let date = '';
            var getDates = $('#getDates').val();
            var extraDates = ekstrakInfoTanggal(getDates);
            const toko = $('#selectToko').val();
            const metode = $('#selectMetode').val();
            const isAdmin = "{{ Auth::user()->role }}" === 'admin';

            if (param == 'hari') {
                date = getDates;
            } else if (param == 'bulan') {
                date = extraDates.tahun + '-' + extraDates.bulan;
            } else if (param == 'tahun') {
                date = extraDates.tahun;
            }

            if (barangTable) {
                barangTable.destroy();
                $('#table-barang tbody').empty();
            }

            let tableColumns = [
                {
                    data: 'nama_barang',
                    className: 'fw-bold text-dark clickable-row'
                },
                { data: 'kode_barang' },
                { 
                    data: 'total_terjual',
                    render: function(data) {
                        return '<span class="badge bg-green-soft text-green px-2.5 py-1.5 font-weight-bold" style="font-size: 0.85rem;">' + data + ' pcs</span>';
                    }
                },
                { 
                    data: 'total_omzet',
                    render: function(data) {
                        return formatRupiah(data);
                    }
                },
                { 
                    data: 'avg_harga',
                    render: function(data) {
                        return formatRupiah(Math.round(data));
                    }
                },
                { data: 'total_transaksi' }
            ];

            if (isAdmin) {
                tableColumns.push({
                    data: 'laba',
                    render: function(data) {
                        return '<span class="text-amber font-weight-black">' + formatRupiah(data) + '</span>';
                    }
                });
            }

            barangTable = $('#table-barang').DataTable({
                processing: true,
                serverSide: true,
                scrollY: '450px',
                scrollCollapse: true,
                scrollX: true,
                lengthMenu: [
                    [25, 50, 100, 200],
                    [25, 50, 100, 200]
                ],
                ajax: {
                    url: '/laporan/barang/show',
                    type: 'GET',
                    data: function(d) {
                        d.param = param;
                        d.date = date;
                        d.toko = toko;
                        d.metode = metode;
                    }
                },
                columns: tableColumns,
                order: [[2, 'desc']] // Order by Qty (Column index 2)
            });

            // Handle clicking product name to show popup modal
            $('#table-barang tbody').on('click', 'td.clickable-row', function() {
                var data = barangTable.row($(this).parents('tr')).data();
                showDetailPopup(data.kode_barang, data.nama_barang, param, date, toko);
            });
        }

        function showDetailPopup(kodeBarang, namaBarang, param, date, toko) {
            $('#detailBarangModalLabel').text(namaBarang);
            $('#detailBarangSublabel').text('Riwayat transaksi untuk produk kode: ' + kodeBarang);
            
            // Destroy existing detailed table if any
            if (detailTable) {
                detailTable.destroy();
                $('#detailTransactionsBody').empty();
            }

            $('#modalLoading').removeClass('d-none');
            $('#modalContent').addClass('d-none');

            // Open Modal
            var myModal = new bootstrap.Modal(document.getElementById('detailBarangModal'), {});
            myModal.show();

            $.ajax({
                url: '/laporan/barang/detail',
                type: 'GET',
                cache: false,
                data: {
                    kode_barang: kodeBarang,
                    param: param,
                    date: date,
                    toko: toko
                },
                success: function(res) {
                    $('#modalLoading').addClass('d-none');
                    $('#modalContent').removeClass('d-none');

                    let rows = '';
                    res.data.forEach(item => {
                        rows += `<tr>
                            <td>${formatTanggal(item.tanggal_transaksi)}</td>
                            <td><span class="badge bg-light text-dark font-weight-bold">${item.kode_invoice}</span></td>
                            <td>${item.nama_toko}</td>
                            <td>${item.kasir}</td>
                            <td><span class="badge ${item.metode === 'umum' ? 'bg-blue-soft text-blue' : 'bg-green-soft text-green'}">${item.metode.toUpperCase()}</span></td>
                            <td class="fw-bold">${item.jumlah} pcs</td>
                            <td>${formatRupiah(item.harga)}</td>
                            <td class="fw-bold">${formatRupiah(item.harga_total)}</td>
                        </tr>`;
                    });
                    
                    $('#detailTransactionsBody').html(rows);

                    // Initialize DataTable for popup modal
                    detailTable = $('#table-detail-barang').DataTable({
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                        order: [[0, 'desc']]
                    });
                },
                error: function() {
                    $('#modalLoading').addClass('d-none');
                    $('#detailTransactionsBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat detail transaksi.</td></tr>');
                }
            });
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

        function ekstrakInfoTanggal(tanggalStr) {
            if (!tanggalStr) return { namaBulan: '', tahun: '', bulan: '' };
            const [tahunStr, bulanStr, hariStr] = tanggalStr.split('-');
            const tahun = parseInt(tahunStr, 10);
            const bulanUntukDateObj = parseInt(bulanStr, 10) - 1;
            const hari = parseInt(hariStr, 10);

            const tanggalObj = new Date(Date.UTC(tahun, bulanUntukDateObj, hari));
            const tahunHasil = tanggalObj.getUTCFullYear();
            const bulanRaw = tanggalObj.getUTCMonth();
            const bulanAngkaFormatted = (bulanRaw + 1).toString().padStart(2, '0');
            const namaBulan = new Intl.DateTimeFormat('id-ID', { month: 'long' }).format(tanggalObj);
            
            return {
                namaBulan: namaBulan,
                tahun: tahunHasil.toString(),
                bulan: bulanAngkaFormatted
            };
        }

        let globalRes = null;

        function updateChart(dataItems) {
            const ctx = document.getElementById('laporanBarangChart').getContext('2d');

            let labels = [];
            let dataQty = [];

            dataItems.forEach(item => {
                labels.push(item.nama_barang.substring(0, 22) + (item.nama_barang.length > 22 ? '...' : ''));
                dataQty.push(parseInt(item.total_terjual));
            });

            if (globalChart) {
                globalChart.destroy();
            }

            globalChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Volume Terjual (Pcs)',
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            hoverBackgroundColor: '#059669',
                            borderWidth: 0,
                            data: dataQty
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });
        }

        function loadLaporanBarang() {
            const param = $('.filter-period-btn.active').attr('id') || 'hari';
            let date = '';
            var getDates = $('#getDates').val();
            var extraDates = ekstrakInfoTanggal(getDates);
            const toko = $('#selectToko').val();
            const metode = $('#selectMetode').val();
            const isAdmin = "{{ Auth::user()->role }}" === 'admin';

            if (param == 'hari') {
                date = getDates;
            } else if (param == 'bulan') {
                date = extraDates.tahun + '-' + extraDates.bulan;
            } else if (param == 'tahun') {
                date = extraDates.tahun;
            }

            $('#hitungTotal').attr('disabled', true);

            $.ajax({
                url: '/laporan/barang/show',
                type: 'GET',
                cache: false,
                data: {
                    param: param,
                    date: date,
                    toko: toko,
                    metode: metode
                },
                success: function(res) {
                    globalRes = res;
                    
                    // Show Sections
                    $('#kpiCardsSection').removeClass('d-none');
                    $('#chartSection').removeClass('d-none');

                    // Fill KPIs
                    $('#kpiTotalJenis').text(res.data.total.jenis + ' Jenis');
                    $('#kpiTotalUnit').text(res.data.total.unit + ' pcs');
                    $('#kpiTotalOmzet').text(formatRupiah(res.data.total.omzet));
                    
                    if (isAdmin) {
                        $('#kpiTotalLaba').text(formatRupiah(res.data.total.laba));
                    } else {
                        $('#kpiSelectedDate').text(getDates);
                        $('#kpiSelectedPeriod').text('Laporan: ' + param.toUpperCase());
                    }

                    // Load data table
                    loadBarangTable(param);

                    // Load charts
                    updateChart(res.data.chart);

                    $('#hitungTotal').attr('disabled', false);
                },
                error: function() {
                    $('#hitungTotal').attr('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal mengambil data laporan barang.'
                    });
                }
            });
        }

        $(document).ready(function() {
            // Initialize Datepicker
            $('#getDates').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
            }).datepicker('update', new Date()).on('changeDate', function(e) {
                loadLaporanBarang();
            });

            // Handle dropdown change event to auto reload
            $('#selectToko, #selectMetode').on('change', function() {
                loadLaporanBarang();
            });

            // Handle period button click to auto reload
            $('.filter-period-btn').on('click', function(e) {
                e.preventDefault();
                $('.filter-period-btn').removeClass('active');
                $(this).addClass('active');
                loadLaporanBarang();
            });

            // Handle Hitung Total click (Shows Summary Dialog)
            $('#hitungTotal').on('click', function(e) {
                e.preventDefault();
                if (!globalRes) {
                    Swal.fire('Gagal', 'Data belum dimuat.', 'error');
                    return;
                }

                const totalUnit = globalRes.data.total.unit;
                const totalOmzet = globalRes.data.total.omzet;
                const totalJenis = globalRes.data.total.jenis;
                const totalLaba = globalRes.data.total.laba;

                const param = $('.filter-period-btn.active').attr('id') || 'hari';
                const date = $('#getDates').val();
                const nama_toko = $('#selectToko').find('option:selected').text();
                const infoTanggal1 = ekstrakInfoTanggal(date);

                let data_date;
                if (param === 'hari') {
                    data_date = nama_toko + ' Tanggal ' + date;
                } else if (param === 'bulan') {
                    data_date = nama_toko + ' Bulan ' + infoTanggal1.namaBulan + ' Tahun ' + infoTanggal1.tahun;
                } else if (param === 'tahun') {
                    data_date = nama_toko + ' Tahun ' + infoTanggal1.tahun;
                }

                let htmlAdds = '';
                if ("{{ Auth::user()->role }}" === 'admin') {
                    htmlAdds = `<div style="text-align: left; font-family: inherit;">
                        <div class="d-flex justify-content-between mb-2"><b>Jenis Barang Terjual:</b> <span>${totalJenis} Jenis</span></div>
                        <div class="d-flex justify-content-between mb-2"><b>Total Unit Terjual:</b> <span>${totalUnit} pcs</span></div>
                        <div class="d-flex justify-content-between mb-2"><b>Total Omzet:</b> <span>${formatRupiah(totalOmzet)}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between text-success"><b>Total Keuntungan (Laba):</b> <span>${formatRupiah(totalLaba)}</span></div>
                    </div>`;
                } else {
                    htmlAdds = `<div style="text-align: left; font-family: inherit;">
                        <div class="d-flex justify-content-between mb-2"><b>Jenis Barang Terjual:</b> <span>${totalJenis} Jenis</span></div>
                        <div class="d-flex justify-content-between mb-2"><b>Total Unit Terjual:</b> <span>${totalUnit} pcs</span></div>
                        <div class="d-flex justify-content-between mb-2"><b>Total Omzet:</b> <span>${formatRupiah(totalOmzet)}</span></div>
                    </div>`;
                }

                Swal.fire({
                    title: 'Ringkasan Laporan Barang',
                    html: `<div class="mb-3 text-center text-muted small">${data_date}</div>` + htmlAdds,
                    icon: 'success',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: 'var(--primary)'
                });
            });

            // Trigger default load
            $('#hari').trigger('click');
        });
    </script>
@endpush
