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
                        <h1 class="mb-0 pb-0 display-4" id="title">Toko/Warehouse</h1>
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
                                                placeholder="Search" data-datatable="#tbToko">
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
                                                type="button" data-datatable="#tbToko">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button
                                                class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print"
                                                type="button" data-datatable="#tbToko">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbToko">
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
                                                data-datatable="#tbToko">
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
                                    <table class="data-table nowrap hover" id="tbToko" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Kode</th>
                                                <th class="text-muted text-small text-uppercase">Nama Toko</th>
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

    <div class="modal fade" id="tokoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
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
                            <input type="text" class="form-control" placeholder="Nama Toko" id="namaToko"
                                name="namaToko" required>
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
                                icon: response.icon,
                                title: response.title,
                                text: response.text,
                            })
                        } else if (method == 'get') {
                            getData(response)
                            return response
                        }
                    }
                });
                $('#tokoModal').modal('hide');
                $('#tbToko').DataTable().ajax.reload(null, false);
            }

            function getData(params) {
                $('#kode').val(params.data.kode);
                $('#namaToko').val(params.data.nama_toko);
            }

            $('#tbToko').DataTable({
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
                ajax: '/manajemen/warehouse/show',
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
                        data: 'nama_toko',
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

            $(document).on('click', '.edit', function() {
                $('#tokoModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                var data = {
                    kode: kode
                };
                ajaxData('get', '/manajemen/warehouse/edit', data);
            });

            $(document).on('click', '.datatable-add', function() {
                $('#tokoModal').modal('show');
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
                var kode = $('#kode').val('TK_' + generateString(4));
            });

            $(document).on('click', '.closed', function() {
                $('#tokoModal').modal('toggle');
                $('.modal-title').html('');
                $('#kode').val('');
                $('#namaToko').val('');
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var kode = $('#kode').val();
                var namaToko = $('#namaToko').val();
                var valid = $("#formValid").valid();

                var data = {
                    kode: kode,
                    toko: namaToko,
                };

                if (valid == true) {
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/manajemen/warehouse/store', data);
                        $('#namaToko').val('');
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/manajemen/warehouse/update', data);
                        $('#namaToko').val('');
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
                        ajaxData('post', '/manajemen/warehouse/destroy', data);
                    }
                })
            });

        });
    </script>
@endpush
