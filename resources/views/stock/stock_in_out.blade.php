@extends('layouts.main')
@section('main')
    <style>
        .error {
            color: #F00;
            background-color: #FFF;
        }

        /* Customize Select2 dropdown option hover/highlight states */
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary, #3454d1) !important;
            color: #fff !important;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .select2-results__option {
            padding: 8px 12px !important;
            border-radius: var(--border-radius-sm, 4px) !important;
            margin: 2px 4px !important;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: rgba(var(--primary-rgb, 52, 84, 209), 0.08) !important;
            color: var(--primary, #3454d1) !important;
        }

        .select2-dropdown {
            border-color: var(--separator, #e4e4e4) !important;
            border-radius: var(--border-radius-md, 8px) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
            padding: 4px 0 !important;
        }

        /* Premium Modal Customization */
        .modal-content {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            overflow: hidden !important;
        }

        .modal-header {
            background-color: var(--background, #f8f9fa) !important;
            padding: 20px 24px !important;
            border-bottom: 1px solid var(--separator-light, #f0f0f0) !important;
        }

        .modal-body {
            padding: 24px !important;
        }

        .form-label-custom {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            color: var(--muted, #aeaeae) !important;
            margin-bottom: 6px !important;
            display: block !important;
        }

        .modal-footer-custom {
            display: flex !important;
            justify-content: flex-end !important;
            gap: 12px !important;
            margin-top: 24px !important;
            border-top: 1px solid var(--separator-light, #f0f0f0) !important;
            padding-top: 20px !important;
        }
    </style>
    <main>
        <div class="container">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-0 pb-0 display-4" id="title">Stock Barang Masuk</h1>
                    </div>
                    <!-- Title End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="row mb-3">
                <div class="col-12">
                    <section class="scroll-section" id="hover">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-5 col-lg-3 col-xxl-2 mb-1">
                                        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#tbStockInOut">
                                            <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                            <span class="search-delete-icon d-none">
                                                <i data-acorn-icon="close"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-7 col-lg-9 col-xxl-10 text-end mb-1">
                                        <div class="d-inline-block">
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add" type="button" data-datatable="#tbStockInOut">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#tbStockInOut">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbStockInOut">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#tbStockInOut">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                                    20 Items
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">10 Items</a>
                                                    <a class="dropdown-item active" href="#">20 Items</a>
                                                    <a class="dropdown-item" href="#">50 Items</a>
                                                    <a class="dropdown-item" href="#">100 Items</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="data-table-responsive-wrapper">
                                    <table class="data-table nowrap hover" id="tbStockInOut" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Kode Barang</th>
                                                {{-- <th class="text-muted text-small text-uppercase">Supplier</th> --}}
                                                <th class="text-muted text-small text-uppercase">Nama Barang</th>
                                                <th class="text-muted text-small text-uppercase">Jumlah Masuk</th>
                                                <th class="text-muted text-small text-uppercase">Jumlah Keluar</th>
                                                <th class="text-muted text-small text-uppercase">Tanggal Masuk</th>
                                                <th class="text-muted text-small text-uppercase">Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
            <!-- Content End -->
        </div>
    </main>

    <div class="modal fade indexModal" id="stockInOutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center fw-bold text-primary"></h5>
                    <button type="button" class="closed btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate data-no-spinner>
                        <input type="text" id="id" hidden>
                        
                        <div class="row g-3">
                            <div class="col-12 form-group">
                                <label class="form-label-custom">Nama Barang</label>
                                <div class="filled w-100 position-relative">
                                    <i data-acorn-icon="boxes"></i>
                                    <select class="form-control" id="namaBarang" name="namaBarang" data-placeholder="Pilih Barang" required>
                                        <option label="&nbsp;"></option>
                                        @foreach ($barang as $b)
                                            <option value="{{ $b->kode }}">{{ $b->nama_barang }} | Grosir: Rp. {{ number_format((float)str_replace('.', '', $b->harga_grosir), 0, ',', '.') }} | Jual: Rp. {{ number_format((float)str_replace('.', '', $b->harga_jual), 0, ',', '.') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <label class="form-label-custom">Nama Supplier</label>
                                <div class="filled w-100 position-relative">
                                    <i data-acorn-icon="delivery-truck"></i>
                                    <select class="form-control" id="namaSupplier" name="namaSupplier" data-placeholder="Pilih Supplier" required>
                                        <option label="&nbsp;"></option>
                                        @foreach ($supplier as $s)
                                            <option value="{{ $s->kode }}">{{ $s->nama_supplier }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                              <div class="col-md-6 form-group">
                                <label class="form-label-custom">Kode Transaksi</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="tag"></i>
                                    <input type="text" class="form-control" placeholder="Kode Transaksi" id="kodeInput" name="kodeInput" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Jumlah Barang (Qty)</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="calculator"></i>
                                    <input type="number" class="form-control" placeholder="Jumlah (Qty)" id="jumlah" name="jumlah" required min="1">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer-custom">
                            <button type="button" class="closed btn btn-outline-muted">Close</button>
                            <button type="submit" class="simpan btn btn-primary px-4"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // Your custom JavaScript...
        $(document).ready(function() {
            $('#namaBarang').select2({
                dropdownParent: $('#stockInOutModal')
            });

            $('#namaSupplier').select2({
                dropdownParent: $('#stockInOutModal')
            });
            $('#stocks').select2({
                dropdownParent: $('#stockInOutModal')
            });

            $('#stockInOutModal').on('shown.bs.modal', function () {
                var title = $('h5.modal-title').html();
                if (title === 'Add Data') {
                    $('#namaBarang').select2('open');
                } else {
                    $('#jumlah').focus().select();
                }
            });

            function resetForm() {
                $('#id').val('');
                $('#kodeInput').val('');
                $('#namaBarang').val(null).trigger('change').prop('disabled', false);
                $('#namaSupplier').val(null).trigger('change').prop('disabled', false);
                $('#jumlah').val('');
                $('.simpan').attr('disabled', false);

                var validator = $("#formValid").validate();
                validator.resetForm();
                $('#formValid').find('.is-invalid').removeClass('is-invalid');
                $('#formValid').find('.is-valid').removeClass('is-valid');
            }

            function ajaxData(method, url, data, params) {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        if (method == 'post') {
                            Swal.fire({
                                toast: true,
                                position: 'top-right',
                                showConfirmButton: false,
                                timer: 1500,
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                            });
                            $('#stockInOutModal').modal('hide');
                            resetForm();
                        } else if (method == 'get') {
                            getData(response);
                            return response;
                        }
                        $('#tbStockInOut').DataTable().ajax.reload(null, false);
                    },
                    complete: function() {
                        $('.simpan').attr('disabled', false);
                    }
                });
            }

            function getData(params) {
                $('#namaBarang').val(params.data.kode_barang).trigger('change').prop('disabled', true);
                $('#namaSupplier').val(params.data.kode_supplier).trigger('change').prop('disabled', true);
                $('#jumlah').val(params.data.jumlah_masuk);
            }

            function quickRandomAlphaNum(length) {
                return Math.random().toString(36).slice(2, 2 + length);
            }

            function getFormattedDate() {
                const date = new Date();
                const tanggal = String(date.getDate()).padStart(2, '0');
                const bulan = String(date.getMonth() + 1).padStart(2, '0');
                const tahun = date.getFullYear();
                return tanggal + bulan + tahun;
            }

            $('#tbStockInOut').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "DESC"]
                ],
                responsive: true,
                ajax: '/manajemen/stock/inout/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        'searchable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'kode_barang',
                    },
                    // {
                    //     data: 'supplier',
                    // },
                    {
                        data: 'barang',
                    },
                    {
                        data: 'jumlah_masuk',
                    },
                    {
                        data: 'jumlah_keluar',
                    },
                    {
                        data: 'tanggal',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        orderable: false,
                    },
                ],
                sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>',
                language: {
                    paginate: {
                        previous: '<i class="cs-chevron-left"></i>',
                        next: '<i class="cs-chevron-right"></i>',
                    },
                },
            });

            $("#formValid").validate();

            $(document).on('click', '.datatable-add', function() {
                resetForm();
                $('#stockInOutModal').modal('show');
                $('h5.modal-title').html('<i data-acorn-icon="plus" class="me-2 text-primary" data-acorn-size="18"></i> Tambah Stock In/Out');
                $('.simpan').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save me-1" viewBox="0 0 16 16"><path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-.7-.3L7.3.3A1 1 0 0 0 6.5 0H2zM1 2a1 1 0 0 1 1-1h3.5a1 1 0 0 1 .7.3l1.5 1.5a1 1 0 0 1 .3.7V14H2a1 1 0 0 1-1-1V2zm11 1.5v9h-1v-9h1z"/></svg> Simpan');
                $('#kodeInput').val('masuk-' + quickRandomAlphaNum(6) + getFormattedDate());
                if (typeof AcornIcons !== 'undefined') {
                    new AcornIcons().replace();
                }
            });

            $(document).on('click', '.edit', function() {
                resetForm();
                $('#stockInOutModal').modal('show');
                $('h5.modal-title').html('<i data-acorn-icon="edit" class="me-2 text-primary" data-acorn-size="18"></i> Edit Stock In/Out');
                $('.simpan').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square me-1" viewBox="0 0 16 16"><path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/><path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/></svg> Update');
                var kode = $(this).attr('data-kode');
                $('#id').val(kode);
                var data = {
                    kode: kode
                };
                ajaxData('get', '/manajemen/stock/inout/edit', data);
                if (typeof AcornIcons !== 'undefined') {
                    new AcornIcons().replace();
                }
            });

            $(document).on('click', '.closed', function() {
                $('#stockInOutModal').modal('hide');
                resetForm();
            });

            $('#formValid').submit(function(e) {
                e.preventDefault();
                var cekButton = $('.simpan').text().trim();
                var kode = $('#id').val();
                var kodeBarang = $('#namaBarang').val();
                var kodeSupplier = $('#namaSupplier').val();
                var jumlah = $('#jumlah').val();
                var stocks = $('#stocks').val();
                var valid = $("#formValid").valid();

                var data = {
                    kode: kode,
                    kodeBarang: kodeBarang,
                    kodeSupplier: kodeSupplier,
                    jumlah: jumlah,
                    stocks: stocks,
                    kode_input: $('#kodeInput').val()
                };

                if (valid == true) {
                    $('.simpan').attr('disabled', true);
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/manajemen/stock/inout/store', data);
                    } else if (cekButton == 'Update') {
                        ajaxData('post', '/manajemen/stock/inout/update', data);
                    }
                }
            });

            $(document).on('click', '.destroy', function() {
                var data = {
                    kode: $(this).attr('data-kode')
                }
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        ajaxData('post', '/manajemen/stock/inout/destroy', data);
                    }
                })
            });

        });
    </script>
@endpush
