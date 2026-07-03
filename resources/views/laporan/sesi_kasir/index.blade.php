@extends('layouts.main')

@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Laporan Sesi Kasir</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#">Laporan</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sesi Kasir</li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            @if(Auth::user()->role === 'admin' && $pending_approvals->count() > 0)
                <!-- Pending Approvals Start -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border border-warning shadow-sm">
                            <div class="card-header bg-light py-3 border-bottom border-warning">
                                <h5 class="card-title mb-0 text-warning fw-bold d-flex align-items-center">
                                    <i data-acorn-icon="shield" class="me-2 text-warning"></i>
                                    Pengajuan Pembukaan Sesi Kasir (Menunggu Persetujuan)
                                </h5>
                            </div>
                            <div class="card-body py-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <thead>
                                            <tr class="text-muted text-small text-uppercase">
                                                <th>Toko</th>
                                                <th>Kasir</th>
                                                <th>Saldo Awal</th>
                                                <th>Keterangan</th>
                                                <th>Waktu Pengajuan</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pending_approvals as $pending)
                                                <tr class="border-bottom">
                                                    <td class="fw-bold">{{ $pending->toko->nama_toko ?? '-' }}</td>
                                                    <td>{{ $pending->dibukaOleh->name ?? '-' }}</td>
                                                    <td class="fw-bold text-primary">Rp. {{ number_format($pending->saldo_awal, 0, ',', '.') }}</td>
                                                    <td>{{ $pending->catatan }}</td>
                                                    <td>{{ $pending->waktu_buka->format('d M Y H:i') }}</td>
                                                    <td class="text-end py-2">
                                                        <button type="button" class="btn btn-sm btn-outline-success btn-approve me-1" data-id="{{ $pending->id }}">
                                                            Setujui
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-reject" data-id="{{ $pending->id }}">
                                                            Tolak
                                                        </button>
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
                <!-- Pending Approvals End -->
            @endif

            <!-- Filters Start -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="filterForm" class="row g-3 align-items-end">
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Toko / Cabang</label>
                                    <select class="form-select" id="filterToko">
                                        <option value="semua">Semua Toko</option>
                                        @foreach ($toko as $t)
                                            <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="filterStatus">
                                        <option value="semua">Semua Status</option>
                                        <option value="buka">Buka</option>
                                        <option value="tutup">Tutup</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Tanggal Buka</label>
                                    <input type="date" class="form-control" id="filterDate">
                                </div>
                                <div class="col-12 col-md-3">
                                    <button type="button" id="btnFilter" class="btn btn-outline-primary w-100">
                                        <i data-acorn-icon="search" class="me-1"></i> Saring
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Filters End -->

            <!-- Content Start -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="data-table-responsive-wrapper">
                                <table class="data-table nowrap hover" id="tbSesiKasir">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">No</th>
                                            <th class="text-muted text-small text-uppercase">Toko</th>
                                            <th class="text-muted text-small text-uppercase">Tanggal Buka</th>
                                            <th class="text-muted text-small text-uppercase">Tanggal Tutup</th>
                                            <th class="text-muted text-small text-uppercase">Kasir (Buka)</th>
                                            <th class="text-muted text-small text-uppercase">Kasir (Tutup)</th>
                                            <th class="text-muted text-small text-uppercase">Saldo Awal</th>
                                            <th class="text-muted text-small text-uppercase">Total Penjualan</th>
                                            <th class="text-muted text-small text-uppercase">Saldo Akhir (Sistem)</th>
                                            <th class="text-muted text-small text-uppercase">Saldo Akhir (Aktual)</th>
                                            <th class="text-muted text-small text-uppercase">Selisih</th>
                                            <th class="text-muted text-small text-uppercase">Status</th>
                                            <th class="text-muted text-small text-uppercase">Catatan / Approval</th>
                                        </tr>
                                    </thead>
                                </table>
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
        $(document).ready(function() {
            const table = $('#tbSesiKasir').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                order: [[2, "asc"]],
                responsive: true,
                ajax: {
                    url: '{{ route("laporan.sesi_kasir.show") }}',
                    data: function(d) {
                        d.toko = $('#filterToko').val();
                        d.status = $('#filterStatus').val();
                        d.date = $('#filterDate').val();
                    }
                },
                columns: [
                    {
                        data: null,
                        sortable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { data: 'nama_toko' },
                    { data: 'tgl_buka' },
                    { data: 'tgl_tutup' },
                    { data: 'kasir_buka' },
                    { data: 'kasir_tutup' },
                    { data: 'formatted_saldo_awal' },
                    { data: 'formatted_total_penjualan' },
                    { data: 'formatted_saldo_akhir_sistem' },
                    { data: 'formatted_saldo_akhir_aktual' },
                    { data: 'formatted_selisih' },
                    { data: 'status_badge', className: 'text-center' },
                    { data: 'catatan' }
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
                drawCallback: function() {
                    if (typeof AcornIcons !== 'undefined') {
                        new AcornIcons().replace();
                    }
                },
            });

            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });

            // Approval Handler
            $('.btn-approve').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Setujui Pengajuan?',
                    text: "Sesi kasir akan dibuka dengan modal awal yang diajukan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Setujui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '/laporan/sesi-kasir/approve/' + id,
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                Swal.fire('Berhasil', res.cek_data, 'success').then(() => location.reload());
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON ? xhr.responseJSON.cek_data : 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });

            // Rejection Handler
            $('.btn-reject').on('click', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Tolak Pengajuan?',
                    text: "Masukkan alasan penolakan:",
                    input: 'text',
                    inputPlaceholder: 'Contoh: Modal awal tidak sesuai',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Alasan penolakan harus diisi!'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '/laporan/sesi-kasir/reject/' + id,
                            data: {
                                _token: '{{ csrf_token() }}',
                                alasan: result.value
                            },
                            success: function(res) {
                                Swal.fire('Berhasil', res.cek_data, 'success').then(() => location.reload());
                            },
                            error: function(xhr) {
                                var msg = xhr.responseJSON ? xhr.responseJSON.cek_data : 'Terjadi kesalahan.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
