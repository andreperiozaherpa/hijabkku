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
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <input type="text" id="id" hidden>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="chart-3"></i>
                            <input type="text" class="form-control" placeholder="kode_input" id="kodeInput" name="kodeInput" required readonly>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="boxes"></i>
                            <select class="form-control" id="namaBarang" name="namaBarang" data-placeholder="Nama Barang" required>
                                <option label="&nbsp;"></option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b->kode }}">{{ $b->nama_barang }} | Grosir:
                                        {{ $b->harga_grosir }}
                                        | Jual: {{ $b->harga_jual }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="delivery-truck"></i>
                            <select class="form-control" id="namaSupplier" name="namaSupplier" data-placeholder="Nama Supplier" required>
                                <option label="&nbsp;"></option>
                                @foreach ($supplier as $s)
                                    <option value="{{ $s->kode }}">{{ $s->nama_supplier }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="chart-3"></i>
                            <input type="number" class="form-control" placeholder="Jumlah" id="jumlah" name="jumlah" required>
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
                dropdownParent: $('#stockInOutModal')
            });

            $('#namaSupplier').select2({
                placeholder: '',
                dropdownParent: $('#stockInOutModal')
            });
            $('#stocks').select2({
                placeholder: '',
                dropdownParent: $('#stockInOutModal')
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
                    }
                });
            }

            function getData(params) {
                $('#namaBarang').val(['val', params.data.kode_barang]).trigger('change').prop('readonly', true);
                $('#namaSupplier').val(['val', params.data.kode_supplier]).trigger('change').prop('readonly', true);
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
                $('#stockInOutModal').modal('show');
                $('h5.modal-title').html('Add Data');
                $('.simpan').html('Simpan');
                $('.simpan').attr('disabled', false);
                $('#kodeInput').val('masuk-' + quickRandomAlphaNum(6) + getFormattedDate());
            });

            $(document).on('click', '.edit', function() {
                $('#stockInOutModal').modal('show');
                $('h5.modal-title').html('Edit Data');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                $('#id').val(kode);
                var data = {
                    kode: kode
                };
                ajaxData('get', '/manajemen/stock/inout/edit', data);
            });

            $(document).on('click', '.closed', function() {
                $('#stockInOutModal').modal('hide');
                $('h5.modal-title').html('');
                $('#id').val('');
                $('#namaBarang').val(['val', 'Nama Barang']).trigger('change');
                $('#namaSupplier').val(['val', 'Nama Supplier']).trigger('change');
                $('#jumlah').val('');
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var kode = $('#id').val();
                var kodeBarang = $('#namaBarang').val();
                var kodeSupplier = $('#namaSupplier').val();
                var jumlah = $('#jumlah').val();
                var stocks = $('#stocks').val();
                var valid = $("#formValid").valid();
                $(this).attr('disabled', true);

                var data = {
                    kode: kode,
                    kodeBarang: kodeBarang,
                    kodeSupplier: kodeSupplier,
                    jumlah: jumlah,
                    stocks: stocks,
                    kode_input: $('#kodeInput').val()
                };

                if (valid == true) {
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/manajemen/stock/inout/store', data);
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/manajemen/stock/inout/update', data);
                    } else {

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
