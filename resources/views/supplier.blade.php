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
                        <h1 class="mb-0 pb-0 display-4" id="title">Supplier</h1>
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
                                                placeholder="Search" data-datatable="#tbSupplier">
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
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add"
                                                type="button" data-datatable="#tbSupplier">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print"
                                                type="button" data-datatable="#tbSupplier">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbSupplier">
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
                                                data-datatable="#tbSupplier">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    data-bs-offset="0,3">
                                                    10 Items
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
                                    <table class="data-table nowrap hover" id="tbSupplier" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Kode</th>
                                                <th class="text-muted text-small text-uppercase">Nama Supplier</th>
                                                <th class="text-muted text-small text-uppercase">Telpon/Wa</th>
                                                <th class="text-muted text-small text-uppercase">Rekening Bank</th>
                                                <th class="text-muted text-small text-uppercase">Alamat</th>
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

    <div class="modal fade" id="supplierModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="text" class="form-control" placeholder="Kode" id="kode" name="kode"
                                readonly required>
                        </div>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="credit-card"></i>
                            <input type="text" class="form-control" placeholder="Nama Supplier" id="namaSupplier"
                                name="namaSupplier" required>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="phone"></i>
                            <input type="number" class="form-control" placeholder="telepon/Wa" id="telepon"
                                name="telepon" required>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="user"></i>
                            <input type="text" class="form-control" placeholder="Nama Rekenenig" id="namaRekening"
                                name="namaRekening" required>
                        </div>
                        <div class="mb-3 filled">
                            <i data-acorn-icon="wallet"></i>
                            <input type="text" class="form-control" placeholder="No Rekenenig" id="noRekening"
                                name="noRekening" required>
                        </div>
                        <div class="filled">
                            <textarea class="form-control" placeholder="Alamat" rows="2" id="alamat" name="alamat" required></textarea>
                            <i data-acorn-icon="compass"></i>
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
            function ajaxData(method, url, data, params) {
                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    success: function(response) {
                        if (method == 'post') {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Data Akan Diproses',
                                text: 'Anda Yakin Akan Melanjutkan Proses?',
                                showCancelButton: true,
                                confirmButtonText: 'Simpan',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        icon: response.icon,
                                        title: response.title,
                                        text: response.text,
                                    })
                                    $('.closed').click();
                                    $('#tbSupplier').DataTable().ajax.reload(null, false);
                                } else {
                                    Swal.fire('Changes are not saved', '', 'info')
                                    $('.closed').click();
                                    $('#tbSupplier').DataTable().ajax.reload(null, false);
                                }
                            })
                        } else if (method == 'get') {
                            getData(response)
                            return response
                        }
                    }
                });
            }

            function getData(params) {
                $('#kode').val(params.data.kode);
                $('#namaSupplier').val(params.data.nama_supplier);
                $('#telepon').val(params.data.telepon);
                $('#namaRekening').val(params.data.nama_rekening);
                $('#noRekening').val(params.data.no_rekening);
                $('#alamat').val(params.data.alamat);
            }

            $('#tbSupplier').DataTable({
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
                ajax: '/manajemen/supplier/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'kode',
                    },
                    {
                        data: 'nama_supplier',
                    },
                    {
                        data: 'telepon',
                    },
                    {
                        data: 'no_rekening',
                    },
                    {
                        data: 'alamat',
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
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

            $(document).on('click', '.edit', function() {
                $('#supplierModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                var data = {
                    kode: kode
                };
                ajaxData('get', '/manajemen/supplier/edit', data);
            });

            $(document).on('click', '.datatable-add', function() {
                $('#supplierModal').modal('show');
                $('.modal-title').html('Add Data');
                $('.simpan').html('Simpan');

                // declare all characters
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

                function generateString(length) {
                    let result = '';
                    const charactersLength = characters.length;
                    for (let i = 0; i < length; i++) {
                        result += characters.charAt(Math.floor(Math.random() * charactersLength));
                    }

                    return result.toLowerCase();
                }
                var kode = $('#kode').val('SPR-' + generateString(4) + new Date().getFullYear());
            });

            $(document).on('click', '.closed', function() {
                $('#supplierModal').modal('toggle');
                $('.modal-title').html('');
                $('#kode').val('');
                $('#namaSupplier').val('');
                $('#telepon').val('');
                $('#namaRekening').val('');
                $('#noRekening').val('');
                $('#alamat').val('');
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var kode = $('#kode').val();
                var namaSupplier = $('#namaSupplier').val();
                var telepon = $('#telepon').val();
                var namaRekening = $('#namaRekening').val();
                var noRekening = $('#noRekening').val();
                var alamat = $('#alamat').val();
                var valid = $("#formValid").valid();

                var data = {
                    kode: kode,
                    supplier: namaSupplier,
                    phone: telepon,
                    nama_rekening: namaRekening,
                    no_rekening: noRekening,
                    alamat: alamat,
                };

                if (valid == true) {
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/manajemen/supplier/store', data);
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/manajemen/supplier/update', data);
                    } else {

                    }
                }
            });

            $(document).on('click', '.destroy', function() {
                var data = {
                    kode: $(this).attr('data-kode')
                }
                ajaxData('post', '/manajemen/supplier/destroy', data);
            });

        });
    </script>
@endpush
