@extends('layouts.main')
@section('main')
    <style>
        .error {
            color: #F00;
            background-color: #FFF;
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
                                    <div class="col-12 col-sm-2 col-lg-1 col-xxl-1 mb-1">
                                        <div class="d-inline-block">
                                            <button data-bs-toggle="modal" data-bs-target="#detailModal" class="btn btn-icon btn-icon-only btn-outline-primary btn-sm" type="button">
                                                <i data-acorn-icon="eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-5 col-lg-8 col-xxl-9 text-end mb-1">
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
                                                    10 Items
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
                <div class="modal-header">
                    <h5 class="modal-title">Detail Asset</h5>
                </div>
                <div class="modal-body">
                    Total Sisa Asset Toko : {{ $total_aset }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade indexModal" id="stockInOutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="chart-3"></i>
                            <input type="text" class="form-control" placeholder="Kode Barang" id="kode_barang" name="kode_barang" required readonly hidden>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="boxes"></i>
                            <select class="form-control" id="namaBarang" name="namaBarang" data-placeholder="Nama Barang" required>
                                <option label="&nbsp;"></option>
                                <option value=""></option>
                                {{-- @foreach ($barang as $b)
                                    <option value="{{ $b->id }}">{{ $b->barang }} | Supplier:
                                        {{ $b->supplier }} | Stock Sisa: {{ $b->jumlah_masuk - $b->jumlah_keluar }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="chart-3"></i>
                            <input type="number" class="form-control" placeholder="Sisa Stock" id="sisaStock" name="sisaStock" required readonly>
                        </div>

                        <div class="mb-3 filled position-relative form-group input-group">
                            <i data-acorn-icon="chart-3"></i>
                            <input type="number" class="form-control" placeholder="Jumlah" id="jumlah" name="jumlah" required>
                            <button class="btn btn-secondary" type="button" id="getSemuaJumlah">Semua</button>
                        </div>

                        <div class="mt-3 float-end">
                            <button type="button" class="closed btn btn-muted">Close</button>
                            <button type="button" class="simpan btn btn-primary"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exchangeModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <label class="mb-3 top-label">
                                <input type="text" class="form-control" placeholder="Nama Barang" id="namaBarangExchange" name="namaBarangExchange" required readonly>
                                <span class="bg-transparent">Nama Barang</span>
                            </label>
                            <input type="text" id="kodeBarang" name="kodeBarang" readonly hidden>
                            {{-- <input type="text" id="id_in" name="id_in" readonly> --}}
                            <input type="text" id="jumlahOld" name="jumlahOld" readonly hidden>
                        </div>

                        <div class="mb-3 position-relative form-group">
                            <label class="mb-3 top-label">
                                <input type="text" class="form-control" placeholder="Sisa Exchange" id="sisaExchange" name="sisaExchange" required readonly>
                                <span class="bg-transparent">Sisa Barang</span>
                            </label>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <label class="mb-3 top-label">
                                <input type="text" class="form-control" placeholder="Nama Supplier" id="namaSupplierExchange" name="namaSupplierExchange" required readonly>
                                <span class="bg-transparent">Nama Supplier</span>
                            </label>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="delivery-truck"></i>
                            <select class="form-control" id="namaToko" name="namaToko" data-placeholder="Kirim Ke Toko" required>
                                <option label="&nbsp;"></option>
                                @foreach ($toko as $t)
                                    <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <label class="mb-3 top-label">
                                <input type="number" class="form-control" placeholder="Jumlah Kirim" id="jumlahKirim" name="jumlahKirim" required>
                                <span class="bg-transparent">Jumlah Kirim</span>
                            </label>
                        </div>

                        <div class="mt-3 float-end">
                            <button type="button" class="closed btn btn-muted">Close</button>
                            <button type="button" class="simpan btn btn-primary"></button>
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
                placeholder: '',
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
                        markup += '<div class="text-muted">Stock: ' + (result.jumlah_masuk - result
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
                placeholder: '',
                dropdownParent: $('#exchangeModal')
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
                            })
                            $('.closed').click();
                        } else if (method == 'get') {
                            getData(response)
                            return response
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
                    // $('#id_in').val(params.data.id_stock_in);
                    $('#sisaExchange').val(params.data.jumlah - params.data.terjual);
                    $('#jumlahKirim').val(params.data.jumlah - params.data.terjual);
                    $('#namaSupplierExchange').val(params.data.supplier);
                    $('#jumlahOld').val(params.data.jumlah);
                    $('#namaToko').val('TK_j7q1').trigger('change');
                }
            }

            $('#namaBarang').change(function(e) {
                e.preventDefault();
                var id = $(this).val()
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
                length: 10,
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
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'kode_barang',
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
                    },
                    {
                        data: 'total_uang_grosir',
                    },
                    {
                        data: 'aksi',
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
                $('#stockInOutModal').modal('show');
                $('h5.modal-title').html('Add Data');
                $('.simpan').html('Simpan');
                $('.simpan').attr("disabled", false);
            });

            $(document).on('click', '.exchange', function() {
                $('#exchangeModal').modal('show');
                $('#exchangeModal h5.modal-title').html('Exchange');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                var data = {
                    id: kode
                };
                ajaxData('get', '/manajemen/stock/toko/edit', data);
            });

            $(document).on('click', '.closed', function() {
                $('#stockInOutModal').modal('hide');
                $('#exchangeModal').modal('hide');
                $('h5.modal-title').html('');

                $('#namaBarang').val(['val', 'Nama Barang']).trigger('change');
                $('#sisaStock').val('');
                $('#jumlah').val('');
                $('#jumlahKirim').val('');
                $('#jumlahOld').val('');
                // $('#id_in').val('');
                $('#kodeBarang').val('');
                $('#namaToko').val(['val', 'Kirim Ke Toko']).trigger('change');
                $('#sisaExchange').val('');
                $('#namaSupplierExchange').val('');
                $('.simpan').attr("disabled", false);
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var namaBarang = $('#namaBarang').val();
                var kode_barang = $('#kode_barang').val();
                var jumlah = $('#jumlah').val();
                var kodeToko = $('#kodeToko').val();
                $('.simpan').attr("disabled", true);

                var valid = $("#formValid").valid();

                if (valid == true) {
                    if (cekButton == 'Simpan') {
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
                                $('.simpan').attr("disabled", false);
                            });
                        }
                    } else if (cekButton == 'Edit') {
                        var namaBarangExchange = $('#namaBarangExchange').val();
                        var kodeBarang = $('#kodeBarang').val();
                        // var id_in = $('#id_in').val();
                        var namaSupplierExchange = $('#namaSupplierExchange').val();
                        var namaToko = $('#namaToko').val();
                        var sisaExchange = $('#sisaExchange').val();
                        var kirimExchange = $('#jumlahKirim').val();
                        var jumlahOld = $('#jumlahOld').val();

                        var totalKirim = sisaExchange - kirimExchange;

                        var data = {
                            namaBarangExchange: namaBarangExchange,
                            kodeTokoOld: kodeToko,
                            kodeBarang: kodeBarang,
                            // idIn: id_in,
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
                            })
                        }
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
