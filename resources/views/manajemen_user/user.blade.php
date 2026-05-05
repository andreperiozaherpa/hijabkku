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
                        <h1 class="mb-0 pb-0 display-4" id="title">User Manager</h1>
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
                                            <input class="form-control form-control-sm datatable-search" placeholder="Search" data-datatable="#tbUser">
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
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-add" type="button" data-datatable="#tbUser">
                                                <i data-acorn-icon="plus"></i>
                                            </button>
                                            <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm datatable-print" type="button" data-datatable="#tbUser">
                                                <i data-acorn-icon="print"></i>
                                            </button>
                                            <div class="d-inline-block datatable-export" data-datatable="#tbUser">
                                                <button class="btn btn-icon btn-icon-only btn-outline-muted btn-sm dropdown" data-bs-toggle="dropdown" type="button" data-bs-offset="0,3">
                                                    <i data-acorn-icon="download"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <button class="dropdown-item export-copy" type="button">Copy</button>
                                                    <button class="dropdown-item export-excel" type="button">Excel</button>
                                                    <button class="dropdown-item export-cvs" type="button">Cvs</button>
                                                </div>
                                            </div>
                                            <div class="dropdown-as-select d-inline-block datatable-length" data-datatable="#tbUser">
                                                <button class="btn btn-outline-muted btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
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
                                    <table class="data-table nowrap hover" id="tbUser" data-order='[[ 0, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase">No</th>
                                                <th class="text-muted text-small text-uppercase">Nama</th>
                                                <th class="text-muted text-small text-uppercase">Kode Toko</th>
                                                <th class="text-muted text-small text-uppercase">Role</th>
                                                <th class="text-muted text-small text-uppercase">Status</th>
                                                <th class="text-muted text-small text-uppercase">Shift</th>
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

    <div class="modal fade" id="userModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValid" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="user"></i>
                            <input type="text" class="form-control" placeholder="Nama" id="nama" name="nama" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="email"></i>
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email" required>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="lock-on"></i>
                            <select class="form-control" id="role" name="role" data-placeholder="Role" required>
                                <option label="&nbsp;"></option>
                                <option value="admin">admin</option>
                                <option value="gudang">gudang</option>
                                <option value="kasir">kasir</option>
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="home-garage"></i>
                            <select class="form-control" id="namaToko" name="namaToko" data-placeholder="Nama Toko" required>
                                <option label="&nbsp;"></option>
                                @foreach ($toko as $t)
                                    <option value="{{ $t->kode }}">{{ $t->nama_toko }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="activity"></i>
                            <select class="form-control" id="status" name="status" data-placeholder="Status" required>
                                <option label="&nbsp;"></option>
                                <option value="on">on</option>
                                <option value="off">off</option>
                            </select>
                        </div>

                        <div class="filled mb-3 w-100 position-relative form-group">
                            <i data-acorn-icon="clock"></i>
                            <select class="form-control" id="shift" name="shift" data-placeholder="shift" required>
                                <option label="&nbsp;"></option>
                                <option value="0">Semua</option>
                                <option value="1">Pagi</option>
                                <option value="2">Sore</option>
                            </select>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="password" class="form-control" placeholder="Password" id="confirm_password" name="confirm_password" required>
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

    <div class="modal fade" id="userPasswordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="closed btn-close"></button>
                </div>
                <div class="modal-body">
                    <form id="formValids" class="tooltip-label-end" novalidate>
                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="email" class="form-control" placeholder="Email" id="changeEmail" name="changeEmail" readonly required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="password" class="form-control" placeholder="Password" id="changePassword" name="changePassword" required>
                        </div>

                        <div class="mb-3 filled position-relative form-group">
                            <i data-acorn-icon="barcode"></i>
                            <input type="password" class="form-control" placeholder="Password Konfirmasi" id="changeConfirmPassword" name="changeConfirmPassword" required>
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
        $('#namaToko').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#role').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#status').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });
        $('#shift').select2({
            placeholder: '',
            dropdownParent: $('#userModal')
        });

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
                                    if (response.icon == 'success') {
                                        $('.closed').click();
                                    }
                                    $('#tbUser').DataTable().ajax.reload(null, false);
                                } else {
                                    $('#tbUser').DataTable().ajax.reload(null, false);
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
                var nama = $('#nama').val(params.data.name);
                var email = $('#email').val(params.data.email).prop('disabled', true);
                var role = $('#role').val(['val', params.data.role]).trigger('change');
                var namaToko = $('#namaToko').val(['val', params.data.kode_toko]).trigger('change');
                var status = $('#status').val(['val', params.data.status]).trigger('change');
                var shift = $('#shift').val(['val', params.data.shift]).trigger('change');
                var password = $('#password').prop('disabled', true);
                var confirm_password = $('#confirm_password').prop('disabled', true);

                var email = $('#changeEmail').val(params.data.email).prop('disabled', true);
            }

            $('#tbUser').DataTable({
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
                ajax: '/user/show',
                columns: [{
                        'data': null,
                        'sortable': false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1
                        },

                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'kode_toko',
                    },
                    {
                        data: 'role',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'shift',
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

            $(document).on('click', '.datatable-add', function() {
                $('#userModal').modal('show');
                $('.modal-title').html('Add Data');
                $('.simpan').html('Simpan');
            });

            $(document).on('click', '.password', function() {
                $('#userPasswordModal').modal('show');
                $('.modal-title').html('Ubah Password');
                $('.simpan').html('Ubah');
                var kode = $(this).attr('data-kode');
                var data = {
                    kode: kode
                };
                ajaxData('get', '/user/edit', data);
            });

            $(document).on('click', '.edit', function() {
                $('#userModal').modal('show');
                $('.modal-title').html('Edit Data');
                $('.simpan').html('Edit');
                var kode = $(this).attr('data-kode');
                var data = {
                    kode: kode
                };
                ajaxData('get', '/user/edit', data);
            });

            $(document).on('click', '.closed', function() {
                $('#userModal').modal('hide');
                $('#userPasswordModal').modal('hide');
                $('#changePassword').val('');
                $('#changeConfirmPassword').val('');
            });

            $(document).on('click', '.simpan', function() {
                var cekButton = $('.simpan').html();
                var nama = $('#nama').val();
                var email = $('#email').val();
                var role = $('#role').val();
                var namaToko = $('#namaToko').val();
                var status = $('#status').val();
                var shift = $('#shift').val();
                var password = $('#password').val();
                var confirm_password = $('#confirm_password').val();
                var valid = $("#formValid").valid();

                var data = {
                    nama: nama,
                    email: email,
                    role: role,
                    namaToko: namaToko,
                    status: status,
                    shift: shift,
                    password: password,
                    confirm_password: confirm_password,
                };

                if (valid == true) {
                    if (cekButton == 'Simpan') {
                        ajaxData('post', '/user/store', data);
                    } else if (cekButton == 'Edit') {
                        ajaxData('post', '/user/update', data);
                    } else if (cekButton == 'Ubah') {
                        var changePassword = $('#changePassword').val();
                        var changeConfirmPassword = $('#changeConfirmPassword').val();
                        var changeEmail = $('#changeEmail').val();
                        var datas = {
                            params: 'password',
                            changeEmail: changeEmail,
                            changePassword: changePassword,
                            changeConfirmPassword: changeConfirmPassword,
                        };
                        ajaxData('post', '/user/update', datas);
                    }
                }
            });

            $(document).on('click', '.destroy', function() {
                var data = {
                    kode: $(this).attr('data-kode')
                }
                ajaxData('post', '/user/destroy', data);
            });

        });
    </script>
@endpush
