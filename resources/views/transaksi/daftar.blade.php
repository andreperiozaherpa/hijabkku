@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Daftar Transaksi Penjualan</h1>
                        <p class="text-muted mb-0">Kelola dan tinjau riwayat pembayaran invoice toko secara real-time.</p>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-5">
                <div class="col-12">
                    <!-- Modern Filter Panel Start -->
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-end">
                                <!-- Toko Filter -->
                                <div class="col-12 col-md-3">
                                    <div class="form-floating w-100">
                                        <select class="select-floating" id="selectFloating">
                                            @if (Auth::user()->role == 'admin')
                                                <option value="semua">Semua Cabang Toko</option>
                                            @endif
                                            @foreach ($toko as $t)
                                                <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                        <label>Cabang Toko</label>
                                    </div>
                                </div>

                                <!-- Kasir Filter -->
                                <div class="col-12 col-md-3">
                                    <div class="form-floating w-100">
                                        <select class="select-floating" id="select2Basic">
                                            @if (Auth::user()->role == 'admin')
                                                <option value="semua">Semua Kasir</option>
                                            @endif
                                            @foreach ($kasir as $k)
                                                <option value="{{ $k->id }}" data-toko="{{ $k->kode_toko }}">{{ $k->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Petugas Kasir</label>
                                    </div>
                                </div>

                                <!-- Date Filter -->
                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-muted small ms-1 mb-1">Tanggal Transaksi</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Pilih Tanggal" id="getDates">
                                            <span class="input-group-text bg-light border-start-0">
                                                <i data-acorn-icon="calendar" class="text-muted" data-acorn-size="16"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Period Button Group -->
                                <div class="col-12 col-md-3">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-muted small ms-1 mb-1">Periode Cetak Rekap</label>
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="rekap-period-btn btn btn-outline-primary active" id="hari" data-param="hari">Hari</button>
                                            <button type="button" class="rekap-period-btn btn btn-outline-primary" id="bulan" data-param="bulan">Bulan</button>
                                            <button type="button" class="rekap-period-btn btn btn-outline-primary" id="tahun" data-param="tahun">Tahun</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4 pt-3 border-top border-light align-items-center">
                                <div class="col-12 col-md-6 mb-2 mb-md-0">
                                    <div class="d-inline-block search-input-container w-100 border border-separator bg-foreground search-sm rounded-lg">
                                        <input class="form-control form-control-sm datatable-search" placeholder="Cari Kode Invoice / Kasir..." data-datatable="#tbDaftarTransaksi">
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 text-md-end">
                                    <button class="btn btn-outline-muted btn-icon btn-icon-only btn-sm datatable-print me-1" type="button" data-datatable="#tbDaftarTransaksi" title="Print List">
                                        <i data-acorn-icon="print"></i>
                                    </button>
                                    <div class="d-inline-block datatable-export me-2" data-datatable="#tbDaftarTransaksi">
                                        <button class="btn btn-outline-muted btn-icon btn-icon-only btn-sm dropdown" data-bs-toggle="dropdown" type="button" title="Download">
                                            <i data-acorn-icon="download"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                            <button class="dropdown-item export-copy" type="button">Copy</button>
                                            <button class="dropdown-item export-excel" type="button">Excel</button>
                                            <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm action-rekap shadow-sm">
                                        <i data-acorn-icon="print" class="me-1"></i> Cetak Rekap
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modern Filter Panel End -->

                    <!-- Datatable Start -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="card-body p-0">
                            <div class="data-table-responsive-wrapper p-4">
                                <table class="data-table nowrap hover w-100" id="tbDaftarTransaksi" data-order='[[ 0, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase" style="width: 5%">No</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 12%">Invoice</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 18%">Tanggal</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 12%">Kasir</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 12%">Cabang Toko</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 8%">Harga</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 12%">Pembayaran</th>
                                            <th class="text-muted text-small text-uppercase text-end" style="width: 13%">Total Belanja</th>
                                            <th class="text-muted text-small text-uppercase text-center" style="width: 8%">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Datatable End -->
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>

    <!-- Visual POS Receipt Detail Modal Start -->
    <div class="modal fade" id="modalDetailTransaksi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background-color: #fafafa;">
                <div class="modal-body p-4">
                    <!-- Thermal Receipt Container -->
                    <div class="bg-white p-4 shadow-sm border border-light" style="border-radius: 12px; font-family: 'Courier New', Courier, monospace; color: #333;">
                        <!-- Store Header -->
                        <div class="text-center mb-3">
                            <h4 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif; letter-spacing: 1px;">HIJABKKU</h4>
                            <p class="small text-muted mb-0" id="tokoTitle" style="font-family: inherit;"></p>
                            <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        </div>

                        <!-- Invoice Info -->
                        <div class="small mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                            <div class="d-flex justify-content-between">
                                <span>No. Struk :</span>
                                <span class="fw-bold" id="invoiceTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tanggal   :</span>
                                <span id="dateTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Kasir     :</span>
                                <span id="nameTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Metode    :</span>
                                <span class="text-uppercase fw-bold" id="metodeTitle"></span>
                            </div>
                            <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        </div>

                        <!-- Items Table -->
                        <table class="w-100 small mb-3" style="font-size: 0.85rem; line-height: 1.6;">
                            <thead>
                                <tr style="border-bottom: 1px dashed #ccc;">
                                    <th class="text-start pb-1">Menu/Barang</th>
                                    <th class="text-center pb-1" style="width: 15%">Qty</th>
                                    <th class="text-end pb-1" style="width: 25%">Harga</th>
                                    <th class="text-end pb-1" style="width: 30%">Total</th>
                                </tr>
                            </thead>
                            <tbody id="dataTransaksi">
                                <!-- Loaded dynamically -->
                            </tbody>
                        </table>

                        <!-- Payment Totals -->
                        <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        <div class="small" style="font-size: 0.88rem; line-height: 1.6;">
                            <div class="d-flex justify-content-between fw-bold mb-1">
                                <span>TOTAL BELANJA :</span>
                                <span id="totalBelanjaTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>TUNAI/BAYAR   :</span>
                                <span id="bayarTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold text-success">
                                <span>KEMBALIAN     :</span>
                                <span id="kembalianTitle"></span>
                            </div>
                        </div>

                        <!-- Footer Note -->
                        <div style="border-top: 1px dashed #ccc; margin: 15px 0 10px 0;"></div>
                        <div class="text-center small text-muted mt-2" style="font-size: 0.75rem;">
                            Terima Kasih atas Kunjungan Anda<br>
                            Barang yang sudah dibeli tidak dapat ditukar/dikembalikan
                        </div>
                    </div>

                    <!-- Modal Close Button -->
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-muted closed" style="border-radius: 8px;">Tutup Struk</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visual POS Receipt Detail Modal End -->
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Initialize Datepicker
            $('#getDates').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            }).datepicker('update', new Date()).on('changeDate', function(e) {
                $('#tbDaftarTransaksi').DataTable().ajax.reload();
            });

            // Initialize Datatable
            const transaksiTable = $('#tbDaftarTransaksi').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "desc"]
                ],
                responsive: true,
                ajax: {
                    url: '/transaksi/daftar/show',
                    data: function(d) {
                        d.toko = $('#selectFloating').val();
                        d.kasir = $('#select2Basic').val();
                        d.date = $('#getDates').val();
                        d.param = $('.rekap-period-btn.active').data('param') || 'hari';
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
                    {
                        data: 'kode_invoice',
                        render: function(data, type, row) {
                            return `<span class="badge bg-outline-primary text-primary font-weight-bold" style="font-size: 0.85rem;">${data}</span>`;
                        }
                    },
                    { data: 'tanggal' },
                    { data: 'user_name' },
                    { data: 'toko' },
                    { data: 'metode' },
                    { data: 'metode_pembayaran' },
                    {
                        data: 'total_rupiah',
                        className: 'font-weight-bold text-end'
                    },
                    {
                        data: 'aksi',
                        sortable: false,
                        className: 'text-center'
                    }
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
            });

            // Keep a master backup of all cashier options
            const allKasirOptions = $('#select2Basic').find('option').clone();

            // Filter changes -> Reload Table
            $('#selectFloating').on('change', function() {
                const selectedToko = $(this).val();
                const kasirSelect = $('#select2Basic');
                const currentKasir = kasirSelect.val();

                // Empty the select first
                kasirSelect.empty();

                // Re-populate with matching options from backup
                allKasirOptions.each(function() {
                    const optionVal = $(this).val();
                    const optionToko = $(this).attr('data-toko');

                    if (selectedToko === 'semua' || optionVal === 'semua' || !optionToko || optionToko == selectedToko) {
                        kasirSelect.append($(this).clone());
                    }
                });

                // Restore selected kasir or reset to 'semua' if not available in current options
                const optionExists = kasirSelect.find(`option[value="${currentKasir}"]`).length > 0;
                if (optionExists) {
                    kasirSelect.val(currentKasir);
                } else {
                    kasirSelect.val('semua');
                }

                // If using Select2 or other custom plugins, trigger change to refresh the visual UI
                kasirSelect.trigger('change.select2');
                
                transaksiTable.ajax.reload();
            });

            $('#select2Basic').on('change', function() {
                transaksiTable.ajax.reload();
            });

            // Period Button Handler
            $('.rekap-period-btn').on('click', function(e) {
                e.preventDefault();
                $('.rekap-period-btn').removeClass('active');
                $(this).addClass('active');
                transaksiTable.ajax.reload();
            });

            // Currency formatting helper
            function formatRupiah(value) {
                return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // AJAX Detail Transaction Loader
            $(document).on('click', '.detailTransaksi', function() {
                var kodeInvoice = $(this).attr('data-invoice');
                
                // Clear old items first
                $('.dataBarang').remove();

                $.ajax({
                    type: 'GET',
                    url: '/transaksi/daftar/show_detail',
                    data: { invoice: kodeInvoice },
                    success: function(res) {
                        $('#invoiceTitle').text(res.data[0].kode_invoice);
                        $('#nameTitle').text(res.username);
                        $('#dateTitle').text(res.tanggal);
                        $('#tokoTitle').text(res.toko);
                        $('#metodeTitle').text(res.metode_pembayaran || 'TUNAI');
                        
                        $('#totalBelanjaTitle').text(formatRupiah(res.total_harga));
                        $('#bayarTitle').text(formatRupiah(res.pembayaran));
                        $('#kembalianTitle').text(formatRupiah(res.kembalian));

                        $.each(res.data, function(index, value) {
                            $('#dataTransaksi').append(
                                `<tr class="dataBarang">
                                    <td class="text-start py-1">${value.nama_barang}</td>
                                    <td class="text-center py-1">${value.jumlah}</td>
                                    <td class="text-end py-1">${formatRupiah(value.harga)}</td>
                                    <td class="text-end py-1">${formatRupiah(value.harga_total)}</td>
                                </tr>`
                            );
                        });

                        $('#modalDetailTransaksi').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil detail transaksi.'
                        });
                    }
                });
            });

            // Action Print Rekap
            $(document).on('click', '.action-rekap', function() {
                var getDates = $('#getDates').val();
                var getFormat = $('.rekap-period-btn.active').text(); // "Hari", "Bulan", "Tahun"
                var getToko = $('#selectFloating').val();
                var getUser = $('#select2Basic').val();

                if (getToko && getUser) {
                    // Open rekap in a new window with a relative path
                    var printUrl = "/transaksi/daftar/edit?tanggal=" + getDates +
                                   "&format=" + getFormat + "&toko=" + getToko + "&users=" + getUser;
                    window.open(printUrl, "_blank");
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-right',
                        showConfirmButton: false,
                        timer: 1500,
                        icon: 'error',
                        title: 'Harap pilih Toko dan Kasir',
                    });
                }
            });

            // Close Modal
            $(document).on('click', '.closed', function() {
                $('#modalDetailTransaksi').modal('hide');
                $('.dataBarang').remove();
            });
        });
    </script>
@endpush
