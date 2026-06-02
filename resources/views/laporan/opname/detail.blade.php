@extends('layouts.main')

@section('main')
    <style>
        /* Modern Sleek Visual Layout Styles */
        .highlight-variance {
            background-color: rgba(220, 53, 69, 0.02) !important;
            border-left: 4px solid #dc3545 !important;
        }
        .sticky-action-bar {
            position: sticky;
            bottom: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 15px 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            z-index: 1000;
            border: 1px solid rgba(0, 0, 0, 0.05);
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        html[data-theme="dark"] .sticky-action-bar {
            background: rgba(30, 30, 36, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        .audit-log-item {
            font-size: 0.82rem;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04);
            transition: background 0.2s ease;
        }
        html[data-theme="dark"] .audit-log-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }
        .audit-log-item:hover {
            background-color: rgba(0, 0, 0, 0.015);
        }
        html[data-theme="dark"] .audit-log-item:hover {
            background-color: rgba(255, 255, 255, 0.015);
        }
        .cursor-pointer {
            cursor: pointer;
        }
        .border-dashed {
            border: 1px dashed currentColor;
        }
        .card-stat {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04) !important;
        }
        .scanner-glow {
            animation: pulse-glow 2s infinite alternate;
        }
        @keyframes pulse-glow {
            0% { box-shadow: 0 0 5px rgba(13, 202, 240, 0.2); }
            100% { box-shadow: 0 0 15px rgba(13, 202, 240, 0.6); }
        }
        /* High-ergonomics editable cell styling */
        .editable-cell {
            cursor: pointer !important;
            transition: all 0.2s ease-in-out;
        }
        .editable-cell:hover {
            background-color: rgba(13, 110, 253, 0.08) !important; /* Premium soft blue glow */
        }
        .editable-cell:hover .edit-qty-trigger {
            background-color: #0d6efd !important; /* Solid blue badge on cell hover */
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
            transform: scale(1.05);
        }
    </style>

    <main>
        <div class="container-fluid px-4 pb-5">
            <!-- Header Sesi SO -->
            <div class="page-title-container mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <h1 class="mb-0 pb-0 display-4 font-weight-bold text-dark">Stock Opname Sesi</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('stock.opname') }}">Opname</a></li>
                                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $session->nomor_so }}</li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-12 col-md-6 text-md-end">
                        <span class="fs-6 me-2 text-muted font-weight-bold">Status Sesi:</span>
                        @php
                            $badges = [
                                'Draft' => 'bg-secondary text-white',
                                'Counting' => 'bg-info text-white',
                                'Recount' => 'bg-warning text-dark',
                                'Review' => 'bg-primary text-white',
                                'Approved' => 'bg-success text-white',
                                'Posted' => 'bg-dark text-white'
                            ];
                            $badge = $badges[$session->status] ?? 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badge }} fs-6 px-3 py-2" id="sessionStatusLabel">{{ $session->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Summary Cards Dashboard -->
            <div class="row g-3 mb-4">
                <div class="col-6 @if(Auth::user()->role == 'admin') col-lg-2 @else col-lg-3 @endif">
                    <div class="card h-100 border-0 shadow-sm card-stat bg-light">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase font-weight-bold">Total SKU</div>
                            <div class="fs-3 font-weight-bold text-dark" id="cardTotalSku">{{ $total_sku }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 @if(Auth::user()->role == 'admin') col-lg-2 @else col-lg-3 @endif">
                    <div class="card h-100 border-0 shadow-sm card-stat">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase font-weight-bold">Sudah Dihitung</div>
                            <div class="fs-3 font-weight-bold text-success" id="cardCounted">{{ $counted }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 @if(Auth::user()->role == 'admin') col-lg-2 @else col-lg-3 @endif">
                    <div class="card h-100 border-0 shadow-sm card-stat">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase font-weight-bold">Belum Dihitung</div>
                            <div class="fs-3 font-weight-bold text-warning" id="cardRemaining">{{ $remaining }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 @if(Auth::user()->role == 'admin') col-lg-2 @else col-lg-3 @endif">
                    <div class="card h-100 border-0 shadow-sm card-stat">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase font-weight-bold">SKU Selisih</div>
                            <div class="fs-3 font-weight-bold text-danger" id="cardVarianceItems">{{ $variance_items }}</div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->role == 'admin')
                <div class="col-12 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm card-stat bg-outline-danger">
                        <div class="card-body py-3">
                            <div class="text-muted small text-uppercase font-weight-bold">Nilai Selisih Estimasi</div>
                            <div class="fs-3 font-weight-bold text-danger" id="cardVarianceValue">
                                Rp. {{ number_format($variance_value, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Detail Sesi Metadata -->
            <div class="row g-4">
                <div class="col-12">
                    <!-- Session Details Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small">Toko / Cabang</div>
                                    <div class="font-weight-bold text-dark">{{ $session->toko ? $session->toko->nama_toko : '-' }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small">Petugas Lapangan</div>
                                    <div class="font-weight-bold text-dark">{{ $session->petugas ? $session->petugas->name : '-' }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small">Supervisor/Validator</div>
                                    <div class="font-weight-bold text-dark" id="supervisorLabel">{{ $session->supervisor ? $session->supervisor->name : '-' }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted small">Waktu Mulai</div>
                                    <div class="font-weight-bold text-primary">{{ $session->tanggal_mulai ? \Carbon\Carbon::parse($session->tanggal_mulai)->format('d M Y H:i') : 'Belum Dimulai' }}</div>
                                </div>
                                @if($session->notes)
                                    <div class="col-12 border-top pt-2">
                                        <div class="text-muted small">Catatan Sesi</div>
                                        <div class="text-secondary small">{{ $session->notes }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Barcode Scanner Flow Area -->
                    @if(in_array($session->status, ['Counting', 'Recount']))
                        <div class="card mb-4 border border-info bg-gradient-info-light scanner-glow">
                            <div class="card-body py-3 d-flex flex-column flex-md-row align-items-center justify-content-between">
                                <div class="mb-3 mb-md-0">
                                    <h5 class="mb-1 text-info font-weight-bold d-flex align-items-center">
                                        <i data-acorn-icon="scanner" class="me-2"></i> Area Barcode Scanner Aktif
                                    </h5>
                                    <p class="mb-0 small text-muted">Letakkan kursor pada kolom input di samping, lalu mulailah men-scan barcode produk.</p>
                                </div>
                                <div class="w-100 w-md-50">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white text-info border-end-0"><i data-acorn-icon="barcode"></i></span>
                                        <input type="text" class="form-control form-control-lg border-start-0" id="scannerInput" placeholder="Arahkan scan barcode di sini..." autofocus>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Filter & Items List Table (Full Width for Premium Spaciousness) -->
                    <div class="card mb-5 border-0 shadow-sm">
                        <div class="card-header border-bottom py-3">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-6">
                                    <h5 class="mb-0 font-weight-bold text-dark">Daftar Item Opname Fisik</h5>
                                </div>
                                <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                                    <!-- Dynamic Round Selector Tabs -->
                                    <div class="btn-group" role="group" id="roundTabs">
                                        <button type="button" class="btn btn-sm btn-outline-primary {{ $active_round == 1 ? 'active' : '' }}" data-round="1">Round 1</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary {{ $active_round == 2 ? 'active' : '' }}" data-round="2" id="tabRound2">Round 2</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary {{ $active_round == 3 ? 'active' : '' }}" data-round="3" id="tabRound3">Round 3</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary {{ $active_round === 'final' ? 'active' : '' }}" data-round="final">Final Count</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Search Bar (No keyup) -->
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" placeholder="Cari Nama Barang atau Barcode... (Tekan Enter atau klik tombol Cari)" id="tableSearchQuery">
                                        <button class="btn btn-sm btn-primary" type="button" id="btnTableSearch">
                                            Cari
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" id="btnResetTableSearch">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Filters inside details -->
                            <div class="row g-3 mb-4 align-items-end">
                                <div class="col-6 col-md-3">
                                    <label class="form-label small font-weight-bold text-muted">Kategori Barang</label>
                                    <select class="form-select form-select-sm" id="tableFilterCategory">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="form-label small font-weight-bold text-muted">Rak / Lokasi</label>
                                    <select class="form-select form-select-sm" id="tableFilterRack">
                                        <option value="">Semua Lokasi</option>
                                        @foreach($racks as $rack)
                                            <option value="{{ $rack }}">{{ $rack }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 d-flex gap-3">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="tableFilterVariance" value="true">
                                        <label class="form-check-label small font-weight-bold text-dark cursor-pointer" for="tableFilterVariance">Tampilkan Selisih Saja</label>
                                    </div>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="tableFilterUncounted" value="true">
                                        <label class="form-check-label small font-weight-bold text-dark cursor-pointer" for="tableFilterUncounted">Belum Dihitung Saja</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Table of items with generous horizontal layout -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle nowrap" id="tbSOItems" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th class="border-0 text-center" style="width: 50px;">No</th>
                                            <th class="border-0 text-end" id="thRoundQty" style="min-width: 120px;">{{ $active_round === 'final' ? 'Final Qty' : 'Round ' . $active_round . ' Qty' }}</th>
                                            <th class="border-0">Nama Produk</th>
                                            <th class="border-0 text-end">Harga Ecer</th>
                                            @if(Auth::user()->role == 'admin')
                                            <th class="border-0 text-end">Snapshot Awal</th>
                                            <th class="border-0 text-end text-danger">Penjualan (Sales)</th>
                                            <th class="border-0 text-end text-success">Stok Ekspektasi</th>
                                            @endif
                                            <th class="border-0 text-end">Hasil Akhir</th>
                                            @if(Auth::user()->role == 'admin')
                                            <th class="border-0 text-end">Selisih Fisik</th>
                                            @endif
                                            @if(Auth::user()->role == 'admin')
                                            <th class="border-0 text-end">Nilai Selisih</th>
                                            @endif
                                            <th class="border-0 text-center">Status</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Floating Footer Action Bar (Sleek Modern Glassmorphism Design) -->
        <div class="sticky-action-bar">
            <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-outline-info text-info p-2"><i data-acorn-icon="info" style="width: 14px; height: 14px;"></i></span>
                    <span class="text-muted small font-weight-bold">Pengendali Sesi Stock Opname</span>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <!-- Trigger button for beautiful Offcanvas drawer -->
                    <button type="button" class="btn btn-outline-info font-weight-bold d-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#auditTrailOffcanvas">
                        <i data-acorn-icon="history" class="me-2" style="width:16px; height:16px;"></i> Audit History Logs
                        <span class="badge bg-info text-white ms-2" id="auditCountBadge">0</span>
                    </button>
 
                    @if(in_array($session->status, ['Counting', 'Recount']))
                        <button type="button" class="btn btn-outline-primary font-weight-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addManualItemModal">
                            <i data-acorn-icon="plus" class="me-2" style="width:16px; height:16px;"></i> Tambah Barang Manual
                        </button>
                    @endif

                    @if($session->status == 'Draft')
                        <button type="button" class="btn btn-info text-white font-weight-bold" id="btnStartCounting">
                            <i data-acorn-icon="play" class="me-1"></i> Mulai Hitung Fisik
                        </button>
                    @endif



                    @if(in_array($session->status, ['Counting', 'Recount']))
                        @if(Auth::user()->role == 'admin')
                            <button type="button" class="btn btn-success text-white font-weight-bold" id="btnApproveFinal">
                                <i data-acorn-icon="check" class="me-1"></i> Selesaikan Perhitungan (Review)
                            </button>
                        @else
                            <button type="button" class="btn btn-success text-white font-weight-bold" disabled data-bs-toggle="tooltip" title="Hanya Admin yang dapat memfinalkan">
                                <i data-acorn-icon="lock" class="me-1"></i> Selesaikan Perhitungan (Admin Only)
                            </button>
                        @endif
                    @endif

                    @if($session->status == 'Review')
                        @if(Auth::user()->role == 'admin')
                            <button type="button" class="btn btn-dark text-white font-weight-bold" id="btnPostAdjustment">
                                <i data-acorn-icon="database" class="me-1"></i> Post Koreksi Stok Ke Sistem
                            </button>
                        @else
                            <button type="button" class="btn btn-dark text-white font-weight-bold" disabled data-bs-toggle="tooltip" title="Hanya Admin yang dapat memposting koreksi stok">
                                <i data-acorn-icon="lock" class="me-1"></i> Post Koreksi Stok (Admin Only)
                            </button>
                        @endif
                    @endif

                    @if(Auth::user()->role == 'admin')
                    <a href="/laporan/opname/export/{{ $session->id }}" class="btn btn-outline-secondary font-weight-bold">
                        <i data-acorn-icon="download" class="me-1"></i> Export Data CSV
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Beautiful Offcanvas Drawer for Audit Trail (Keeps Main Table Spacious) -->
    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="auditTrailOffcanvas" aria-labelledby="auditTrailOffcanvasLabel" style="width: 420px;">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title font-weight-bold text-dark d-flex align-items-center" id="auditTrailOffcanvasLabel">
                <i data-acorn-icon="history" class="me-2 text-info"></i> Aktivitas Audit Trail
            </h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                <span class="small text-muted font-weight-bold">Riwayat Log Scan & Edit Manual</span>
                <span class="badge bg-info text-white px-2 py-1" id="auditCount">0 logs</span>
            </div>
            <div id="auditLogList" style="height: calc(100vh - 120px); overflow-y: auto;">
                <div class="p-5 text-center text-muted small" id="emptyAuditLabel">
                    <i data-acorn-icon="info" class="fs-2 mb-2 d-block text-separator"></i>
                    Belum ada aktivitas audit log tercatat pada sesi ini.
                </div>
            </div>
        </div>
    </div>

    <!-- Elegant Bootstrap Modal for Manual Product Insertion -->
    <div class="modal fade" id="addManualItemModal" tabindex="-1" aria-labelledby="addManualItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header bg-primary text-white py-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title font-weight-bold d-flex align-items-center" id="addManualItemModalLabel">
                        <i data-acorn-icon="plus" class="me-2"></i> Tambah Barang Manual
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Cari produk dari database master yang ingin Anda tambahkan ke dalam daftar stock opname ini.</p>
                    
                    <div class="input-group mb-3 shadow-sm rounded">
                        <input type="text" id="manualProductSearch" class="form-control border-end-0" placeholder="Ketik barcode atau nama barang...">
                        <button class="btn btn-primary font-weight-bold" type="button" id="btnSearchManualProduct">
                            <i data-acorn-icon="search" class="me-1"></i> Cari
                        </button>
                    </div>
                    
                    <div id="manualProductResults" class="list-group rounded overflow-auto shadow-sm" style="max-height: 280px; display: none;">
                        <!-- Results injected dynamically -->
                    </div>

                    <div id="manualProductInstructions" class="text-center text-muted py-5 border border-dashed rounded bg-light">
                        <i data-acorn-icon="search" class="fs-1 text-muted opacity-30 mb-2 d-block"></i>
                        <span class="small font-weight-bold">Masukkan kata kunci pencarian lalu klik Cari.</span>
                    </div>

                    <div id="manualProductEmpty" class="text-center text-muted py-5 border border-dashed rounded bg-light" style="display: none;">
                        <i data-acorn-icon="info" class="fs-1 text-warning opacity-50 mb-2 d-block"></i>
                        <span class="small font-weight-bold">Produk tidak ditemukan atau sudah ada di list.</span>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary font-weight-bold btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const sessionId = "{{ $session->id }}";
            let activeRound = @json($active_round);

            // Initialize dynamic audit trail logs
            loadAuditTrail();

            const itemsTable = $('#tbSOItems').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 25,
                responsive: true,
                ajax: {
                    url: '/laporan/opname/items-data/' + sessionId,
                    data: function(d) {
                        d.category = $('#tableFilterCategory').val();
                        d.rack = $('#tableFilterRack').val();
                        d.variance_only = $('#tableFilterVariance').is(':checked') ? 'true' : 'false';
                        d.uncounted_only = $('#tableFilterUncounted').is(':checked') ? 'true' : 'false';
                        d.search_query = $('#tableSearchQuery').val();
                    }
                },
                columns: [
                    { 
                        data: null, 
                        className: 'text-center text-muted font-weight-bold',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { 
                        data: null, 
                        className: 'text-end text-primary font-weight-bold editable-cell',
                        render: function(data, type, row) {
                            let qty = 0;
                            if (activeRound === 1) qty = row.round_1_qty;
                            else if (activeRound === 2) qty = row.round_2_qty;
                            else if (activeRound === 3) qty = row.round_3_qty;
                            else qty = row.final_qty;

                            // Allow editing inline only if counting/recount is active
                            const sessionStatus = $('#sessionStatusLabel').text().trim();
                            const isCounting = (sessionStatus === 'Counting' || sessionStatus === 'Recount');

                             if (isCounting && activeRound !== 'final') {
                                 if (activeRound === serverRound) {
                                     return `<span class="badge bg-outline-primary py-1 px-2 border-dashed cursor-pointer edit-qty-trigger font-weight-bold" data-id="${row.id}">${qty !== null ? qty : '-'}</span>`;
                                 }
                             }
                            return qty !== null ? qty : '-';
                        }
                    },
                    { data: 'product_name' },
                    { data: 'harga_jual', className: 'text-end font-weight-bold text-dark' },
                    @if(Auth::user()->role == 'admin')
                    { data: 'snapshot_qty', className: 'text-end font-weight-bold text-muted' },
                    { data: 'sales_during_opname', className: 'text-end font-weight-bold text-danger' },
                    { data: 'adjusted_snapshot', className: 'text-end font-weight-bold text-success' },
                    @endif
                    { data: 'final_qty', className: 'text-end font-weight-bold text-dark' },
                    @if(Auth::user()->role == 'admin')
                    { 
                        data: 'difference', 
                        className: 'text-end font-weight-bold',
                        render: function(data, type, row) {
                            if (data > 0) return `<span class="text-success font-weight-bold">+${data}</span>`;
                            if (data < 0) return `<span class="text-danger font-weight-bold">${data}</span>`;
                            return `<span class="text-muted">0</span>`;
                        }
                    },
                    @endif
                    @if(Auth::user()->role == 'admin')
                    { 
                        data: 'difference_value', 
                        className: 'text-end font-weight-bold',
                        render: function(data, type, row) {
                            let val = parseInt(data);
                            let formatted = 'Rp. ' + Math.abs(val).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            if (val > 0) return `<span class="text-success font-weight-bold">+${formatted}</span>`;
                            if (val < 0) return `<span class="text-danger font-weight-bold">-${formatted}</span>`;
                            return `<span class="text-muted font-weight-bold">Rp. 0</span>`;
                        }
                    },
                    @endif
                    { data: 'status_badge', className: 'text-center' }
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
                createdRow: function(row, data, dataIndex) {
                    // Only apply elegant red/orange outline border for items with difference
                    if (data.difference !== 0) {
                        $(row).addClass('highlight-variance');
                    }
                }
            });

            // Reload table on filter changes
            $('#tableFilterCategory, #tableFilterRack, #tableFilterVariance, #tableFilterUncounted').on('change', function() {
                itemsTable.ajax.reload();
            });

            // Trigger search on click
            $('#btnTableSearch').on('click', function() {
                itemsTable.ajax.reload();
            });

            // Trigger search on enter key down (avoiding keyup entirely)
            $('#tableSearchQuery').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    itemsTable.ajax.reload();
                }
            });

            // Reset search input
            $('#btnResetTableSearch').on('click', function() {
                $('#tableSearchQuery').val('');
                itemsTable.ajax.reload();
            });

            const serverRound = @json($active_round);
            const sessionStatus = @json($session->status);
 
            // Dynamically disable previous round tabs to make it super clear in UI
            function updateTabsUI() {
                if (sessionStatus === 'Draft') {
                    $('#roundTabs button').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').css('pointer-events', 'none');
                } else if (serverRound === 2) {
                    $('#roundTabs button[data-round="1"]').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').css('pointer-events', 'none');
                } else if (serverRound === 3) {
                    $('#roundTabs button[data-round="1"]').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').css('pointer-events', 'none');
                    $('#roundTabs button[data-round="2"]').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').css('pointer-events', 'none');
                }
            }
            updateTabsUI();
 
            // Handle Round Tabs selection
            $('#roundTabs button').on('click', function(e) {
                if (sessionStatus === 'Draft') {
                    e.preventDefault();
                    Swal.fire('Perhitungan Belum Dimulai', 'Mulai perhitungan fisik terlebih dahulu sebelum mengakses putaran (Round)!', 'warning');
                    return;
                }

                const roundVal = $(this).attr('data-round');
                const isAdmin = @json(Auth::user()->role == 'admin');
                const isSupervisor = @json(Auth::user()->id == $session->supervisor_id);
                const isAuthorized = isAdmin || isSupervisor;
 
                if (serverRound !== 'final') {
                    // Check if they are authorized to trigger rounding (generate next rounds)
                    if (roundVal === '2' && serverRound === 1 && !isAuthorized) {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Hanya Admin atau Supervisor yang ditunjuk yang berhak memicu transisi ke Round 2 (Recount)!', 'error');
                        return;
                    }
                    if (roundVal === '3' && serverRound === 2 && !isAuthorized) {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Hanya Admin atau Supervisor yang ditunjuk yang berhak memicu transisi ke Round 3 (Tie-Breaker)!', 'error');
                        return;
                    }
                    if (roundVal === 'final' && !isAdmin) {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Hanya Admin yang berhak melihat hasil finalisasi!', 'error');
                        return;
                    }
                    // Block going backward
                    if (serverRound === 2 && roundVal === '1') {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Anda tidak dapat kembali ke Round 1 setelah Round 2 (Recount) aktif!', 'warning');
                        return;
                    }
                    if (serverRound === 3 && (roundVal === '1' || roundVal === '2')) {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Anda tidak dapat kembali ke Round sebelumnya setelah Round 3 aktif!', 'warning');
                        return;
                    }

                    // Handle forward transition to Round 2
                    if (serverRound === 1 && roundVal === '2') {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Mulai Round 2 (Recount)?',
                            text: 'Sistem akan menyelesaikan Round 1 dan memproses barang yang berselisih ke Round 2.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Mulai!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post('/laporan/opname/generate-recount', {
                                    _token: "{{ csrf_token() }}",
                                    stock_opname_id: sessionId
                                }, function(res) {
                                    if (res.success) {
                                        Swal.fire('Sukses!', res.message, 'success').then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire('Gagal!', res.message, 'error');
                                    }
                                });
                            }
                        });
                        return;
                    }

                    // Handle forward transition to Round 3
                    if (serverRound === 2 && roundVal === '3') {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Mulai Round 3 (Supervisor Tie-Breaker)?',
                            text: 'Sistem akan menyelesaikan Round 2 dan memproses barang yang berselisih ke Round 3.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Mulai!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.post('/laporan/opname/generate-recount', {
                                    _token: "{{ csrf_token() }}",
                                    stock_opname_id: sessionId
                                }, function(res) {
                                    if (res.success) {
                                        Swal.fire('Sukses!', res.message, 'success').then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        Swal.fire('Gagal!', res.message, 'error');
                                    }
                                });
                            }
                        });
                        return;
                    }

                    // Block hopping ahead skipping steps
                    if (serverRound === 1 && (roundVal === '3' || roundVal === 'final')) {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Silakan jalankan Round 1 dan Round 2 terlebih dahulu!', 'warning');
                        return;
                    }
                    if (serverRound === 2 && roundVal === 'final') {
                        e.preventDefault();
                        Swal.fire('Akses Ditolak', 'Silakan jalankan Round 2 dan Round 3 terlebih dahulu!', 'warning');
                        return;
                    }
                }

                // If valid navigation within allowed bounds
                $('#roundTabs button').removeClass('active');
                $(this).addClass('active');

                if (roundVal === 'final') {
                    activeRound = 'final';
                    $('#thRoundQty').text('Final Qty');
                } else {
                    activeRound = parseInt(roundVal);
                    $('#thRoundQty').text(`Round ${activeRound} Qty`);
                }

                itemsTable.ajax.reload();
            });

            // Handle start counting trigger
            $('#btnStartCounting').on('click', function() {
                Swal.fire({
                    title: 'Mulai Sesi Counting?',
                    text: 'Sistem akan membekukan (freeze) data stok toko saat ini sebagai basis snapshot perhitungan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Mulai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/laporan/opname/start-counting', {
                            _token: "{{ csrf_token() }}",
                            stock_opname_id: sessionId
                        }, function(res) {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    timer: 1500
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        });
                    }
                });
            });

            // Handle barcode scanner scan flow
            $('#scannerInput').on('keypress', function(e) {
                if (e.which === 13) { // Enter key pressed
                    const barcode = $(this).val().trim();
                    if (barcode === '') return;

                    $.post('/laporan/opname/scan-barcode', {
                        _token: "{{ csrf_token() }}",
                        stock_opname_id: sessionId,
                        barcode: barcode,
                        round: activeRound
                    }, function(res) {
                        if (res.success) {
                            $('#scannerInput').val('').attr('placeholder', res.message).addClass('is-valid');
                            setTimeout(() => {
                                $('#scannerInput').attr('placeholder', 'Arahkan scan barcode di sini...').removeClass('is-valid');
                            }, 1000);
                            
                            itemsTable.ajax.reload(null, false); // reload table inline
                            loadAuditTrail();
                            updateSummaryCards();
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                            $('#scannerInput').val('');
                        }
                    });
                }
            });

            // Handle manual inline qty edit click (extremely ergonomic cell-wide trigger)
            $(document).on('click', '.editable-cell', function() {
                const trigger = $(this).find('.edit-qty-trigger');
                if (trigger.length === 0) return; // If no edit trigger is present (e.g. read-only final/other state), do nothing

                const itemId = trigger.attr('data-id');
                const currentVal = trigger.text() === '-' ? 0 : parseInt(trigger.text());
 
                Swal.fire({
                    title: 'Edit Qty Manual',
                    input: 'number',
                    inputLabel: 'Masukkan jumlah hitung fisik barang:',
                    inputValue: currentVal,
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (value === '' || isNaN(value) || parseInt(value) < 0) {
                            return 'Kuantitas harus berupa angka positif!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/laporan/opname/update-qty-manual', {
                            _token: "{{ csrf_token() }}",
                            item_id: itemId,
                            round: activeRound,
                            qty: result.value
                        }, function(res) {
                            if (res.success) {
                                itemsTable.ajax.reload(null, false);
                                loadAuditTrail();
                                updateSummaryCards();
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        });
                    }
                });
            });

            // Generate Recount (Move to Round 2 or Round 3)
            $(document).on('click', '#btnGenerateRecount', function() {
                const sessionStatus = $('#sessionStatusLabel').text().trim();
                let nextRoundText = sessionStatus === 'Counting' ? 'Round 2' : 'Round 3';

                Swal.fire({
                    title: `Generate Recount (${nextRoundText})?`,
                    text: `Sistem akan menyusun daftar recount khusus untuk item yang masih berselisih di ${nextRoundText}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Generate!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/laporan/opname/generate-recount', {
                            _token: "{{ csrf_token() }}",
                            stock_opname_id: sessionId
                        }, function(res) {
                            if (res.success) {
                                Swal.fire('Sukses!', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        });
                    }
                });
            });

            // Selesaikan Hitung (Approve Final to enter Review)
            $('#btnApproveFinal').on('click', function() {
                Swal.fire({
                    title: 'Selesaikan Perhitungan?',
                    text: 'Apakah proses pencatatan kuantitas fisik sudah selesai dan siap dilaporkan ke Supervisor untuk direview?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/laporan/opname/approve-final', {
                            _token: "{{ csrf_token() }}",
                            stock_opname_id: sessionId
                        }, function(res) {
                            if (res.success) {
                                Swal.fire('Sukses!', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        });
                    }
                });
            });

            // Post Adjustment to inventory
            $('#btnPostAdjustment').on('click', function() {
                Swal.fire({
                    title: 'Post Koreksi Stok ke Persediaan?',
                    text: 'Tindakan ini akan langsung mengkoreksi jumlah stok persediaan di sistem sesuai dengan selisih yang ditemukan. Tindakan ini permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Koreksi!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('/laporan/opname/post-adjustment', {
                            _token: "{{ csrf_token() }}",
                            stock_opname_id: sessionId
                        }, function(res) {
                            if (res.success) {
                                Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', res.message, 'error');
                            }
                        });
                    }
                });
            });

            // Robust dynamic Audit Trail log fetcher
            function loadAuditTrail() {
                $.ajax({
                    url: '/laporan/opname/audit-logs/' + sessionId,
                    method: 'GET',
                    success: function(res) {
                        if (res.success && res.logs.length > 0) {
                            $('#emptyAuditLabel').addClass('d-none');
                            $('#auditLogList').empty();
                            res.logs.forEach(log => {
                                let badgeClass = log.action === 'Scan Barcode' ? 'bg-info' : 'bg-warning';
                                let logHtml = `
                                    <div class="audit-log-item">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="font-weight-bold text-dark text-truncate" style="max-width: 220px;">${log.product_name}</span>
                                            <span class="badge ${badgeClass} text-white small px-2 py-0.5" style="font-size:0.7rem;">Round ${log.round}</span>
                                        </div>
                                        <div class="d-flex justify-content-between text-muted small mb-1">
                                            <span>SKU: ${log.sku}</span>
                                            <span>Perubahan: <strong class="text-primary">${log.qty_before !== null ? log.qty_before : '0'} &rarr; ${log.qty_after}</strong></span>
                                        </div>
                                        <div class="d-flex justify-content-between text-muted small" style="font-size: 0.72rem;">
                                            <span>Oleh: <strong class="text-secondary">${log.user_name}</strong></span>
                                            <span class="text-muted"><i data-acorn-icon="clock" class="me-1" style="width: 10px; height: 10px;"></i>${log.time}</span>
                                        </div>
                                    </div>
                                `;
                                $('#auditLogList').append(logHtml);
                            });
                            $('#auditCount').text(res.logs.length);
                            $('#auditCountBadge').text(res.logs.length);
                            
                            // Reinitialize icons if acorn is loaded
                            if (typeof acorn !== 'undefined' && acorn.initIcons) {
                                acorn.initIcons();
                            }
                        } else {
                            $('#emptyAuditLabel').removeClass('d-none');
                            $('#auditCount').text('0');
                            $('#auditCountBadge').text('0');
                        }
                    }
                });
            }

            function updateSummaryCards() {
                $.get('/laporan/opname/detail/' + sessionId, function(html) {
                    const parsed = $(html);
                    $('#cardTotalSku').text(parsed.find('#cardTotalSku').text().trim());
                    $('#cardCounted').text(parsed.find('#cardCounted').text().trim());
                    $('#cardRemaining').text(parsed.find('#cardRemaining').text().trim());
                    $('#cardVarianceItems').text(parsed.find('#cardVarianceItems').text().trim());
                    $('#cardVarianceValue').html(parsed.find('#cardVarianceValue').html().trim());
                });
            }

            // Manual Product Search inside Modal
            function performManualProductSearch() {
                const query = $('#manualProductSearch').val().trim();
                if (query === '') {
                    $('#manualProductResults').hide().html('');
                    $('#manualProductEmpty').hide();
                    $('#manualProductInstructions').show();
                    return;
                }

                $('#manualProductInstructions').hide();
                $('#manualProductEmpty').hide();
                $('#manualProductResults').show().html('<div class="p-4 text-center text-muted small"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Mencari produk...</div>');

                $.get('/laporan/opname/search-master-products/' + sessionId, { search_query: query }, function(res) {
                    if (res.length === 0) {
                        $('#manualProductResults').hide().html('');
                        $('#manualProductEmpty').show();
                    } else {
                        let html = '';
                        res.forEach(function(item) {
                            html += `
                                <div class="list-group-item d-flex align-items-center justify-content-between py-3 border-start-0 border-end-0 border-top-0">
                                    <div>
                                        <h6 class="font-weight-bold text-dark mb-1">${item.nama_barang}</h6>
                                        <span class="badge bg-light text-muted small px-2 py-0.5">${item.jenis_barang}</span>
                                        <span class="text-muted small ms-2">Ecer: <strong>${item.harga_jual}</strong></span>
                                        <div class="small text-muted mt-1">Barcode: <strong>${item.kode}</strong></div>
                                    </div>
                                    <div>
                                        ${item.is_added ? 
                                            `<button class="btn btn-outline-secondary btn-sm font-weight-bold" disabled><i data-acorn-icon="check" class="me-1"></i> Sudah Ada</button>` : 
                                            `<button class="btn btn-primary btn-sm font-weight-bold btn-add-manual-prod" data-code="${item.kode}"><i data-acorn-icon="plus" class="me-1"></i> Tambah</button>`
                                        }
                                    </div>
                                </div>
                            `;
                        });
                        $('#manualProductResults').html(html).show();
                        if (typeof acorn !== 'undefined' && acorn.initIcons) {
                            acorn.initIcons();
                        }
                    }
                });
            }

            $('#btnSearchManualProduct').on('click', performManualProductSearch);
            $('#manualProductSearch').on('keypress', function(e) {
                if (e.which === 13) {
                    performManualProductSearch();
                }
            });

            // Action when adding manual product
            $(document).on('click', '.btn-add-manual-prod', function() {
                const button = $(this);
                const code = button.attr('data-code');
                button.prop('disabled', true).html('<div class="spinner-border spinner-border-sm text-white" role="status"></div>');

                $.post('/laporan/opname/add-master-product/' + sessionId, {
                    _token: "{{ csrf_token() }}",
                    kode_barang: code
                }, function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: res.message,
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        // Update the button state
                        button.removeClass('btn-primary').addClass('btn-outline-secondary').html('<i data-acorn-icon="check" class="me-1"></i> Sudah Ada').prop('disabled', true);
                        if (typeof acorn !== 'undefined' && acorn.initIcons) {
                            acorn.initIcons();
                        }
                        // Reload main table & dashboard cards
                        itemsTable.ajax.reload(null, false);
                        loadAuditTrail();
                        updateSummaryCards();
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                        button.prop('disabled', false).html('<i data-acorn-icon="plus" class="me-1"></i> Tambah');
                    }
                });
            });

            // Clean modal state when closed
            $('#addManualItemModal').on('hidden.bs.modal', function () {
                $('#manualProductSearch').val('');
                $('#manualProductResults').hide().html('');
                $('#manualProductEmpty').hide();
                $('#manualProductInstructions').show();
            });

        });
    </script>
@endpush
