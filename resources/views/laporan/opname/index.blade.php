@extends('layouts.main')

@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Stock Opname</h1>
                        <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
                            <ul class="breadcrumb pt-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#">Stock</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Opname</li>
                            </ul>
                        </nav>
                    </div>
                    <!-- Title End -->

                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-5 text-end">
                        <button class="btn btn-primary btn-icon btn-icon-start w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#createSOModal">
                            <i data-acorn-icon="plus"></i>
                            <span>Buat Sesi SO</span>
                        </button>
                    </div>
                    <!-- Top Buttons End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

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
                                        <option value="Draft">Draft</option>
                                        <option value="Counting">Counting</option>
                                        <option value="Recount">Recount</option>
                                        <option value="Review">Review</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Posted">Posted</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label">Tanggal</label>
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
                                <table class="data-table nowrap hover" id="tbStockOpname">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase">No</th>
                                            <th class="text-muted text-small text-uppercase">Nomor SO</th>
                                            <th class="text-muted text-small text-uppercase">Toko</th>
                                            <th class="text-muted text-small text-uppercase">Tanggal Sesi</th>
                                            <th class="text-muted text-small text-uppercase">Petugas</th>
                                            <th class="text-muted text-small text-uppercase">Supervisor</th>
                                            <th class="text-muted text-small text-uppercase">Status</th>
                                            <th class="text-muted text-small text-uppercase">Aksi</th>
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

        <!-- Create Session Modal -->
        <div class="modal fade" id="createSOModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="createSOForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Buat Sesi Stock Opname Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Toko / Cabang <span class="text-danger">*</span></label>
                                <select class="form-select" name="kode_toko" required>
                                    <option value="" disabled selected>Pilih Toko...</option>
                                    @foreach ($toko as $t)
                                        <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Supervisor Validasi <span class="text-danger">*</span></label>
                                <select class="form-select" name="supervisor_id" required>
                                    <option value="" disabled selected>Pilih Supervisor...</option>
                                    @foreach ($supervisors as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }} ({{ strtoupper($s->role) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan / Notes</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Contoh: Opname akhir bulan rak 1-5..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmitSO">Buat Sesi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            const table = $('#tbStockOpname').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                order: [[0, "desc"]],
                responsive: true,
                ajax: {
                    url: '/laporan/opname/show',
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
                    { data: 'nomor_so' },
                    { data: 'nama_toko' },
                    { data: 'tanggal' },
                    { data: 'nama_petugas' },
                    { data: 'nama_supervisor' },
                    { data: 'status_badge', className: 'text-center' },
                    { data: 'aksi', sortable: false, className: 'text-center' }
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
                drawCallback: function() {
                    if (typeof acorn !== 'undefined' && acorn.initIcons) {
                        acorn.initIcons();
                    }
                },
            });
            window.tbStockOpname = table;

            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });

            // Create Sesi SO Form Submission via AJAX
            $('#createSOForm').on('submit', function(e) {
                e.preventDefault();
                $('#btnSubmitSO').prop('disabled', true).text('Memproses...');

                $.ajax({
                    url: '/laporan/opname/store',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.redirect;
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message
                            });
                            $('#btnSubmitSO').prop('disabled', false).text('Buat Sesi');
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan sistem, silakan coba lagi.'
                        });
                        $('#btnSubmitSO').prop('disabled', false).text('Buat Sesi');
                    }
                });
            });

            // Delete SO handler
            $(document).on('click', '.btn-delete-so', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Sesi Stock Opname yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/laporan/opname/destroy/' + id,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Dihapus!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal!',
                                        text: res.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan sistem, silakan coba lagi.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-database.js";

        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}"
        };

        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        const updatesRef = ref(db, 'hijabkku/updates/opname_list');
        let isInitialLoad = true;

        onValue(updatesRef, (snapshot) => {
            if (isInitialLoad) {
                isInitialLoad = false;
                return;
            }
            if (window.tbStockOpname) {
                window.tbStockOpname.ajax.reload(null, false);
            }
        });
    </script>
@endpush
