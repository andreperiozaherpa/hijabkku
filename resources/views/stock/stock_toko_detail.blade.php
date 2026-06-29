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
                        <h1 class="mb-0 pb-0 display-4" id="title">Stock Toko Detail <strong>{{ $nama_toko }}</strong>
                        </h1>
                        <input type="text" id="kodeToko" value="{{ $kode }}" hidden>
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
                                        <div
                                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 border border-separator bg-foreground search-sm">
                                            <input class="form-control form-control-sm datatable-search"
                                                placeholder="Search" data-datatable="#tbStockInOut">
                                            <span class="search-magnifier-icon">
                                                <i data-acorn-icon="search"></i>
                                            </span>
                                            <span class="search-delete-icon d-none">
                                                <i data-acorn-icon="close"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-2 col-lg-1 col-xxl-1 mb-1">
                                        <div class="d-inline-block">
                                            <button data-bs-toggle="modal" data-bs-target="#detailModal"
                                                class="btn btn-icon btn-icon-only btn-outline-primary btn-sm"
                                                type="button">
                                                <i data-acorn-icon="eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-8 col-xxl-9 text-end mb-1">
                                        <div class="d-inline-block">
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add"
                                                type="button" data-datatable="#tbStockInOut">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print"
                                                type="button" data-datatable="#tbStockInOut">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbStockInOut">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown"
                                                    data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length"
                                                data-datatable="#tbStockInOut">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    data-bs-offset="0,3">
                                                    20 Items
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <a class="dropdown-item" href="#">10 Items</a>
                                                    <a class="dropdown-item active" href="#">20 Items</a>
                                                    <a class="dropdown-item" href="#">50 Items</a>
                                                    <a class="dropdown-item" href="#">100 Items</a>
                                                    <a class="dropdown-item" href="#">200 Items</a>
                                                    <a class="dropdown-item" href="#">500 Items</a>
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
                                                <th class="text-muted text-small text-uppercase">Supplier</th>
                                                <th class="text-muted text-small text-uppercase">Nama Barang</th>
                                                <th class="text-muted text-small text-uppercase">Jumlah</th>
                                                <th class="text-muted text-small text-uppercase">Terjual</th>
                                                <th class="text-muted text-small text-uppercase">Sisa</th>
                                                <th class="text-muted text-small text-uppercase">Total Sisa (Rp)</th>
                                                <th class="text-muted text-small text-uppercase">Total Sisa Grosir (Rp)</th>
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

    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center fw-bold text-primary">
                        <i data-acorn-icon="eye" class="me-2 text-primary" data-acorn-size="18"></i> Detail Asset Toko
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <h6 class="text-muted mb-2">Total Sisa Asset Toko</h6>
                    <h3 class="text-primary fw-bold">{{ $total_aset }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade indexModal" id="stockInOutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center fw-bold text-primary"></h5>
                    <button type="button" class="closed btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formStockInOut" class="tooltip-label-end" novalidate data-no-spinner>
                        <input type="text" id="kode_barang" name="kode_barang" required readonly hidden>
                        
                        <div class="row g-3">
                            <div class="col-12 form-group">
                                <label class="form-label-custom">Nama Barang</label>
                                <div class="filled w-100 position-relative">
                                    <i data-acorn-icon="boxes"></i>
                                    <select class="form-control" id="namaBarang" name="namaBarang" data-placeholder="Pilih Barang" required>
                                        <option label="&nbsp;"></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Sisa Stock</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="tag"></i>
                                    <input type="number" class="form-control" placeholder="Sisa Stock" id="sisaStock" name="sisaStock" required readonly>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Jumlah Ambil (Qty)</label>
                                <div class="filled position-relative input-group">
                                    <i data-acorn-icon="calculator"></i>
                                    <input type="number" class="form-control" placeholder="Jumlah" id="jumlah" name="jumlah" required min="1">
                                    <button class="btn btn-secondary" type="button" id="getSemuaJumlah">Semua</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer-custom">
                            <button type="button" class="closed btn btn-outline-muted" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="simpan btn btn-primary px-4"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exchangeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title d-flex align-items-center fw-bold text-primary"></h5>
                    <button type="button" class="closed btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formExchange" class="tooltip-label-end" novalidate data-no-spinner>
                        <input type="text" id="kodeBarang" name="kodeBarang" readonly hidden>
                        <input type="text" id="jumlahOld" name="jumlahOld" readonly hidden>
                        
                        <div class="row g-3">
                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Nama Barang</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="boxes"></i>
                                    <input type="text" class="form-control" placeholder="Nama Barang" id="namaBarangExchange" name="namaBarangExchange" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Sisa Barang</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="tag"></i>
                                    <input type="text" class="form-control" placeholder="Sisa Exchange" id="sisaExchange" name="sisaExchange" required readonly>
                                </div>
                            </div>
                            
                            <div class="col-12 form-group">
                                <label class="form-label-custom">Nama Supplier</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="delivery-truck"></i>
                                    <input type="text" class="form-control" placeholder="Nama Supplier" id="namaSupplierExchange" name="namaSupplierExchange" required readonly>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Kirim Ke Toko</label>
                                <div class="filled w-100 position-relative">
                                    <i data-acorn-icon="delivery-truck"></i>
                                    <select class="form-control" id="namaToko" name="namaToko" data-placeholder="Kirim Ke Toko" required>
                                        <option label="&nbsp;"></option>
                                        @foreach ($toko as $t)
                                            <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label class="form-label-custom">Jumlah Kirim</label>
                                <div class="filled position-relative">
                                    <i data-acorn-icon="calculator"></i>
                                    <input type="number" class="form-control" placeholder="Jumlah Kirim" id="jumlahKirim" name="jumlahKirim" required min="1">
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer-custom">
                            <button type="button" class="closed btn btn-outline-muted" data-bs-dismiss="modal">Close</button>
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
        $(document).ready(function() {
            $('#namaBarang').select2({
                placeholder: 'Pilih Barang',
                dropdownParent: $('#stockInOutModal'),
                ajax: {
                    url: '/manajemen/stock/toko/create',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: {
                                value: params.term
                            },
                            page: params.page,
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: data.data,
                        };
                    },
                    cache: true,
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                minimumInputLength: 0,
                templateResult: function formatResult(result) {
                    if (result.loading) return result.text;
                    var markup = '<div class="clearfix text-capitalize"><div>' + result.barang +
                        '</div>';
                    if (result.kode_barang) {
                        markup += '<div class="text-muted small">Stock: ' + (result.jumlah_masuk - result
                            .jumlah_keluar) + '</div>';
                    }
                    return markup;
                },
                templateSelection: function formatResultSelection(result) {
                    return result.barang ? '<div class="clearfix text-capitalize"><div>' + result
                        .barang +
                        '</div>' : 'Nama Barang';
                },
            });

            $('#namaToko').select2({
                placeholder: 'Kirim Ke Toko',
                dropdownParent: $('#exchangeModal')
            });

            // Auto focus
            $('#stockInOutModal').on('shown.bs.modal', function () {
                $('#namaBarang').select2('open');
            });

            $('#exchangeModal').on('shown.bs.modal', function () {
                $('#jumlahKirim').focus().select();
            });

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
                            $('.closed').first().click();
                        } else if (method == 'get') {
                            getData(response);
                            return response;
                        }
                        $('#tbStockInOut').DataTable().ajax.reload(null, false);
                    },
                    error: function(response) {
                        $('.simpan').attr("disabled", false);
                    }
                });
            }

            function getData(params) {
                if (params.res == 'create') {
                    $('#sisaStock').val(params.sisa);
                    $('#kode_barang').val(params.kode_barang);
                } else if (params.res == 'exchanges') {
                    $('#namaBarangExchange').val(params.data.nama_barang);
                    $('#kodeBarang').val(params.data.kode_barang);
                    $('#sisaExchange').val(params.data.jumlah - params.data.terjual);
                    $('#jumlahKirim').val(params.data.jumlah - params.data.terjual);
                    $('#namaSupplierExchange').val(params.data.supplier);
                    $('#jumlahOld').val(params.data.jumlah);
                    $('#namaToko').val('TK_j7q1').trigger('change');
                }
            }

            $('#namaBarang').change(function(e) {
                e.preventDefault();
                var id = $(this).val();
                if (id) {
                    var data = {
                        id: id,
                        params: 'sisa'
                    };
                    ajaxData('get', '/manajemen/stock/toko/create', data);
                }
            });

            $('#tbStockInOut').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                paging: true,
                pageLength: 20,
                buttons: ['copy', 'excel', 'csv', 'print'],
                order: [
                    [0, "asc"]
                ],
                responsive: true,
                ajax: '/manajemen/stock/toko/show/' + $('#kodeToko').val(),
                columns: [{
                        'data': null,
                        'sortable': false,
                        'searchable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: 'kode_barang',
                        orderable: false,
                    },
                    {
                        data: 'supplier',
                    },
                    {
                        data: 'nama_barang',
                    },
                    {
                        data: 'jumlah',
                    },
                    {
                        data: 'terjual',
                    },
                    {
                        data: 'sisa',
                    },
                    {
                        data: 'total_uang',
                        searchable: false,
                        orderable: true,
                    },
                    {
                        data: 'total_uang_grosir',
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

            $("#formStockInOut").validate();
            $("#formExchange").validate();

            $(document).on('click', '.datatable-add', function() {
                $('#stockInOutModal').modal('show');
                $('#stockInOutModal h5.modal-title').html('<i data-acorn-icon="plus" class="me-2 text-primary" data-acorn-size="18"></i>Add Data');
                $('#stockInOutModal .simpan').html('<i data-acorn-icon="save" class="me-1" data-acorn-size="15"></i>Simpan');
                $('#stockInOutModal .simpan').attr("disabled", false);
                new AcornIcons().replace();
            });

            $(document).on('click', '.exchange', function() {
                $('#exchangeModal').modal('show');
                $('#exchangeModal h5.modal-title').html('<i data-acorn-icon="send" class="me-2 text-primary" data-acorn-size="18"></i>Exchange Stock');
                $('#exchangeModal .simpan').html('<i data-acorn-icon="save" class="me-1" data-acorn-size="15"></i>Kirim');
                $('#exchangeModal .simpan').attr("disabled", false);
                var kode = $(this).attr('data-kode');
                var data = {
                    id: kode
                };
                ajaxData('get', '/manajemen/stock/toko/edit', data);
                new AcornIcons().replace();
            });

            $(document).on('click', '.closed', function() {
                $('#stockInOutModal').modal('hide');
                $('#exchangeModal').modal('hide');
                $('h5.modal-title').html('');

                $('#namaBarang').val(null).trigger('change');
                $('#sisaStock').val('');
                $('#jumlah').val('');
                $('#jumlahKirim').val('');
                $('#jumlahOld').val('');
                $('#kodeBarang').val('');
                $('#namaToko').val(null).trigger('change');
                $('#sisaExchange').val('');
                $('#namaSupplierExchange').val('');
                $('.simpan').attr("disabled", false);

                var validator1 = $("#formStockInOut").validate();
                validator1.resetForm();
                $('#formStockInOut').find('.is-invalid').removeClass('is-invalid');
                $('#formStockInOut').find('.is-valid').removeClass('is-valid');

                var validator2 = $("#formExchange").validate();
                validator2.resetForm();
                $('#formExchange').find('.is-invalid').removeClass('is-invalid');
                $('#formExchange').find('.is-valid').removeClass('is-valid');
            });

            $('#formStockInOut').submit(function(e) {
                e.preventDefault();
                var namaBarang = $('#namaBarang').val();
                var kode_barang = $('#kode_barang').val();
                var jumlah = $('#jumlah').val();
                var kodeToko = $('#kodeToko').val();

                var valid = $("#formStockInOut").valid();

                if (valid === true) {
                    $('#stockInOutModal .simpan').attr("disabled", true);
                    var data = {
                        namaBarang: namaBarang,
                        kode_barang: kode_barang,
                        jumlah: jumlah,
                        kodeToko: kodeToko,
                    };

                    var values = $('#jumlah').val();
                    var sisa = $('#sisaStock').val();
                    var total = sisa - values;

                    if (total >= 0) {
                        ajaxData('post', '/manajemen/stock/toko/store', data);
                    } else {
                        Swal.fire({
                            title: 'Limit',
                            text: "Barang Melebihi Limit",
                            icon: 'error',
                        }).then((result) => {
                            $('#stockInOutModal .simpan').attr("disabled", false);
                        });
                    }
                }
            });

            $('#formExchange').submit(function(e) {
                e.preventDefault();
                var namaBarangExchange = $('#namaBarangExchange').val();
                var kodeBarang = $('#kodeBarang').val();
                var namaSupplierExchange = $('#namaSupplierExchange').val();
                var namaToko = $('#namaToko').val();
                var sisaExchange = $('#sisaExchange').val();
                var kirimExchange = $('#jumlahKirim').val();
                var jumlahOld = $('#jumlahOld').val();
                var kodeToko = $('#kodeToko').val();

                var valid = $("#formExchange").valid();

                if (valid === true) {
                    $('#exchangeModal .simpan').attr("disabled", true);
                    var totalKirim = sisaExchange - kirimExchange;

                    var data = {
                        namaBarangExchange: namaBarangExchange,
                        kodeTokoOld: kodeToko,
                        kodeBarang: kodeBarang,
                        namaSupplierExchange: namaSupplierExchange,
                        namaToko: namaToko,
                        sisaExchange: sisaExchange,
                        kirimExchange: kirimExchange,
                        jumlahOld: jumlahOld,
                    };

                    if (totalKirim >= 0) {
                        ajaxData('post', '/manajemen/stock/toko/update', data);
                    } else {
                        Swal.fire({
                            title: 'Limit',
                            text: "Barang Melebihi Limit",
                            icon: 'error',
                        }).then((result) => {
                            $('#exchangeModal .simpan').attr("disabled", false);
                        });
                    }
                }
            });

            $('#getSemuaJumlah').click(function(e) {
                e.preventDefault();
                var sisaStock = $('#sisaStock').val();
                $('#jumlah').val(sisaStock);
            });
        });
    </script>
@endpush
