@extends('layouts.main')
@section('main')
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container mb-4">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Kelola Pesanan Pickup</h1>
                        <p class="text-muted mb-0">Lacak, kelola, dan proses pengambilan barang pesanan pelanggan online secara real-time.</p>
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
                                <div class="col-12 col-md-4">
                                    <div class="form-floating w-100">
                                        <select class="select-floating" id="selectFloating">
                                            @if (Auth::user()->role == 'admin')
                                                <option value="semua">Semua Cabang Toko</option>
                                            @endif
                                            @foreach ($tokos as $t)
                                                <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                            @endforeach
                                        </select>
                                        <label>Cabang Toko</label>
                                    </div>
                                </div>

                                <!-- Status Filter Group -->
                                <div class="col-12 col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label text-muted small ms-1 mb-1">Status Pengambilan</label>
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="status-filter-btn btn btn-outline-primary active" data-status="Belum Diambil">Belum Diambil</button>
                                            <button type="button" class="status-filter-btn btn btn-outline-primary" data-status="Sudah Diambil">Sudah Diambil</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Search Field -->
                                <div class="col-12 col-md-4">
                                    <div class="d-inline-block search-input-container w-100 border border-separator bg-foreground search-sm rounded-lg">
                                        <input class="form-control form-control-sm datatable-search" placeholder="Cari Pelanggan / Invoice..." data-datatable="#tbPickupTransaksi">
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modern Filter Panel End -->

                    <!-- Datatable Start -->
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="card-body p-0">
                            <div class="data-table-responsive-wrapper p-4">
                                <table class="data-table nowrap hover w-100" id="tbPickupTransaksi" data-order='[[ 0, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase" style="width: 5%">No</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 15%">Invoice</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 15%">Cabang Toko</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 20%">Pelanggan</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 15%">No. Telepon</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 20%">Detail Barang</th>
                                            <th class="text-muted text-small text-uppercase" style="width: 10%">Aksi</th>
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

    <!-- Visual Pickup Detail Modal Start -->
    <div class="modal fade" id="modalDetailPickup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; background-color: #fafafa;">
                <div class="modal-body p-4">
                    <!-- Thermal Receipt Container -->
                    <div class="bg-white p-4 shadow-sm border border-light" style="border-radius: 12px; font-family: 'Courier New', Courier, monospace; color: #333;">
                        <!-- Store Header -->
                        <div class="text-center mb-3">
                            <h4 class="fw-bold mb-1" style="font-family: 'Outfit', sans-serif; letter-spacing: 1px;">HIJABKKU</h4>
                            <p class="small text-muted mb-0" id="pickupTokoTitle" style="font-family: inherit;"></p>
                            <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        </div>

                        <!-- Customer & Invoice Info -->
                        <div class="small mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                            <div class="d-flex justify-content-between">
                                <span>No. Struk :</span>
                                <span class="fw-bold" id="pickupInvoiceTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tanggal   :</span>
                                <span id="pickupDateTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Pelanggan :</span>
                                <span class="fw-bold" id="pickupCustomerTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>WhatsApp  :</span>
                                <span id="pickupPhoneTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Email     :</span>
                                <span id="pickupEmailTitle"></span>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <span>Status    :</span>
                                <span class="badge" id="pickupStatusBadge"></span>
                            </div>
                            <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        </div>

                        <!-- Items Table -->
                        <table class="w-100 small mb-3" style="font-size: 0.85rem; line-height: 1.6;">
                            <thead>
                                <tr style="border-bottom: 1px dashed #ccc;">
                                    <th class="text-start pb-1">Barang</th>
                                    <th class="text-center pb-1" style="width: 15%">Qty</th>
                                    <th class="text-end pb-1" style="width: 25%">Harga</th>
                                    <th class="text-end pb-1" style="width: 30%">Total</th>
                                </tr>
                            </thead>
                            <tbody id="pickupDataTransaksi">
                                <!-- Loaded dynamically -->
                            </tbody>
                        </table>

                        <!-- Totals -->
                        <div style="border-top: 1px dashed #ccc; margin: 10px 0;"></div>
                        <div class="small" style="font-size: 0.88rem; line-height: 1.6;">
                            <div class="d-flex justify-content-between fw-bold mb-1">
                                <span>TOTAL BELANJA :</span>
                                <span id="pickupTotalBelanjaTitle"></span>
                            </div>
                        </div>

                        <!-- Footer Note -->
                        <div style="border-top: 1px dashed #ccc; margin: 15px 0 10px 0;"></div>
                        <div class="text-center small text-muted mt-2" style="font-size: 0.75rem;">
                            Terima Kasih atas Kunjungan Anda<br>
                            METODE PENGAMBILAN: STORE PICKUP
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-muted closed" style="border-radius: 8px;">Tutup</button>
                        <button type="button" class="btn btn-success btn-modal-complete shadow-sm" id="btnModalComplete" style="border-radius: 8px; display: none;">
                            <i data-acorn-icon="check" class="me-1"></i> Serahkan Barang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Visual Pickup Detail Modal End -->
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            let activeStatus = 'Belum Diambil';

            // Setup CSRF Token for ajax requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize Datatable
            const pickupTable = $('#tbPickupTransaksi').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                order: [
                    [0, "desc"]
                ],
                responsive: true,
                ajax: {
                    url: '/transaksi/pickup/data',
                    data: function(d) {
                        d.kode_toko = $('#selectFloating').val();
                        d.status_pengambilan = activeStatus;
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
                    { data: 'nama_toko' },
                    { data: 'customer_name' },
                    { 
                        data: 'customer_phone',
                        render: function(data, type, row) {
                            let formattedPhone = data;
                            // Basic format conversion to international format for WA if starting with 0
                            let waPhone = data;
                            if (waPhone.startsWith('0')) {
                                waPhone = '62' + waPhone.substring(1);
                            }
                            return `<a href="https://wa.me/${waPhone}" target="_blank" class="text-success d-inline-flex align-items-center" style="font-weight: 500;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-whatsapp me-1" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.948h.008c4.368 0 7.928-3.556 7.93-7.928a7.86 7.86 0 0 0-2.33-5.688zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.69-4.98c-.202-.1-1.194-.59-1.378-.656-.185-.067-.32-.1-.453.1-.133.2-.516.654-.633.788-.117.133-.234.15-.435.05-.201-.1-.85-.313-1.618-.997-.597-.533-.999-1.193-1.116-1.393-.117-.2-.013-.307.087-.406.09-.089.2-.234.3-.35.1-.117.133-.2.2-.333.067-.133.033-.25-.017-.35-.05-.1-.453-1.09-62-.977-.182-.2-.313-.243-.453-.243-.13-.002-.28-.002-.43.149-.15.15-.58.567-.58 1.384 0 .817.594 1.606.677 1.716.083.11 1.171 1.787 2.836 2.502.396.17.705.27.945.347.399.127.762.109 1.05.066.32-.048 1.195-.488 1.365-.96.17-.47.17-.872.12-.96-.05-.088-.18-.133-.38-.234z"/>
                                </svg>
                                ${formattedPhone}
                            </a>`;
                        }
                    },
                    { data: 'items', sortable: false },
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
                drawCallback: function() {
                    // Re-initialize Acorn icons if present
                    if (typeof AcornIcons !== 'undefined') {
                        new AcornIcons().replace();
                    }
                }
            });

            // Search input handler
            $('.datatable-search').on('keyup', function() {
                pickupTable.search($(this).val()).draw();
            });

            // Filter changes -> Reload Table
            $('#selectFloating').on('change', function() {
                pickupTable.ajax.reload();
            });

            // Status Filter Group handler
            $('.status-filter-btn').on('click', function(e) {
                e.preventDefault();
                $('.status-filter-btn').removeClass('active');
                $(this).addClass('active');
                activeStatus = $(this).data('status');
                pickupTable.ajax.reload();
            });

            // Currency formatting helper
            function formatRupiah(value) {
                return 'Rp. ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            let currentPickupId = null;

            // AJAX Detail Loader
            $(document).on('click', '.btn-detail', function() {
                const tr = $(this).closest('tr');
                const rowData = pickupTable.row(tr).data();
                const pickupId = rowData.id;
                currentPickupId = pickupId;
                
                // Clear old items first
                $('.pickupDataBarang').remove();

                $.ajax({
                    type: 'GET',
                    url: `/transaksi/pickup/${pickupId}/items`,
                    success: function(res) {
                        $('#pickupInvoiceTitle').text(res.pickup.kode_invoice);
                        $('#pickupCustomerTitle').text(res.pickup.customer_name);
                        
                        let waPhone = res.pickup.customer_phone;
                        if (waPhone.startsWith('0')) {
                            waPhone = '62' + waPhone.substring(1);
                        }
                        
                        $('#pickupPhoneTitle').html(`<a href="https://wa.me/${waPhone}" target="_blank" class="text-success fw-bold">${res.pickup.customer_phone}</a>`);
                        $('#pickupEmailTitle').text(res.pickup.customer_email || '-');
                        
                        const dateFormatted = res.pickup.created_at ? moment(res.pickup.created_at).locale('id').format('LLLL') : '-';
                        $('#pickupDateTitle').text(dateFormatted);
                        $('#pickupTokoTitle').text(res.toko);
                        
                        // Set Status Badge
                        const badge = $('#pickupStatusBadge');
                        badge.text(res.pickup.status_pengambilan);
                        if (res.pickup.status_pengambilan === 'Belum Diambil') {
                            badge.removeClass('bg-success').addClass('bg-warning text-dark');
                            $('#btnModalComplete').show();
                        } else {
                            badge.removeClass('bg-warning text-dark').addClass('bg-success text-white');
                            $('#btnModalComplete').hide();
                        }
                        
                        $('#pickupTotalBelanjaTitle').text(res.grand_total_rupiah);

                        $.each(res.items, function(index, value) {
                            $('#pickupDataTransaksi').append(
                                `<tr class="pickupDataBarang">
                                    <td class="text-start py-1">${value.nama_barang}</td>
                                    <td class="text-center py-1">${value.jumlah}</td>
                                    <td class="text-end py-1">${formatRupiah(value.harga)}</td>
                                    <td class="text-end py-1">${formatRupiah(value.harga_total)}</td>
                                </tr>`
                            );
                        });

                        $('#modalDetailPickup').modal('show');
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Gagal mengambil detail pesanan pickup.'
                        });
                    }
                });
            });

            // Mark as Complete Handler (Single Button from table)
            $(document).on('click', '.btn-complete', function() {
                const pickupId = $(this).data('id');
                const invoice = $(this).data('invoice');
                confirmPickup(pickupId, invoice);
            });

            // Mark as Complete Handler (from detail modal)
            $('#btnModalComplete').on('click', function() {
                const invoice = $('#pickupInvoiceTitle').text();
                confirmPickup(currentPickupId, invoice, function() {
                    $('#modalDetailPickup').modal('hide');
                });
            });

            function confirmPickup(id, invoice, callback = null) {
                Swal.fire({
                    title: 'Konfirmasi Pengambilan',
                    text: `Apakah Anda yakin ingin menandai pesanan ${invoice} sebagai "Sudah Diambil"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Sudah Diambil',
                    cancelButtonText: 'Batal',
                    background: '#ffffff',
                    customClass: {
                        popup: 'premium-swal-popup',
                        title: 'premium-swal-title',
                        htmlContainer: 'premium-swal-html'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/transaksi/pickup/${id}/complete`,
                            type: 'POST',
                            success: function(res) {
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: res.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    pickupTable.ajax.reload(null, false); // Reload table preserving page
                                    if (callback) callback();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Gagal',
                                        text: res.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Gagal memproses pengambilan.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                            }
                        });
                    }
                });
            }

            // Close Modal
            $(document).on('click', '.closed', function() {
                $('#modalDetailPickup').modal('hide');
                $('.pickupDataBarang').remove();
            });
        });
    </script>
@endpush
